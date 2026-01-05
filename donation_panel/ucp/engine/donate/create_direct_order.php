<?php
/**
 * Endpoint para crear donaciones directas sin login
 * Los coins se acreditan directamente al personaje especificado
 */

// Habilitar errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log de debugging
$logFile = __DIR__ . '/../../debug_donation.txt';
file_put_contents($logFile, 
    "\n=== " . date('Y-m-d H:i:s') . " ===\n" . 
    "POST: " . print_r($_POST, true) . "\n" .
    "GET: " . print_r($_GET, true) . "\n",
    FILE_APPEND
);

if(!$indexing) { 
    file_put_contents($logFile, "ERROR: indexing no está definido\n", FILE_APPEND);
    exit('ERROR: indexing no definido'); 
}

// No requiere login - comentamos esta validación
// if($logged != 1) { fim('Access denied!', 'RELOAD'); }

if(!isset($funct) || $funct['donate'] != 1) { 
    file_put_contents($logFile, "ERROR: funct[donate] no está activo\n", FILE_APPEND);
    fim($LANG[40003], 'ERROR', './'); 
}

// Obtener datos del formulario
$charName = !empty($_POST['charName']) ? trim($_POST['charName']) : '';
$qtdCoins = !empty($_POST['qtdCoins']) ? intval(trim($_POST['qtdCoins'])) : '';
$metodo_pgto = !empty($_POST['metodo_pgto']) ? vCode($_POST['metodo_pgto']) : '';

// Validaciones
if(empty($charName) || empty($qtdCoins) || empty($metodo_pgto)) { 
    fim($LANG[10024]); 
}

if(!is_numeric($qtdCoins)) { 
    fim($LANG[10025], 'ERROR', './'); 
}

if($qtdCoins < 100) {
    fim('La cantidad mínima es 100 coins', 'ERROR', './');
}

require('private/classes/classDonate.php');

// Validar que el personaje existe y obtener su cuenta
$charData = DB::Executa("SELECT TOP 1 char_name, char_id, account_name FROM user_data WHERE char_name = '".vCode($charName)."'", "WORLD");

if(count($charData) == 0) {
    fim('El personaje no existe', 'ERROR', './');
}

$accountName = $charData[0]['account_name'];
$charId = $charData[0]['char_id'];

// Configurar métodos de pago disponibles
if(!empty($G2APay['actived'])) { $metodos[] = 'G2APay'; }
if(!empty($PagSeguro['actived'])) { $metodos[] = 'PagSeguro'; }
if(!empty($PayPal['actived'])) { $metodos[] = 'PayPal_USD'; $metodos[] = 'PayPal_EUR'; $metodos[] = 'PayPal_BRL'; }
if(!empty($Banking['actived'])) { $metodos[] = 'Banking'; }
if(!empty($MercadoPago['actived'])) { $metodos[] = 'MercadoPago'; }
if(!empty($PagosOnline['actived'])) { $metodos[] = 'PagosOnline'; }
if(!empty($PayGol['BRL']['actived'])) { $metodos[] = 'PayGol_BRL'; }
if(!empty($PayGol['EUR']['actived'])) { $metodos[] = 'PayGol_EUR'; }
if(!empty($PayGol['USD']['actived'])) { $metodos[] = 'PayGol_USD'; }
if(!empty($WebMoney['actived'])) { $metodos[] = 'WebMoney'; }
if(!empty($Payza['actived'])) { $metodos[] = 'Payza'; }
if(!empty($Skrill['actived'])) { $metodos[] = 'Skrill'; }
if(!empty($PicPay['actived'])) { $metodos[] = 'PicPay'; }
if(!empty($Ualabis['actived'])) { $metodos[] = 'Ualabis'; }
if(!empty($Weear['actived'])) { $metodos[] = 'Weear'; }

// Determinar precio por coin según método de pago
if($metodo_pgto == 'PagSeguro') {
    $coinPrice = $PagSeguro['coin_price'];
    $curr = 'BRL';
} else if($metodo_pgto == 'Banking') {
    $coinPrice = $Banking['coin_price'];
    $curr = $Banking['currency'];
} else if($metodo_pgto == 'PicPay') {
    $coinPrice = $PicPay['coin_price'];
    $curr = $PicPay['currency'];
} else if($metodo_pgto == 'PayPal_USD') {
    $coinPrice = $PayPal['USD']['coin_price'];
    $curr = 'USD';
} else if($metodo_pgto == 'PayPal_BRL') {
    $coinPrice = $PayPal['BRL']['coin_price'];
    $curr = 'BRL';
} else if($metodo_pgto == 'PayPal_EUR') {
    $coinPrice = $PayPal['EUR']['coin_price'];
    $curr = 'EUR';
} else if($metodo_pgto == 'MercadoPago') {
    $coinPrice = $MercadoPago['coin_price'];
    $curr = $MercadoPago['currency'];
} else if($metodo_pgto == 'PagosOnline') {
    $coinPrice = $PagosOnline['coin_price'];
    $curr = $PagosOnline['currency'];
} else if($metodo_pgto == 'PayGol_USD') {
    $coinPrice = $PayGol['USD']['coin_price'];
    $curr = 'USD';
} else if($metodo_pgto == 'PayGol_BRL') {
    $coinPrice = $PayGol['BRL']['coin_price'];
    $curr = 'BRL';
} else if($metodo_pgto == 'PayGol_EUR') {
    $coinPrice = $PayGol['EUR']['coin_price'];
    $curr = 'EUR';
} else if($metodo_pgto == 'WebMoney') {
    $coinPrice = $WebMoney['coin_price'];
    $curr = $WebMoney['currency'];
} else if($metodo_pgto == 'Payza') {
    $coinPrice = $Payza['coin_price'];
    $curr = $Payza['currency'];
} else if($metodo_pgto == 'Skrill') {
    $coinPrice = $Skrill['coin_price'];
    $curr = $Skrill['currency'];
} else if($metodo_pgto == 'Ualabis') {
    $coinPrice = $Ualabis['coin_price'];
    $curr = $Ualabis['currency'];
} else if($metodo_pgto == 'Weear') {
    $coinPrice = $Weear['coin_price'];
    $curr = $Weear['currency'];
} else {
    $coinPrice = $G2APay['coin_price'];
    $curr = $G2APay['currency'];
}

if(!in_array($metodo_pgto, $metodos)) {
    fim($LANG[10027], 'ERROR', './');
}

// Calcular bonus
$qtdBonus = 0;

if($bonusActived == 1) {
    $count1 = (isset($buyCoins['bonus_count'][1]) ? intval($buyCoins['bonus_count'][1]) : 0);
    $count2 = (isset($buyCoins['bonus_count'][2]) ? intval($buyCoins['bonus_count'][2]) : 0);
    $count3 = (isset($buyCoins['bonus_count'][3]) ? intval($buyCoins['bonus_count'][3]) : 0);
    $count4 = (isset($buyCoins['bonus_count'][4]) ? intval($buyCoins['bonus_count'][4]) : 0);
    $count5 = (isset($buyCoins['bonus_count'][5]) ? intval($buyCoins['bonus_count'][5]) : 0);
    
    $bonus1 = (isset($buyCoins['bonus_percent'][1]) ? intval($buyCoins['bonus_percent'][1]) : 0);
    $bonus2 = (isset($buyCoins['bonus_percent'][2]) ? intval($buyCoins['bonus_percent'][2]) : 0);
    $bonus3 = (isset($buyCoins['bonus_percent'][3]) ? intval($buyCoins['bonus_percent'][3]) : 0);
    $bonus4 = (isset($buyCoins['bonus_percent'][4]) ? intval($buyCoins['bonus_percent'][4]) : 0);
    $bonus5 = (isset($buyCoins['bonus_percent'][5]) ? intval($buyCoins['bonus_percent'][5]) : 0);
    
    if($qtdCoins >= $count5) {
        $qtdBonus = floor(($qtdCoins*$bonus5)/100);
    } else if($qtdCoins >= $count4) {
        $qtdBonus = floor(($qtdCoins*$bonus4)/100);
    } else if($qtdCoins >= $count3) {
        $qtdBonus = floor(($qtdCoins*$bonus3)/100);
    } else if($qtdCoins >= $count2) {
        $qtdBonus = floor(($qtdCoins*$bonus2)/100);
    } else if($qtdCoins >= $count1) {
        $qtdBonus = floor(($qtdCoins*$bonus1)/100);
    }
}

// Calcular valor total
$valor = number_format((($qtdCoins*$coinPrice)), 2, '.', '');

// Generar protocolo único
$protocolo = strtoupper(substr(md5(time().$accountName.$charId), 0, 15));

// Insertar donación en la base de datos
// Modificamos para incluir el char_id directamente
$insertDonation = DB::Executa("
    INSERT INTO site_donations (
        account, 
        personagem, 
        price, 
        currency, 
        metodo_pgto, 
        quant_coins, 
        coins_bonus, 
        valor, 
        data, 
        protocolo,
        status,
        auto_credit
    ) VALUES (
        '".$accountName."', 
        '".$charId."', 
        '".$coinPrice."', 
        '".$curr."', 
        '".$metodo_pgto."', 
        '".$qtdCoins."', 
        '".$qtdBonus."', 
        '".$valor."', 
        '".time()."',
        '".$protocolo."',
        '0',
        '1'
    )", "SITE");

if(!$insertDonation) {
    fim('Error al registrar la donación', 'ERROR', './');
}

// Redirigir según el método de pago
if($metodo_pgto == 'MercadoPago') {
    
    require('private/includes/MercadoPago.php');
    
    $preference = new MercadoPago\Preference();
    
    $item = new MercadoPago\Item();
    $item->title = $qtdCoins.' '.$coinName."'s".($qtdBonus > 0 ? ' + '.$qtdBonus.' Bonus' : '');
    $item->quantity = 1;
    $item->currency_id = $curr;
    $item->unit_price = floatval($valor);
    
    $preference->items = array($item);
    $preference->external_reference = $protocolo;
    
    $preference->back_urls = array(
        "success" => "http://".$server_url."/donation_panel/ucp/?module=donate&page=order_pay&protocolo=".$protocolo,
        "failure" => "http://".$server_url."/donation_panel/ucp/?module=donate&page=order_pay&protocolo=".$protocolo,
        "pending" => "http://".$server_url."/donation_panel/ucp/?module=donate&page=order_pay&protocolo=".$protocolo
    );
    
    $preference->auto_return = "approved";
    $preference->notification_url = "http://".$server_url."/donation_panel/ucp/ipn/mercadopago_webhook.php";
    
    $preference->save();
    
    if($MercadoPago['testando'] == 1) {
        $payUrl = $preference->sandbox_init_point;
    } else {
        $payUrl = $preference->init_point;
    }
    
    header('Location: '.$payUrl);
    exit;
    
} else if($metodo_pgto == 'PayPal_USD' || $metodo_pgto == 'PayPal_BRL' || $metodo_pgto == 'PayPal_EUR') {
    
    header('Location: ./?module=donate&page=order_pay&protocolo='.$protocolo);
    exit;
    
} else {
    
    header('Location: ./?module=donate&page=order_pay&protocolo='.$protocolo);
    exit;
    
}
