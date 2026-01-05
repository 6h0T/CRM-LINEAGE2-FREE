<?php

###########################################################
##                  CONFIGURACIÓN GENERAL                ##
###########################################################

$server_name = "Tu Servidor L2"; // Nombre del servidor
$server_chronicle = "Interlude"; // Crónica del servidor
$server_url = "tu-dominio.com"; // URL del servidor (SIN http:// o https://)
$panel_url = "tu-dominio.com/donation_panel/ucp"; // URL del panel (SIN http:// o https://)

###########################################################
##            CONFIGURACIÓN DE BASE DE DATOS             ##
###########################################################

// Método de conexión: 1 = mssql_connect (PHP 5.x), 2 = sqlsrv_connect (PHP 7+)
$conMethod = 2;

// Hosts de las bases de datos
$host['DB'] = 'tu-servidor-sql.com'; // o dirección IP
$host['WORLD'] = 'tu-servidor-sql.com';
$host['SITE'] = 'tu-servidor-sql.com';

// Puertos (por defecto 1433 para SQL Server)
$port['DB'] = '1433';
$port['WORLD'] = '1433';
$port['SITE'] = '1433';

// Nombres de las bases de datos
$dbnm['DB'] = 'lin2db';
$dbnm['WORLD'] = 'lin2world';
$dbnm['SITE'] = 'lin2site';

// Usuarios de las bases de datos
$user['DB'] = 'tu_usuario_sql';
$user['WORLD'] = 'tu_usuario_sql';
$user['SITE'] = 'tu_usuario_sql';

// Contraseñas de las bases de datos
$pass['DB'] = 'tu_contraseña_sql';
$pass['WORLD'] = 'tu_contraseña_sql';
$pass['SITE'] = 'tu_contraseña_sql';

###########################################################
##              CONFIGURACIÓN DE DONACIONES              ##
###########################################################

// Nombre del coin de donación
$coinName = "Donate Coin";
$coinName_mini = "DC";

// Valor del coin para cálculos
$coinQntV = 1;

// Entrega automática de coins (1 = Sí, 0 = No)
$autoDelivery = 1;

// Email para notificaciones de donaciones
$donateEmail = 'admin@tu-dominio.com';

// Sistema de bonus (1 = Activado, 0 = Desactivado)
$bonusActived = 0;

// Configuración de bonus (si está activado)
$buyCoins['bonus_count'] = array(
    1 => 1000,   // A partir de 1000 coins
    2 => 5000,   // A partir de 5000 coins
    3 => 10000,  // A partir de 10000 coins
    4 => 20000,  // A partir de 20000 coins
    5 => 50000   // A partir de 50000 coins
);

$buyCoins['bonus_percent'] = array(
    1 => 5,   // 5% de bonus
    2 => 10,  // 10% de bonus
    3 => 15,  // 15% de bonus
    4 => 20,  // 20% de bonus
    5 => 25   // 25% de bonus
);

###########################################################
##                CONFIGURACIÓN DE PAYPAL                ##
###########################################################

$PayPal['actived'] = 0; // Activar PayPal (1 = Sí, 0 = No)
$PayPal['business_email'] = 'tu-email-paypal@example.com'; // Email de tu cuenta PayPal
$PayPal['USD']['coin_price'] = '0.033'; // Precio por coin en USD (3.30 USD = 100 coins)
$PayPal['BRL']['coin_price'] = '0.16'; // Precio por coin en BRL
$PayPal['EUR']['coin_price'] = '0.25'; // Precio por coin en EUR
$PayPal['testando'] = 1; // Modo sandbox (1 = Sí, 0 = No)

###########################################################
##             CONFIGURACIÓN DE MERCADOPAGO              ##
###########################################################

$MercadoPago['actived'] = 1; // Activar MercadoPago (1 = Sí, 0 = No)

// Credenciales de MercadoPago
// Obtener en: https://www.mercadopago.com/developers/panel/credentials
$MercadoPago['access_token'] = 'TU_ACCESS_TOKEN_AQUI'; // Access Token de producción o test
$MercadoPago['webhook_secret'] = 'TU_WEBHOOK_SECRET_AQUI'; // Secret para validar webhooks

// Credenciales legacy (solo si no tienes access_token)
$MercadoPago['client_id'] = ''; // CLIENT_ID (opcional)
$MercadoPago['client_secret'] = ''; // CLIENT_SECRET (opcional)

// Configuración de precios
$MercadoPago['currency'] = 'ARS'; // Código de moneda (ARS, USD, BRL, etc)
$MercadoPago['symbol'] = '$'; // Símbolo de la moneda
$MercadoPago['coin_price'] = '30.00'; // Precio por coin (30 ARS = 1 coin)

// Modo de prueba
$MercadoPago['testando'] = 1; // Sandbox (1 = Sí, 0 = No)

###########################################################
##              CONFIGURACIÓN DE UALABIS                 ##
###########################################################

$Ualabis['actived'] = 0; // Activar Ualabis (1 = Sí, 0 = No)
$Ualabis['user_name'] = 'tu_usuario_ualabis';
$Ualabis['client_id'] = 'TU_CLIENT_ID_UALABIS';
$Ualabis['secret'] = 'TU_SECRET_UALABIS';
$Ualabis['currency'] = 'ARS';
$Ualabis['symbol'] = '$';
$Ualabis['coin_price'] = '30.00';
$Ualabis['testando'] = 0;

###########################################################
##               CONFIGURACIÓN DE WEEAR                  ##
###########################################################

$Weear['actived'] = 0; // Activar Weear (1 = Sí, 0 = No)
$Weear['api_key'] = 'TU_API_KEY_WEEAR';
$Weear['currency'] = 'ARS';
$Weear['symbol'] = '$';
$Weear['coin_price'] = '30.00';
$Weear['testando'] = 0;

###########################################################
##              CONFIGURACIÓN DE PAGSEGURO               ##
###########################################################

$PagSeguro['actived'] = 0; // Activar PagSeguro (1 = Sí, 0 = No)
$PagSeguro['email'] = 'tu-email-pagseguro@example.com';
$PagSeguro['token'] = 'TU_TOKEN_PAGSEGURO';
$PagSeguro['coin_price'] = '0.50'; // Precio por coin en BRL
$PagSeguro['testando'] = 1;

###########################################################
##                FUNCIONALIDADES ACTIVAS                ##
###########################################################

$funct['donate'] = 1; // Sistema de donaciones
$funct['shop'] = 1;   // Tienda de items
$funct['stats'] = 1;  // Estadísticas del servidor
$funct['vote'] = 1;   // Sistema de votos

###########################################################
##                      SEGURIDAD                        ##
###########################################################

// Clave única para sesiones (CAMBIAR por una aleatoria)
$uniqueKey = 'CAMBIAR_ESTA_CLAVE_' . bin2hex(random_bytes(16));

// Idioma por defecto (es, en, pt)
$defaultLang = 'es';

###########################################################
##                   CONFIGURACIÓN SMTP                  ##
###########################################################

// Configuración para envío de emails
$smtp['host'] = 'smtp.tu-proveedor.com';
$smtp['port'] = 587;
$smtp['user'] = 'tu-email@tu-dominio.com';
$smtp['pass'] = 'tu_contraseña_email';
$smtp['from'] = 'noreply@tu-dominio.com';
$smtp['from_name'] = 'Tu Servidor L2';

?>
