<?php 

#require_once('../private/configs.php'); 
$siteVinculed = 1;

###########################################################
##                  Configuraciones                      ##
###########################################################
$panel_url = 'https://l2server.com.ar/panel/ucp/'; // Escriba exactamente la URL donde se encuentra este panel (ejemplo: www.l2warcry.com.ar/ucp)
$itemDelivery = 1; // ¿Su servidor cuenta con el sistema Delivery? Si lo tiene, puede utilizarlo para la entrega de artículos. Si no tiene o prefiere no usarlo, usaremos el sistema de caché para entregar los elementos in-game. (1 = Usar entrega | 0 = Utilizar CacheD)
$themeColor = 'black'; // ¿Cuál es la tonalidad de color predominante en la template? (Elija: default, black, blue, red, green ou purple)
$defaultLang = 'ES'; // Idioma estándar del panel (Elija entre: PT, EN o ES) - El panel cuenta con un sistema inteligente que detecta el idioma del navegador del usuario y muestra todo en ese idioma, pero si no podemos detectar o si el navegador está en un idioma diferente tres citados anteriormente, el idioma establecido aquí será el mostrado
$gmt = '0'; // Si los scripts del panel están en una hora temprana o retrasada, cambie el GMT. Ejemplo: -1 (-1 hora), +3 (+3 horas), etc


###########################################################
##                 Control de funciones                  ##
###########################################################
// ¿Qué funciones están disponibles para los jugadores? (1 = Disponible | 0 = No disponible)
$funct['regist'] = 1; // Si se registra a través del panel
$funct['forgot'] = 1; // Recuperar cuenta a través del panel
$funct['donate'] = 1; // Hacer donaciones/adquirir monedas
$funct['trnsf1'] = 1; // Transferir monedas online a un personaje in-game - Posibilita convertir su saldo a monedas/ticket in-game          
$funct['trnsf2'] = 0; // Transferir monedas online a otra cuenta                                                                            
$funct['trnsf3'] = 0; // Transferir monedas de un personaje in-game a saldo online - Posibilita añadir saldo quitando monedas/ticket in-game
$funct['servic'] = 1; // Servicios (todos los servicios)                                                                                      
$funct['shopon'] = 0; // Shop    
$funct['gamst1'] = 1; // Game Stats - Top PvP
$funct['gamst2'] = 1; // Game Stats - Top PK
$funct['gamst3'] = 1; // Game Stats - Top Clan
$funct['gamst4'] = 0; // Game Stats - Top Online
$funct['gamst5'] = 0; // Game Stats - Grand Olympiad
$funct['gamst6'] = 0; // Game Stats - Boss Status
$funct['gamst7'] = 0; // Game Stats - Castle & Siege
$funct['gamst8'] = 0; // Game Stats - Top Level
$funct['gamst9'] = 0; // Game Stats - Top Adena
$funct['gams10'] = 0; // Game Stats - Boss Jewels Control
$funct['config'] = 1; // Configuración (cambiar datos de la cuenta)                                                                           


###########################################################
##                Registro y Recuperación                ##
###########################################################
// Puede insertar abajo links externos para que los jugadores puedan registrarse o recuperar sus cuentas en una página externa (si deja en blanco, las opciones desaparecer)
$link_regist = "http://opcional"; // Link de la página externa de registro
$link_forgot = "http://opcional"; // Link de la página externa de recuperar


###########################################################
##                       Servicios                       ##
###########################################################

# PK Counter Reset (zera cantidad de PKs)
$service['actv']['pkreset'] = 0; // ¿Está disponible? (1 = Sí | 0 = No)
$service['cost']['pkreset'] = 50; // ¿Cuál es el costo?

# Character Nickname (cambia nombre)
$service['actv']['changename'] = 0; // ¿Está disponible? (1 = Sí | 0 = No)
$service['cost']['changename'] = 100; // ¿Cuál es el costo?

# Clan Name (cambia el nombre del clan del personaje, si es líder)
$service['actv']['clanname'] = 0; // ¿Está disponible? (1 = Sí | 0 = No)
$service['cost']['clanname'] = 300; // ¿Cuál es el costo?

# Sex Change (cambio de género/sexo)
$service['actv']['sexchange'] = 0; // ¿Está disponible? (1 = Sí | 0 = No)
$service['cost']['sexchange'] = 200; // ¿Cuál es el costo?


###########################################################
##                 Adquisición de Saldo                  ##
###########################################################
$coinName = 'Fx Coin'; // Nombre de la moneda online que representa el saldo (utilizado sólo en el panel de usuario)
$coinName_mini = 'Coin'; // Nombre resumido de la moneda
$coinQntV = 1; // ¿Cuál es la cantidad comercializada? Usted definirá el valor de esa cantidad justo debajo. (por ejemplo, si se define 10 aquí y en las configuraciones de los "Módulos de donación" abajo definimos 1.00 como valor, el usuario podrá adquirir 10 por $ 1,00, 20 por $ 2,00, etc)

// Bonos en porcentaje al adquirir moneda online en altas cantidades (Ejemplo: cada 100 monedas compradas, gana el 10%, es decir, paga por las 100, pero recibe 110)
$bonusActived = 1; // ¿Desea habilitar la bonificación por compra en cantidad? (1 = Sí | 0 = No)

// Usted puede insertar hasta 3 bonificaciones! Si no quiere usar alguna, basta con fijar los valores como '0' que será desconsiderada.

// bonificación 1:
$buyCoins['bonus_count'][1] = '300'; // ¿A partir de qué cantidad se da lo bonos abajo?
$buyCoins['bonus_percent'][1] = '0'; // ¿Cuál es el porcentaje de bonificación?

// bonificación 2:
$buyCoins['bonus_count'][2] = '600'; // ¿A partir de qué cantidad se da lo bonos abajo?
$buyCoins['bonus_percent'][2] = '0'; // ¿Cuál es el porcentaje de bonificación?

// bonificación 3:
$buyCoins['bonus_count'][3] = '1000'; // ¿A partir de qué cantidad se da lo bonos abajo?
$buyCoins['bonus_percent'][3] = '0'; // ¿Cuál es el porcentaje de bonificación?

// Exclusión de factura
$delFatura = 0; // El usuario puede eliminar una factura? (1 = Sí | 0 = No) - OBS: Una factura nunca se elimina, se oculta, pero siempre permanecerá en la base de datos.



###########################################################
##        Transferencia por coin / ticket in-game        ##
###########################################################
// Si está habilitada la función "Transferir monedas online a un personaje in-game", el jugador podrá convertir su saldo online a monedas in-game! Necesitamos definir algunas informaciones...
$coinGame = 'Donation Coin'; // Nombre de la moneda donate in-game (generalmente Coin, Ticket o Gold)
$coinID = 8533; // ID de la moneda


###########################################################
##                  Módulos de donación                  ##
###########################################################


$autoDelivery = 1; // ¿Desea que la entrega del saldo se haga de forma automática? (1 = Sí | 0 = No) (si opta de forma manual, las donaciones pagadas quedarán con status "Paga". Usted tendrá que ir hasta el panel admin y completarlas haciendo clic en el botón "Entregar". se añadirá y el status pasará a ser "Entregado")
$donateEmail = 'https://www.facebook.com/'; // Correo electrónico que recibirá los comprobantes de pago para las transacciones bancarias y los módulos de confirmación manual

// PAYPAL CONFIGS:
$PayPal['actived'] = 0; // Opción activa? (1 = Sí / 0 = No)
$PayPal['business_email'] = 'melisamedin993@gmail.com'; // E-mail de la cuenta que recibirá las donaciones
$PayPal['USD']['coin_price'] = '0.033'; // Valor de la cantidad comercializada (en Dolar)
$PayPal['BRL']['coin_price'] = '0.16'; // Valor de la cantidad comercializada (en Reais)
$PayPal['EUR']['coin_price'] = '0.25'; // Valor de la cantidad comercializada (en Euros)
$PayPal['testando'] = 1; // ¿Está probando el sistema a través de PayPal Sandbox? (1 = Sí | 0 = No)

// MERCADOPAGO CONFIGS (Actualizado 2024):
$MercadoPago['actived'] = 1; // Opción activa? (1 = Sí / 0 = No)
$MercadoPago['access_token'] = 'APP_USR-1801823751030446-121215-70785237078f18c64af1e09ce19535bc-270974256'; // ACCESS TOKEN de producción
$MercadoPago['webhook_secret'] = 'ac8e0ec5aff321ed8571eaab50d333a43d052a185a23da85fa8b162517082d0a'; // Clave secreta para validar webhooks
$MercadoPago['client_id'] = '983525923663232'; // (LEGACY) CLIENT_ID - Solo si no tienes access_token
$MercadoPago['client_secret'] = 'RWEoNi7fCmseHQvWX6btyxE7Wh4mCJ11'; // (LEGACY) CLIENT_SECRET - Solo si no tienes access_token
$MercadoPago['currency'] = 'ARS'; // Código de la moneda
$MercadoPago['symbol'] = '$'; // Symbol moneda
$MercadoPago['coin_price'] = '1.00'; // Valor de la cantidad comercializada
$MercadoPago['testando'] = 0; // ¿Está probando el sistema a través de MercadoPago Sandbox? (1 = Sí | 0 = No)

// Ualabis CONFIGS:
$Ualabis['actived'] = 0; // Opción activa? (1 = Sí / 0 = No)
$Ualabis['user_name'] = 'l2server.arg';
$Ualabis['client_id'] = 'z0HWShfQv0WCJFlWpb1j5eBC7lAva1Bw';
$Ualabis['secret'] = '8vOBI4rXmdo6rFGkcHWAIhro5nzXdkqZh-Vco-PLxtanYh8jA5OTB_H1lfQ97HDo';
$Ualabis['currency'] = 'ARS'; // Código de la moneda
$Ualabis['symbol'] = '$'; // Symbol moneda
$Ualabis['coin_price'] = '30.00'; // Valor de la cantidad comercializada (en Reais)
$Ualabis['testando'] = 0; // ¿Está probando el sistema a través de Sandbox? (1 = Sí | 0 = No)

// Weear CONFIGS:
$Weear['actived'] = 0; // Opción activa? (1 = Sí / 0 = No)
$Weear['api_key'] = '4c0fba34-6029-4618-a688-9aaee4548046';
$Weear['currency'] = 'ARS'; // Código de la moneda
$Weear['symbol'] = '$'; // Symbol moneda
$Weear['coin_price'] = '5.00'; // Valor de la cantidad comercializada (en Reais)
$Weear['testando'] = 0; // ¿Está probando el sistema a través de Sandbox? (1 = Sí | 0 = No)

// TRANSACCION BANCARIA:
$Banking['actived'] = 0; // Opción activa? (1 = Sí / 0 = No)
$Banking['currency'] = 'ARS'; // Código de la moneda
$Banking['coin_price'] = '30.00'; // Valor de la cantidad comercializada
$Banking['bank_dados'] = '
<b>DONACIONES</b><br />
TRANSFERENCIA BANCARIA <br />
Válido únicamente para moneda Argentina (ARS). <br />
Consulta tus dudas por los canales de contacto. <br />
<b>Datos Importantes para Envío</b> <br />
<b>CVU:</b> 0000013000032230291923 <br />
<b>Alias:</b> l2server <br />
<span style="color: #007bff; font-weight: bold;">
IMPORTANTE: Para la correspondiente entrega de la donación, adjuntar comprobante y usuario en el Messenger de la fanpage de L2Server.
</span><br />';


###########################################################
##                    Vote Reward                        ##
###########################################################
$funct['vote'] = 1; // ¿Está disponible el sistema de votación? (1 = Sí | 0 = No)
$voteReward = 1; // Cantidad de coins que se otorgan por cada voto válido
$voteCooldown = 12; // Horas de espera entre votos en el mismo topsite
$voteDeliveryMethod = 'balance'; // Método de entrega: 'balance' = saldo online | 'ingame' = directo al personaje via ItemDelivery

// Configuración de Topsites (agregue o quite según necesite)
// Cada topsite necesita: actived, name, url, api_key, api_url
$topsites = array();

// Topsite 1 - L2TopZone
$topsites[1]['actived'] = 1; // ¿Activo? (1 = Sí | 0 = No)
$topsites[1]['name'] = 'L2TopZone';
$topsites[1]['image'] = 'l2topzone.png'; // Imagen en /imgs/topsites/
$topsites[1]['vote_url'] = 'https://l2topzone.com/vote/YOUR_SERVER_ID'; // URL donde el usuario vota
$topsites[1]['api_key'] = 'YOUR_API_KEY'; // API Key del topsite
$topsites[1]['api_url'] = 'https://api.l2topzone.com/v1/vote?token=YOUR_API_KEY&ip='; // URL de verificación (se agrega IP al final)

// Topsite 2 - Hopzone
$topsites[2]['actived'] = 1;
$topsites[2]['name'] = 'Hopzone';
$topsites[2]['image'] = 'hopzone.png';
$topsites[2]['vote_url'] = 'https://hopzone.net/vote/YOUR_SERVER_ID';
$topsites[2]['api_key'] = 'YOUR_API_KEY';
$topsites[2]['api_url'] = 'https://api.hopzone.net/v1/vote?token=YOUR_API_KEY&ip=';

// Topsite 3 - L2Network
$topsites[3]['actived'] = 0;
$topsites[3]['name'] = 'L2Network';
$topsites[3]['image'] = 'l2network.png';
$topsites[3]['vote_url'] = 'https://l2network.eu/vote/YOUR_SERVER_ID';
$topsites[3]['api_key'] = 'YOUR_API_KEY';
$topsites[3]['api_url'] = 'https://l2network.eu/api/vote?apiKey=YOUR_API_KEY&ip=';

// Topsite 4 - L2Servers
$topsites[4]['actived'] = 0;
$topsites[4]['name'] = 'L2Servers';
$topsites[4]['image'] = 'l2servers.png';
$topsites[4]['vote_url'] = 'https://l2servers.com/vote/YOUR_SERVER_ID';
$topsites[4]['api_key'] = 'YOUR_API_KEY';
$topsites[4]['api_url'] = 'https://l2servers.com/api/vote?key=YOUR_API_KEY&ip=';
