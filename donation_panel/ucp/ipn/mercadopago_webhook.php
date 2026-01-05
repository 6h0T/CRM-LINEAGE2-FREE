<?php
/**
 * MercadoPago Webhook - Implementación Actualizada 2024
 * 
 * Esta versión usa la API moderna de MercadoPago con:
 * - Access Token en lugar de client_id/client_secret
 * - Validación de firma secreta (x-signature)
 * - Webhooks en lugar de IPN (descontinuado)
 * 
 * Configurar en MercadoPago Dashboard:
 * URL: https://tu-dominio.com/ucp/ipn/mercadopago_webhook.php
 * Eventos: payment
 */

error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('default_charset', 'UTF-8');
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Responder 200 OK inmediatamente para evitar reintentos
http_response_code(200);

function saveLog($text, $dumpData = null) {
    $logDir = __DIR__ . "/logs/";
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . "mercadopago_" . date('Y-m') . ".txt";
    $timestamp = date('d/m/Y H:i:s');
    $logEntry = "[{$timestamp}] {$text}";
    
    if ($dumpData !== null) {
        $logEntry .= " - Data: " . json_encode($dumpData, JSON_UNESCAPED_UNICODE);
    }
    
    $logEntry .= "\r\n";
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

function vCode($content) {
    return addslashes(htmlentities(trim($content), ENT_QUOTES, 'UTF-8'));
}

/**
 * Valida la firma del webhook de MercadoPago
 * Según documentación oficial 2024
 */
function validateSignature($xSignature, $xRequestId, $dataId, $secretKey) {
    if (empty($xSignature) || empty($secretKey)) {
        return false;
    }
    
    // Separar x-signature en partes
    $parts = explode(',', $xSignature);
    $ts = null;
    $hash = null;
    
    foreach ($parts as $part) {
        $keyValue = explode('=', $part, 2);
        if (count($keyValue) == 2) {
            $key = trim($keyValue[0]);
            $value = trim($keyValue[1]);
            if ($key === 'ts') {
                $ts = $value;
            } elseif ($key === 'v1') {
                $hash = $value;
            }
        }
    }
    
    if (empty($ts) || empty($hash)) {
        return false;
    }
    
    // Generar el manifest string
    $manifest = "id:{$dataId};request-id:{$xRequestId};ts:{$ts};";
    
    // Crear HMAC SHA256
    $calculatedHash = hash_hmac('sha256', $manifest, $secretKey);
    
    return hash_equals($calculatedHash, $hash);
}

/**
 * Obtiene información del pago usando cURL con Access Token
 */
function getPaymentInfo($paymentId, $accessToken) {
    $url = "https://api.mercadopago.com/v1/payments/" . $paymentId;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $accessToken,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        saveLog("cURL Error: " . $error);
        return null;
    }
    
    if ($httpCode !== 200) {
        saveLog("API Error - HTTP Code: " . $httpCode, $response);
        return null;
    }
    
    return json_decode($response, true);
}

// ============================================
// INICIO DEL PROCESO
// ============================================

saveLog("=== Nueva notificación recibida ===");

// Cargar configuración
if (file_exists('../private/configs.php')) {
    require('../private/configs.php');
} else {
    saveLog("ERROR: No se encontró configs.php");
    exit;
}

if (file_exists('../../private/configs.php') && (!isset($host) || !isset($dbnm) || !isset($user) || !isset($pass))) {
    require('../../private/configs.php');
}

// Verificar que MercadoPago esté configurado
if (empty($MercadoPago['access_token'])) {
    // Fallback: intentar usar client_id y client_secret para obtener access_token
    if (!empty($MercadoPago['client_id']) && !empty($MercadoPago['client_secret'])) {
        saveLog("ADVERTENCIA: Usando client_id/client_secret (método legacy). Se recomienda configurar access_token directamente.");
        
        // Obtener access_token usando OAuth
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.mercadopago.com/oauth/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'client_credentials',
            'client_id' => $MercadoPago['client_id'],
            'client_secret' => $MercadoPago['client_secret']
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        
        $tokenResponse = curl_exec($ch);
        curl_close($ch);
        
        $tokenData = json_decode($tokenResponse, true);
        if (!empty($tokenData['access_token'])) {
            $accessToken = $tokenData['access_token'];
        } else {
            saveLog("ERROR: No se pudo obtener access_token", $tokenData);
            exit;
        }
    } else {
        saveLog("ERROR: No hay access_token ni client_id/client_secret configurados");
        exit;
    }
} else {
    $accessToken = $MercadoPago['access_token'];
}

// Obtener headers para validación de firma
$xSignature = isset($_SERVER['HTTP_X_SIGNATURE']) ? $_SERVER['HTTP_X_SIGNATURE'] : '';
$xRequestId = isset($_SERVER['HTTP_X_REQUEST_ID']) ? $_SERVER['HTTP_X_REQUEST_ID'] : '';

// Leer el body de la notificación (formato Webhook moderno)
$rawBody = file_get_contents('php://input');
$notification = json_decode($rawBody, true);

saveLog("Headers - x-signature: " . $xSignature . ", x-request-id: " . $xRequestId);
saveLog("Body recibido", $notification);

// Determinar el ID del pago y tipo de notificación
$paymentId = null;
$notificationType = null;

// Formato Webhook moderno (JSON body)
if (!empty($notification)) {
    $notificationType = isset($notification['type']) ? $notification['type'] : '';
    $paymentId = isset($notification['data']['id']) ? $notification['data']['id'] : '';
    
    // También puede venir en query params
    if (empty($paymentId) && isset($_GET['data_id'])) {
        $paymentId = $_GET['data_id'];
    }
    if (empty($paymentId) && isset($_GET['data.id'])) {
        $paymentId = $_GET['data.id'];
    }
}

// Formato IPN legacy (query params)
if (empty($paymentId) && isset($_GET['id'])) {
    $paymentId = $_GET['id'];
    $notificationType = isset($_GET['topic']) ? $_GET['topic'] : 'payment';
}

if (empty($paymentId)) {
    saveLog("ERROR: No se recibió ID de pago");
    exit;
}

saveLog("Payment ID: " . $paymentId . ", Type: " . $notificationType);

// Validar firma si está disponible (recomendado en producción)
if (!empty($MercadoPago['webhook_secret']) && !empty($xSignature)) {
    $dataIdForValidation = isset($_GET['data.id']) ? $_GET['data.id'] : $paymentId;
    
    if (!validateSignature($xSignature, $xRequestId, $dataIdForValidation, $MercadoPago['webhook_secret'])) {
        saveLog("ERROR: Validación de firma fallida");
        // En producción, descomentar la siguiente línea:
        // exit;
    } else {
        saveLog("Firma validada correctamente");
    }
}

// Solo procesar notificaciones de pago
if ($notificationType !== 'payment' && $notificationType !== 'payment.created' && $notificationType !== 'payment.updated') {
    saveLog("Notificación ignorada - Tipo: " . $notificationType);
    exit;
}

// Obtener información del pago
$paymentInfo = getPaymentInfo($paymentId, $accessToken);

if (empty($paymentInfo) || empty($paymentInfo['id'])) {
    saveLog("ERROR: No se pudo obtener información del pago");
    exit;
}

saveLog("Información del pago obtenida", $paymentInfo);

// Extraer datos del pago
$tid = trim($paymentInfo['id']);
$status = strtolower(trim($paymentInfo['status']));
$price = isset($paymentInfo['transaction_details']['total_paid_amount']) 
    ? trim($paymentInfo['transaction_details']['total_paid_amount']) 
    : trim($paymentInfo['transaction_amount']);
$ref = trim($paymentInfo['external_reference']);
$curr = trim($paymentInfo['currency_id']);

if (empty($tid) || empty($status) || empty($ref)) {
    saveLog("ERROR: Parámetros incompletos - tid: {$tid}, status: {$status}, ref: {$ref}");
    exit;
}

// Conectar a la base de datos
if (file_exists('../private/classes/DB.php')) {
    require('../private/classes/DB.php');
} else {
    require('../../private/classes/DB.php');
}

new DB($conMethod, $host, $user, $pass, $dbnm, $port);

// Buscar la donación por protocolo
$d = DB::Executa("SELECT TOP 1 * FROM site_donations WHERE protocolo = '" . vCode($ref) . "'", "SITE");
if (count($d) == 0) {
    saveLog("ERROR: Protocolo inexistente - REF: " . $ref);
    exit;
}

$account = trim($d[0]['account']);
$coinsEntregar = intval(trim($d[0]['quant_coins']) + trim($d[0]['coins_bonus']));
$coinsEntregues = intval(trim($d[0]['coins_entregues']));
$valor = trim($d[0]['valor']);
$currentStatus = intval(trim($d[0]['status']));
$moeda = trim($d[0]['currency']);

saveLog("Donación encontrada - Account: {$account}, Coins: {$coinsEntregar}, Status actual: {$currentStatus}");

/*
Estados de MercadoPago:
- pending: El usuario aún no completó el proceso de pago
- approved: El pago fue aprobado y acreditado
- authorized: El pago fue autorizado pero no capturado
- in_process: El pago está en revisión
- in_mediation: Los usuarios iniciaron una disputa
- rejected: El pago fue rechazado
- cancelled: El pago fue cancelado
- refunded: El pago fue devuelto al usuario
- charged_back: Se hizo un chargeback
*/

switch ($status) {
    case 'approved':
        $finalStatus = 3; // Pagado
        break;
    case 'authorized':
        $finalStatus = 3; // Pagado (autorizado)
        break;
    case 'in_mediation':
        $finalStatus = 3; // Pagado (en disputa)
        break;
    case 'cancelled':
    case 'refunded':
    case 'charged_back':
        $finalStatus = 5; // Cancelada
        break;
    case 'rejected':
        $finalStatus = 5; // Rechazada
        break;
    default:
        $finalStatus = 1; // Pendiente
        break;
}

// No revertir estados ya entregados
if ($currentStatus == 4 && $finalStatus == 3) {
    $finalStatus = 4;
}

// No modificar estados eliminados
if ($currentStatus == 2 && $finalStatus != 3) {
    $finalStatus = 2;
}

// Actualizar estado de la orden
$updateOrder = DB::Executa("UPDATE site_donations SET ultima_alteracao = '" . time() . "', transaction_code = '" . vCode($tid) . "', status = '" . $finalStatus . "', status_real = '" . vCode($status) . "' WHERE protocolo = '" . vCode($ref) . "'", "SITE");

if (!$updateOrder) {
    saveLog("ERROR: No se pudo actualizar el estado de la transacción");
    exit;
}

saveLog("Estado actualizado a: " . $finalStatus);

// Verificar si la entrega automática está habilitada
if ($autoDelivery != 1) {
    saveLog("Entrega automática deshabilitada - Transacción procesada correctamente");
    exit;
}

// Acreditar coins si el pago fue aprobado y no se han entregado
if ($coinsEntregues != $coinsEntregar && $finalStatus == 3) {
    
    // Validar monto pagado
    if (!empty($price) && number_format($price, 2, '.', '') < number_format($valor, 2, '.', '')) {
        saveLog("ERROR: Monto pagado inferior al registrado - Pagado: {$price}, Esperado: {$valor}");
        exit;
    }
    
    // Validar moneda
    if (!empty($curr) && $curr != $moeda) {
        saveLog("ERROR: Moneda diferente - Recibida: {$curr}, Esperada: {$moeda}");
        exit;
    }
    
    // Verificar si es acreditación automática directa al personaje
    $autoCredit = isset($d[0]['auto_credit']) ? intval($d[0]['auto_credit']) : 0;
    $charId = isset($d[0]['personagem']) ? intval($d[0]['personagem']) : 0;
    
    if ($autoCredit == 1 && $charId > 0) {
        // Acreditación directa al personaje
        saveLog("Modo auto-crédito activado - Acreditando directamente al personaje ID: {$charId}");
        
        // Verificar que el personaje existe
        $charData = DB::Executa("SELECT TOP 1 char_id, char_name, account_name FROM user_data WHERE char_id = '" . $charId . "'", "WORLD");
        
        if (count($charData) > 0) {
            $charName = $charData[0]['char_name'];
            
            // Agregar coins directamente al inventario del personaje
            // Buscar el item de Donate Coin en la configuración
            $donateItemId = 4037; // ID del item Donate Coin (ajustar según tu servidor)
            
            // Insertar los coins en el inventario del personaje
            $addCoins = DB::Executa("
                INSERT INTO items (owner_id, item_id, count, loc, loc_data)
                VALUES ('" . $charId . "', '" . $donateItemId . "', '" . $coinsEntregar . "', 'INVENTORY', '0')
            ", "WORLD");
            
            if ($addCoins) {
                // Actualizar estado de la donación
                $updateOrder = DB::Executa("UPDATE site_donations SET coins_entregues = '" . $coinsEntregar . "', status = '4' WHERE protocolo = '" . vCode($ref) . "'", "SITE");
                
                saveLog("ÉXITO: Coins acreditados directamente al personaje - Char: {$charName} (ID: {$charId}), Coins: {$coinsEntregar}");
                
                // Registrar en log de conversión
                DB::Executa("INSERT INTO site_log_convertcoins (quant_coins, account, destinatario, cdata) VALUES ('" . $coinsEntregar . "', '" . vCode($account) . "', '" . $charId . "', GETDATE())", "SITE");
                
            } else {
                saveLog("ERROR: No se pudieron acreditar los coins al personaje");
            }
        } else {
            saveLog("ERROR: Personaje no encontrado - ID: {$charId}");
        }
        
    } else {
        // Acreditación tradicional al balance de la cuenta
        saveLog("Modo tradicional - Acreditando al balance de la cuenta");
        
        // Actualizar coins entregados
        $updateOrder = DB::Executa("UPDATE site_donations SET coins_entregues = '" . $coinsEntregar . "', status = '4' WHERE protocolo = '" . vCode($ref) . "'", "SITE");
        
        if (!$updateOrder) {
            saveLog("ERROR: No se pudo actualizar coins_entregues");
            exit;
        }
        
        // Acreditar saldo
        $checkExists = DB::Executa("SELECT TOP 1 * FROM site_balance WHERE account = '" . vCode($account) . "'", "SITE");
        
        if (count($checkExists) > 0) {
            $addBalance = DB::Executa("UPDATE site_balance SET saldo = (saldo + " . $coinsEntregar . ") WHERE account = '" . vCode($account) . "'", "SITE");
        } else {
            $addBalance = DB::Executa("INSERT INTO site_balance (account, saldo) VALUES ('" . vCode($account) . "', '" . $coinsEntregar . "')", "SITE");
        }
        
        if ($addBalance) {
            saveLog("ÉXITO: Saldo acreditado - Account: {$account}, Coins: {$coinsEntregar}");
        } else {
            saveLog("ERROR: No se pudo acreditar el saldo");
        }
    }
    
    exit;
}

saveLog("Transacción procesada correctamente - Status: " . $status);
