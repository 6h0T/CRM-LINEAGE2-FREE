# 🔒 Checklist de Seguridad para Producción

## ⚠️ IMPORTANTE: Antes de Subir a Producción

Este documento contiene una lista de verificación de seguridad que **DEBES** completar antes de poner el sistema en producción.

---

## 🔐 1. Limpiar Credenciales Sensibles

### Archivos que Contienen Credenciales

Los siguientes archivos contienen credenciales de prueba que **DEBEN** ser eliminadas o reemplazadas:

#### ❌ `donation_panel/ucp/private/configs.php`

**Credenciales a Limpiar:**

```php
// ❌ ELIMINAR ESTAS LÍNEAS
$MercadoPago['access_token'] = 'APP_USR-1801823751030446-121215-...';
$MercadoPago['webhook_secret'] = 'ac8e0ec5aff321ed8571eaab50d333a4...';
$MercadoPago['client_id'] = '983525923663232';
$MercadoPago['client_secret'] = 'RWEoNi7fCmseHQvWX6btyxE7Wh4mCJ11';

$Ualabis['client_id'] = 'z0HWShfQv0WCJFlWpb1j5eBC7lAva1Bw';
$Ualabis['secret'] = '8vOBI4rXmdo6rFGkcHWAIhro5nzXdkqZh-Vco-PLxtanYh8jA5OTB_H1lfQ97HDo';

$Weear['api_key'] = '4c0fba34-6029-4618-a688-9aaee4548046';

$PayPal['business_email'] = 'melisamedin993@gmail.com';
```

**✅ Reemplazar por:**

```php
$MercadoPago['access_token'] = 'TU_ACCESS_TOKEN_AQUI';
$MercadoPago['webhook_secret'] = 'TU_WEBHOOK_SECRET_AQUI';
$MercadoPago['client_id'] = '';
$MercadoPago['client_secret'] = '';

$Ualabis['client_id'] = 'TU_CLIENT_ID_AQUI';
$Ualabis['secret'] = 'TU_SECRET_AQUI';

$Weear['api_key'] = 'TU_API_KEY_AQUI';

$PayPal['business_email'] = 'tu-email@example.com';
```

---

## 📝 2. Usar Archivo de Ejemplo

### Pasos Recomendados:

1. **Renombrar el archivo actual:**
   ```bash
   mv donation_panel/ucp/private/configs.php donation_panel/ucp/private/configs.local.php
   ```

2. **Usar el archivo de ejemplo:**
   ```bash
   cp donation_panel/ucp/private/configs.example.php donation_panel/ucp/private/configs.php
   ```

3. **Configurar con tus credenciales reales**

4. **Agregar al .gitignore:**
   ```
   # En .gitignore
   donation_panel/ucp/private/configs.php
   donation_panel/ucp/private/configs.local.php
   ```

---

## 🗄️ 3. Credenciales de Base de Datos

### ❌ NO Commitear:

```php
$host['DB'] = '192.168.1.100'; // IP real del servidor
$user['DB'] = 'sa';
$pass['DB'] = 'MiPasswordReal123';
```

### ✅ Usar Variables de Entorno:

```php
$host['DB'] = getenv('DB_HOST') ?: 'localhost';
$user['DB'] = getenv('DB_USER') ?: 'usuario';
$pass['DB'] = getenv('DB_PASS') ?: 'password';
```

---

## 🔑 4. Cambiar Claves de Seguridad

### Clave Única de Sesión

**❌ NO usar:**
```php
$uniqueKey = 'CAMBIAR_ESTA_CLAVE_POR_UNA_ALEATORIA_';
```

**✅ Generar una nueva:**
```php
$uniqueKey = bin2hex(random_bytes(32));
// Resultado: algo como "a3f5b2c8d9e1f4a7b6c5d8e9f1a2b3c4d5e6f7a8b9c0d1e2f3a4b5c6d7e8f9a0"
```

### Generar Clave Segura

Ejecuta en PHP:
```php
<?php
echo bin2hex(random_bytes(32));
?>
```

O en terminal:
```bash
php -r "echo bin2hex(random_bytes(32));"
```

---

## 🌐 5. Configuración de URLs

### Desarrollo vs Producción

**❌ NO dejar en producción:**
```php
$server_url = "localhost";
$panel_url = "localhost/donation_panel/ucp";
```

**✅ Configurar correctamente:**
```php
$server_url = "tu-dominio.com";
$panel_url = "tu-dominio.com/donation_panel/ucp";
```

---

## 🔒 6. Permisos de Archivos

### Configurar Permisos Correctos

```bash
# Archivos PHP (lectura/escritura para owner, lectura para grupo)
find donation_panel/ -type f -name "*.php" -exec chmod 644 {} \;

# Directorios (ejecución necesaria)
find donation_panel/ -type d -exec chmod 755 {} \;

# Archivo de configuración (más restrictivo)
chmod 600 donation_panel/ucp/private/configs.php

# Directorios de logs (escritura necesaria)
chmod 755 donation_panel/ucp/ipn/logs/
chmod 755 donation_panel/ucp/cache/
```

---

## 🚫 7. Archivos a NO Commitear

### Crear/Actualizar .gitignore

```gitignore
# Configuración con credenciales
donation_panel/ucp/private/configs.php
donation_panel/ucp/private/configs.local.php

# Logs
donation_panel/ucp/ipn/logs/*.txt
donation_panel/ucp/error_log
donation_panel/ucp/debug_donation.txt

# Cache
donation_panel/ucp/cache/*

# Variables de entorno
.env
.env.local
.env.production

# Dependencias
node_modules/
vendor/

# Builds
dist/
build/

# IDE
.vscode/
.idea/
*.swp
*.swo
*~

# Sistema
.DS_Store
Thumbs.db
```

---

## 🔐 8. Modo de Prueba vs Producción

### MercadoPago

**❌ Modo Sandbox (Desarrollo):**
```php
$MercadoPago['testando'] = 1;
$MercadoPago['access_token'] = 'TEST-1234567890-...';
```

**✅ Modo Producción:**
```php
$MercadoPago['testando'] = 0;
$MercadoPago['access_token'] = 'APP_USR-1234567890-...';
```

### PayPal

**❌ Modo Sandbox:**
```php
$PayPal['testando'] = 1;
```

**✅ Modo Producción:**
```php
$PayPal['testando'] = 0;
```

---

## 🛡️ 9. Protección de Archivos Sensibles

### Crear .htaccess

En `donation_panel/ucp/private/.htaccess`:

```apache
# Denegar acceso a archivos de configuración
<Files "configs.php">
    Order Allow,Deny
    Deny from all
</Files>

<Files "configs.local.php">
    Order Allow,Deny
    Deny from all
</Files>

<Files "*.example.php">
    Order Allow,Deny
    Deny from all
</Files>
```

En `donation_panel/ucp/ipn/logs/.htaccess`:

```apache
# Denegar acceso a logs
<Files "*.txt">
    Order Allow,Deny
    Deny from all
</Files>
```

---

## 🔍 10. Verificación de Seguridad

### Checklist de Verificación

Antes de subir a producción, verifica:

- [ ] Todas las credenciales de prueba eliminadas
- [ ] Claves únicas generadas
- [ ] Modo sandbox desactivado
- [ ] URLs de producción configuradas
- [ ] Permisos de archivos correctos
- [ ] .gitignore actualizado
- [ ] .htaccess configurado
- [ ] HTTPS habilitado
- [ ] Webhooks apuntando a URLs de producción
- [ ] Logs protegidos
- [ ] Backups configurados

---

## 🧪 11. Pruebas de Seguridad

### Verificar que NO se pueda acceder a:

```
❌ https://tu-dominio.com/donation_panel/ucp/private/configs.php
❌ https://tu-dominio.com/donation_panel/ucp/ipn/logs/mercadopago_2026-01.txt
❌ https://tu-dominio.com/donation_panel/ucp/debug_donation.txt
```

Todos deben retornar **403 Forbidden** o **404 Not Found**.

### Verificar que SÍ se pueda acceder a:

```
✅ https://tu-dominio.com/donation_panel/ucp/
✅ https://tu-dominio.com/donation_panel/ucp/ipn/mercadopago_webhook.php (POST)
```

---

## 📋 12. Comandos Útiles

### Buscar Credenciales Hardcodeadas

```bash
# Buscar access tokens
grep -r "APP_USR-" donation_panel/

# Buscar emails
grep -r "@gmail.com" donation_panel/
grep -r "@hotmail.com" donation_panel/

# Buscar IPs
grep -r "192.168." donation_panel/

# Buscar contraseñas comunes
grep -ri "password.*=" donation_panel/
```

### Limpiar Logs Antiguos

```bash
# Eliminar logs de más de 30 días
find donation_panel/ucp/ipn/logs/ -name "*.txt" -mtime +30 -delete
```

---

## 🚨 13. En Caso de Exposición de Credenciales

Si accidentalmente expusiste credenciales:

1. **Revocar inmediatamente:**
   - MercadoPago: Regenerar Access Token
   - PayPal: Cambiar credenciales
   - Base de Datos: Cambiar contraseña

2. **Cambiar en el servidor:**
   - Actualizar `configs.php` con nuevas credenciales

3. **Verificar logs:**
   - Revisar si hubo accesos no autorizados
   - Verificar transacciones sospechosas

4. **Notificar:**
   - Informar al equipo
   - Documentar el incidente

---

## ✅ 14. Checklist Final

Antes de considerar el sistema seguro:

### Configuración
- [ ] `configs.php` sin credenciales de prueba
- [ ] `configs.example.php` creado y documentado
- [ ] Variables de entorno configuradas
- [ ] Claves únicas generadas

### Archivos
- [ ] `.gitignore` actualizado
- [ ] `.htaccess` configurado
- [ ] Permisos correctos aplicados
- [ ] Logs protegidos

### APIs
- [ ] MercadoPago en modo producción
- [ ] PayPal en modo producción
- [ ] Webhooks configurados correctamente
- [ ] URLs de callback actualizadas

### Base de Datos
- [ ] Credenciales seguras
- [ ] Backups configurados
- [ ] Conexión encriptada (si es posible)

### Servidor
- [ ] HTTPS habilitado
- [ ] Certificado SSL válido
- [ ] Firewall configurado
- [ ] PHP actualizado

### Pruebas
- [ ] Donación de prueba exitosa
- [ ] Webhook funcionando
- [ ] Acreditación automática funcionando
- [ ] Logs generándose correctamente

---

## 📞 Soporte

Si tienes dudas sobre seguridad:

1. Revisa `PRODUCTION_SETUP_GUIDE.md`
2. Consulta la documentación de cada API
3. Verifica los logs del sistema

---

**Última actualización**: Enero 2026  
**Versión**: 1.0.0

⚠️ **IMPORTANTE**: Este checklist es crítico para la seguridad del sistema. No omitas ningún paso.
