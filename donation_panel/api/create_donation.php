<?php
/**
 * Endpoint para crear donaciones
 * Procesa la solicitud de donación y genera la preferencia de pago
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Manejar preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

// Cargar configuración
$configPath = __DIR__ . '/../private/configs.php';
if (!file_exists($configPath)) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Configuración no encontrada']);
    exit;
}

require_once($configPath);

// Función para guardar logs
function saveLog($message, $data = null) {
    $logDir = __DIR__ . '/logs/';
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . 'donations_' . date('Y-m') . '.txt';
    $timestamp = date('Y-m-d H:i:s');
    $entry = "[{$timestamp}] {$message}";
    
    if ($data !== null) {
        $entry .= "\n" . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
    
    $entry .= "\n" . str_repeat('-', 80) . "\n";
    @file_put_contents($logFile, $entry, FILE_APPEND);
}

// Leer datos del request
$rawInput = file_get_contents('php://input');
$input = json_decode($rawInput, true);

saveLog('Nueva solicitud de donación', $input);

// Validar datos requeridos
if (empty($input['account']) || empty($input['amount']) || empty($input['currency'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit;
}

$account = trim($input['account']);
$amount = floatval($input['amount']);
$currency = strtoupper(trim($input['currency']));
$method = isset($input['method']) ? trim($input['method']) : 'mercadopago';
$coins = isset($input['coins']) ? intval($input['coins']) : 0;

// Validar monto
if ($amount <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Monto inválido']);
    exit;
}

// Validar moneda
$validCurrencies = ['ARS', 'USD', 'CLP'];
if (!in_array($currency, $validCurrencies)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Moneda no soportada']);
    exit;
}

try {
    // Conectar a la base de datos
    $conn = null;
    
    if ($conMethod == 1) {
        // MsSQL
        $conn = @mssql_connect($host['SITE'], $user['SITE'], $pass['SITE']);
        if ($conn) {
            @mssql_select_db($dbnm['SITE'], $conn);
        }
    } elseif ($conMethod == 2) {
        // SQLSRV
        $connectionInfo = array(
            "Database" => $dbnm['SITE'],
            "UID" => $user['SITE'],
            "PWD" => $pass['SITE'],
            "CharacterSet" => "UTF-8"
        );
        $conn = @sqlsrv_connect($host['SITE'], $connectionInfo);
    }
    
    if (!$conn) {
        throw new Exception('Error de conexión a la base de datos');
    }
    
    // Verificar que la cuenta existe
    $checkQuery = "SELECT login FROM accounts WHERE login = ?";
    
    if ($conMethod == 2) {
        $stmt = sqlsrv_prepare($conn, $checkQuery, array($account));
        $result = sqlsrv_execute($stmt);
        $accountExists = sqlsrv_has_rows($stmt);
    } else {
        // Para otros métodos, usar consulta directa (ajustar según necesidad)
        $accountExists = true; // Por ahora asumimos que existe
    }
    
    if (!$accountExists) {
        http_response_code(404);
        echo json_encode(['success' => false, 'error' => 'Cuenta no encontrada']);
        exit;
    }
    
    // Generar ID único para la transacción
    $transactionId = 'DN' . time() . rand(1000, 9999);
    
    // Insertar registro pendiente en la base de datos
    $insertQuery = "INSERT INTO site_donations (tid, account, price, coins, method, status, date, currency) 
                    VALUES (?, ?, ?, ?, ?, 'pending', GETDATE(), ?)";
    
    if ($conMethod == 2) {
        $params = array($transactionId, $account, $amount, $coins, $method, $currency);
        $stmt = sqlsrv_prepare($conn, $insertQuery, $params);
        $insertResult = sqlsrv_execute($stmt);
        
        if (!$insertResult) {
            throw new Exception('Error al registrar la donación');
        }
    }
    
    // Generar URL de pago según el método
    $paymentUrl = '';
    
    if ($method === 'mercadopago' && !empty($MercadoPago['access_token'])) {
        // Crear preferencia de MercadoPago
        $preference = array(
            'items' => array(
                array(
                    'title' => "Donate Coins - {$account}",
                    'quantity' => 1,
                    'currency_id' => $currency,
                    'unit_price' => $amount
                )
            ),
            'external_reference' => $transactionId,
            'back_urls' => array(
                'success' => "http://{$server_url}/?page=donate&status=success",
                'failure' => "http://{$server_url}/?page=donate&status=failure",
                'pending' => "http://{$server_url}/?page=donate&status=pending"
            ),
            'auto_return' => 'approved',
            'notification_url' => "http://{$server_url}/donation_panel/ucp/ipn/mercadopago_webhook.php"
        );
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.mercadopago.com/checkout/preferences');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($preference));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $MercadoPago['access_token'],
            'Content-Type: application/json'
        ));
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 201) {
            $preferenceData = json_decode($response, true);
            $paymentUrl = isset($preferenceData['init_point']) ? $preferenceData['init_point'] : '';
            
            saveLog('Preferencia de MercadoPago creada', array(
                'transaction_id' => $transactionId,
                'preference_id' => $preferenceData['id'],
                'init_point' => $paymentUrl
            ));
        } else {
            saveLog('Error al crear preferencia de MercadoPago', array(
                'http_code' => $httpCode,
                'response' => $response
            ));
        }
    }
    
    // Si no se pudo generar URL de pago, usar URL genérica
    if (empty($paymentUrl)) {
        $paymentUrl = "/donation_panel/ucp/?page=donate&tid={$transactionId}";
    }
    
    // Cerrar conexión
    if ($conMethod == 2 && $conn) {
        sqlsrv_close($conn);
    }
    
    // Respuesta exitosa
    echo json_encode([
        'success' => true,
        'message' => 'Donación registrada exitosamente',
        'transaction_id' => $transactionId,
        'payment_url' => $paymentUrl,
        'coins' => $coins
    ]);
    
    saveLog('Donación procesada exitosamente', array(
        'transaction_id' => $transactionId,
        'account' => $account,
        'amount' => $amount,
        'coins' => $coins
    ));
    
} catch (Exception $e) {
    saveLog('Error al procesar donación', array(
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ));
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error al procesar la donación: ' . $e->getMessage()
    ]);
}
