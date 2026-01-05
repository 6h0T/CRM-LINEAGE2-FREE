GUIA PARA PASAR MERCADOPAGO A PRODUCCION
=========================================

ARCHIVO A MODIFICAR
-------------------

Archivo: ucp/private/configs.php

Buscar estas líneas y cambiar los valores:

   Línea 125 - access_token
   Valor actual (sandbox): APP_USR-397166240601337-121215-...
   Cambiar a: Tu Access Token de PRODUCCION

   Línea 126 - webhook_secret  
   Valor actual: (vacío)
   Cambiar a: La clave secreta del webhook de producción

   Línea 132 - testando
   Valor actual: 1
   Cambiar a: 0


Ejemplo de cómo quedaría:

   ANTES (Sandbox):
   $MercadoPago['access_token'] = 'APP_USR-397166240601337-121215-62458ba188bc52b9b399b8d3c5990ca8-3060740866';
   $MercadoPago['webhook_secret'] = '';
   $MercadoPago['testando'] = 1;

   DESPUES (Producción):
   $MercadoPago['access_token'] = 'APP_USR-TU_ACCESS_TOKEN_DE_PRODUCCION';
   $MercadoPago['webhook_secret'] = 'TU_WEBHOOK_SECRET_DE_PRODUCCION';
   $MercadoPago['testando'] = 0;


CONFIGURAR WEBHOOK EN MERCADOPAGO
---------------------------------

1. Ir a: https://www.mercadopago.com.ar/developers/panel/app/397166240601337/webhooks

2. Configurar:
   - URL de Producción: https://l2lagash.com.ar/panel/ucp/ipn/mercadopago_webhook.php
   - Eventos: Pagos (payment)

ESTADO: YA CONFIGURADO


ARCHIVO DEL WEBHOOK
-------------------

Archivo: ucp/ipn/mercadopago_webhook.php

Este archivo ya está listo para producción. No requiere cambios.


COMO OBTENER LAS CREDENCIALES DE PRODUCCION
-------------------------------------------

1. Ir a: https://www.mercadopago.com.ar/developers/panel/app
2. Seleccionar tu aplicación
3. Ir a "Credenciales de producción"
4. Copiar el Access Token (empieza con APP_USR-)
5. Ir a "Webhooks" y copiar la "Clave secreta"


CHECKLIST ANTES DE SUBIR
------------------------

[ ] Obtener Access Token de producción
[ ] Obtener Webhook Secret de producción
[ ] Actualizar ucp/private/configs.php:
    [ ] access_token con el token de producción
    [ ] webhook_secret con el secret de producción
    [ ] testando cambiarlo a 0
[ ] Configurar webhook en panel de MercadoPago con URL de producción
[ ] Subir archivos al servidor
[ ] Probar con un pago real de bajo monto


ARCHIVOS IMPORTANTES DEL PROYECTO
---------------------------------

ucp/private/configs.php              -> Configuración de credenciales
ucp/ipn/mercadopago_webhook.php      -> Recibe notificaciones de pago (NUEVO)
ucp/ipn/mercadopago_maxicroma.php    -> Webhook anterior (backup)
ucp/pages/donate/add.php             -> Formulario de donación
ucp/pages/donate/order_pay.php       -> Página de pago
ucp/engine/donate/create_order.php   -> Crea órdenes de donación
ucp/private/classes/classDonate.php  -> Lógica de donaciones


DONDE VER LOS LOGS
------------------

Los logs del webhook se guardan en:
ucp/ipn/logs/mercadopago_YYYY-MM.txt

Revisar estos logs si hay problemas con las notificaciones.


NOTAS IMPORTANTES
-----------------

1. NO compartir el Access Token ni el Webhook Secret públicamente
2. El archivo test_webhook.php es solo para pruebas, ELIMINAR en producción
3. Asegurarse de que la URL del webhook sea accesible desde internet (HTTPS)
4. MercadoPago reintenta las notificaciones si no recibe respuesta 200 OK


Documentación generada el 12/12/2024
