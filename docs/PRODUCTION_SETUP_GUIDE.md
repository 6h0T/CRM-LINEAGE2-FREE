# 🚀 Guía de Configuración para Producción

## 📋 Índice

1. [Requisitos Previos](#requisitos-previos)
2. [Configuración de Base de Datos](#configuración-de-base-de-datos)
3. [Comandos SQL Necesarios](#comandos-sql-necesarios)
4. [Configuración del Backend PHP](#configuración-del-backend-php)
5. [Configuración de APIs de Pago](#configuración-de-apis-de-pago)
6. [Configuración del Frontend React](#configuración-del-frontend-react)
7. [Verificación del Sistema](#verificación-del-sistema)
8. [Seguridad y Buenas Prácticas](#seguridad-y-buenas-prácticas)

---

## 📦 Requisitos Previos

### Software Necesario

- **Servidor Web**: Apache 2.4+ o Nginx
- **PHP**: 7.4+ (recomendado 8.0+)
- **Base de Datos**: SQL Server 2012+
- **Node.js**: 18+ (para compilar el frontend)
- **Extensiones PHP requeridas**:
  - `php_sqlsrv` o `php_mssql`
  - `php_curl`
  - `php_json`
  - `php_mbstring`

### Verificar Extensiones PHP

```bash
php -m | grep -E "sqlsrv|mssql|curl|json|mbstring"
```

---

## 🗄️ Configuración de Base de Datos

### 1. Conexión a SQL Server

Necesitarás los siguientes datos de conexión:

```
Host: tu-servidor-sql.com (o IP)
Puerto: 1433 (por defecto)
Usuario: tu_usuario_sql
Contraseña: tu_contraseña_sql
```

### 2. Bases de Datos Requeridas

El sistema utiliza 3 bases de datos:

| Base de Datos | Propósito |
|---------------|-----------|
| `lin2db` | Base de datos principal del juego |
| `lin2world` | Datos de personajes e items |
| `lin2site` | Datos del sitio web y donaciones |

---

## 💾 Comandos SQL Necesarios

### 1. Crear Tabla de Donaciones (si no existe)

Ejecuta en la base de datos `lin2site`:

```sql
USE lin2site;
GO

-- Crear tabla de donaciones
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='site_donations' AND xtype='U')
BEGIN
    CREATE TABLE site_donations (
        id INT IDENTITY(1,1) PRIMARY KEY,
        account VARCHAR(50) NOT NULL,
        personagem INT NOT NULL DEFAULT 0,
        price DECIMAL(10,2) NOT NULL,
        currency VARCHAR(3) NOT NULL DEFAULT 'USD',
        metodo_pgto VARCHAR(50) NOT NULL,
        quant_coins INT NOT NULL,
        coins_bonus INT NOT NULL DEFAULT 0,
        coins_entregues INT NOT NULL DEFAULT 0,
        valor DECIMAL(10,2) NOT NULL,
        data BIGINT NOT NULL,
        protocolo VARCHAR(50) UNIQUE,
        transaction_code VARCHAR(100),
        status INT NOT NULL DEFAULT 0,
        status_real VARCHAR(50),
        ultima_alteracao BIGINT,
        auto_credit INT DEFAULT 0,
        CONSTRAINT UQ_protocolo UNIQUE (protocolo)
    );
    
    PRINT 'Tabla site_donations creada exitosamente';
END
ELSE
BEGIN
    PRINT 'La tabla site_donations ya existe';
END
GO
```

### 2. Agregar Campo auto_credit (si la tabla ya existe)

```sql
USE lin2site;
GO

-- Agregar campo auto_credit si no existe
IF NOT EXISTS (
    SELECT * FROM sys.columns 
    WHERE object_id = OBJECT_ID('site_donations') 
    AND name = 'auto_credit'
)
BEGIN
    ALTER TABLE site_donations
    ADD auto_credit INT DEFAULT 0;
    
    PRINT 'Campo auto_credit agregado exitosamente';
END
ELSE
BEGIN
    PRINT 'El campo auto_credit ya existe';
END
GO
```

### 3. Crear Tabla de Balance (sistema tradicional)

```sql
USE lin2site;
GO

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='site_balance' AND xtype='U')
BEGIN
    CREATE TABLE site_balance (
        id INT IDENTITY(1,1) PRIMARY KEY,
        account VARCHAR(50) NOT NULL UNIQUE,
        saldo INT NOT NULL DEFAULT 0
    );
    
    PRINT 'Tabla site_balance creada exitosamente';
END
GO
```

### 4. Crear Tabla de Log de Conversiones

```sql
USE lin2site;
GO

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='site_log_convertcoins' AND xtype='U')
BEGIN
    CREATE TABLE site_log_convertcoins (
        id INT IDENTITY(1,1) PRIMARY KEY,
        quant_coins INT NOT NULL,
        account VARCHAR(50) NOT NULL,
        destinatario INT NOT NULL,
        cdata DATETIME NOT NULL DEFAULT GETDATE()
    );
    
    PRINT 'Tabla site_log_convertcoins creada exitosamente';
END
GO
```

### 5. Crear Índices para Optimización

```sql
USE lin2site;
GO

-- Índices para site_donations
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_site_donations_account')
BEGIN
    CREATE INDEX IX_site_donations_account ON site_donations(account);
    PRINT 'Índice IX_site_donations_account creado';
END

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_site_donations_status')
BEGIN
    CREATE INDEX IX_site_donations_status ON site_donations(status);
    PRINT 'Índice IX_site_donations_status creado';
END

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_site_donations_protocolo')
BEGIN
    CREATE INDEX IX_site_donations_protocolo ON site_donations(protocolo);
    PRINT 'Índice IX_site_donations_protocolo creado';
END
GO
```

### 6. Verificar Estructura de Tablas

```sql
-- Verificar site_donations
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    CHARACTER_MAXIMUM_LENGTH,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'site_donations'
ORDER BY ORDINAL_POSITION;

-- Verificar site_balance
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'site_balance'
ORDER BY ORDINAL_POSITION;
```

---

## ⚙️ Configuración del Backend PHP

### 1. Archivo de Configuración Principal

Edita `donation_panel/ucp/private/configs.php`:

```php
<?php

// ============================================
// CONFIGURACIÓN DEL SERVIDOR
// ============================================

$server_name = "Tu Servidor L2";
$server_chronicle = "Interlude";
$server_url = "tu-dominio.com"; // SIN http:// o https://
$panel_url = "tu-dominio.com/donation_panel/ucp"; // SIN http:// o https://

// ============================================
// CONFIGURACIÓN DE BASE DE DATOS
// ============================================

// Método de conexión: 1 = mssql_connect, 2 = sqlsrv_connect
$conMethod = 2; // Usar 2 para versiones modernas de PHP

// Host y Puerto
$host['DB'] = 'tu-servidor-sql.com'; // o IP del servidor SQL
$host['WORLD'] = 'tu-servidor-sql.com';
$host['SITE'] = 'tu-servidor-sql.com';

$port['DB'] = '1433';
$port['WORLD'] = '1433';
$port['SITE'] = '1433';

// Nombres de las bases de datos
$dbnm['DB'] = 'lin2db';
$dbnm['WORLD'] = 'lin2world';
$dbnm['SITE'] = 'lin2site';

// Credenciales de acceso
$user['DB'] = 'tu_usuario_sql';
$user['WORLD'] = 'tu_usuario_sql';
$user['SITE'] = 'tu_usuario_sql';

$pass['DB'] = 'tu_contraseña_sql';
$pass['WORLD'] = 'tu_contraseña_sql';
$pass['SITE'] = 'tu_contraseña_sql';

// ============================================
// CONFIGURACIÓN DE DONACIONES
// ============================================

// Nombre del coin de donación
$coinName = "Donate Coin";
$coinName_mini = "DC";

// Valor del coin (para cálculos)
$coinQntV = 1;

// Activar entrega automática de coins
$autoDelivery = 1; // 1 = Activado, 0 = Desactivado

// Activar sistema de bonus
$bonusActived = 0; // 1 = Activado, 0 = Desactivado

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

// ============================================
// FUNCIONALIDADES ACTIVAS
// ============================================

$funct['donate'] = 1; // Sistema de donaciones
$funct['shop'] = 1;   // Tienda
$funct['stats'] = 1;  // Estadísticas
$funct['vote'] = 1;   // Sistema de votos

// ============================================
// SEGURIDAD
// ============================================

// Clave única para sesiones (cambiar por una aleatoria)
$uniqueKey = 'CAMBIAR_ESTA_CLAVE_POR_UNA_ALEATORIA_' . md5(uniqid());

// Idioma por defecto
$defaultLang = 'es'; // es, en, pt

?>
```

### 2. Configuración de MercadoPago

En el mismo archivo `configs.php`, agrega:

```php
// ============================================
// MERCADOPAGO
// ============================================

$MercadoPago['actived'] = 1; // 1 = Activado, 0 = Desactivado

// Credenciales de MercadoPago
// Obtener en: https://www.mercadopago.com/developers/panel/credentials
$MercadoPago['access_token'] = 'TU_ACCESS_TOKEN_AQUI';
$MercadoPago['webhook_secret'] = 'TU_WEBHOOK_SECRET_AQUI';

// Modo de prueba (Sandbox)
$MercadoPago['testando'] = 1; // 1 = Sandbox, 0 = Producción

// Configuración de precios
$MercadoPago['coin_price'] = 30; // 30 ARS = 1 coin
$MercadoPago['currency'] = 'ARS';
$MercadoPago['symbol'] = '$';

// Client ID y Secret (opcional, para OAuth)
$MercadoPago['client_id'] = '';
$MercadoPago['client_secret'] = '';
```

### 3. Configuración de PayPal (opcional)

```php
// ============================================
// PAYPAL
// ============================================

$PayPal['actived'] = 1;

// Credenciales de PayPal
$PayPal['business'] = 'tu-email-paypal@example.com';
$PayPal['testando'] = 1; // 1 = Sandbox, 0 = Producción

// Precios por moneda
$PayPal['USD']['coin_price'] = 0.033; // 3.30 USD = 100 coins
$PayPal['BRL']['coin_price'] = 0.15;
$PayPal['EUR']['coin_price'] = 0.03;
```

### 4. Configuración del Item ID de Donate Coin

Edita `donation_panel/ucp/ipn/mercadopago_webhook.php` en la línea 377:

```php
// ID del item Donate Coin en tu servidor
$donateItemId = 4037; // CAMBIAR por el ID correcto de tu servidor
```

**Para encontrar el ID correcto:**

```sql
USE lin2world;
GO

SELECT item_id, name, name_en 
FROM etcitem 
WHERE name LIKE '%donate%' OR name LIKE '%coin%' OR name_en LIKE '%donate%';
```

---

## 🔐 Configuración de APIs de Pago

### MercadoPago

#### 1. Obtener Credenciales

1. Ir a https://www.mercadopago.com/developers/panel/credentials
2. Crear una aplicación
3. Copiar:
   - **Access Token** (Production o Test)
   - **Webhook Secret** (en la sección de Webhooks)

#### 2. Configurar Webhook

1. Ir a https://www.mercadopago.com/developers/panel/webhooks
2. Agregar nueva URL:
   ```
   https://tu-dominio.com/donation_panel/ucp/ipn/mercadopago_webhook.php
   ```
3. Seleccionar eventos:
   - ✅ `payment` (Pagos)
4. Guardar

#### 3. Modo Sandbox vs Producción

**Sandbox (Pruebas)**:
```php
$MercadoPago['testando'] = 1;
$MercadoPago['access_token'] = 'TEST-1234567890-...';
```

**Producción**:
```php
$MercadoPago['testando'] = 0;
$MercadoPago['access_token'] = 'APP_USR-1234567890-...';
```

#### 4. Tarjetas de Prueba (Sandbox)

| Tarjeta | Resultado |
|---------|-----------|
| 5031 7557 3453 0604 | Aprobada |
| 5031 4332 1540 6351 | Rechazada |

---

## 🎨 Configuración del Frontend React

### 1. Variables de Entorno

Crea un archivo `.env` en la raíz del proyecto:

```env
# API Keys
VITE_GEMINI_API_KEY=tu_api_key_aqui

# URLs
VITE_API_URL=https://tu-dominio.com
VITE_DONATION_PANEL_URL=https://tu-dominio.com/donation_panel/ucp
```

### 2. Configuración de Vite

El archivo `vite.config.ts` ya está configurado con el proxy:

```typescript
server: {
  port: 3000,
  host: '0.0.0.0',
  proxy: {
    '/donation_panel': {
      target: 'http://localhost', // Cambiar en producción
      changeOrigin: true,
      rewrite: (path) => path
    }
  }
}
```

**Para producción**, este proxy no es necesario ya que el frontend estará compilado y servido desde el mismo servidor.

### 3. Compilar para Producción

```bash
# Instalar dependencias
npm install

# Compilar
npm run build

# Los archivos compilados estarán en la carpeta 'dist/'
```

### 4. Configurar Tasas de Conversión

Edita `components/DonationPanel.tsx` líneas 19-22:

```typescript
const conversionRates: { [key: string]: { rate: number, currency: string, symbol: string } } = {
  MercadoPago: { rate: 30, currency: 'ARS', symbol: '$' },  // 30 ARS = 1 coin
  PayPal_USD: { rate: 0.033, currency: 'USD', symbol: '$' }, // 3.30 USD = 100 coins
  PagSeguro: { rate: 0.5, currency: 'BRL', symbol: 'R$' }
};
```

**IMPORTANTE**: Estas tasas deben coincidir con las configuradas en `configs.php`.

---

## ✅ Verificación del Sistema

### 1. Verificar Conexión a Base de Datos

Crea un archivo `test_db.php` en `donation_panel/ucp/`:

```php
<?php
require('private/configs.php');
require('private/classes/DB.php');

try {
    new DB($conMethod, $host, $user, $pass, $dbnm, $port);
    echo "✅ Conexión exitosa a las bases de datos<br>";
    
    // Probar consulta
    $test = DB::Executa("SELECT TOP 1 * FROM site_donations", "SITE");
    echo "✅ Tabla site_donations accesible<br>";
    echo "Registros encontrados: " . count($test) . "<br>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
```

Accede a: `http://tu-dominio.com/donation_panel/ucp/test_db.php`

### 2. Verificar Tablas Necesarias

```sql
-- Verificar que todas las tablas existan
USE lin2site;
GO

SELECT 
    TABLE_NAME,
    (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = t.TABLE_NAME) as COLUMN_COUNT
FROM INFORMATION_SCHEMA.TABLES t
WHERE TABLE_NAME IN ('site_donations', 'site_balance', 'site_log_convertcoins')
ORDER BY TABLE_NAME;
```

### 3. Verificar Permisos de Escritura

Verifica que PHP pueda escribir en:
- `donation_panel/ucp/ipn/logs/`
- `donation_panel/ucp/cache/`

```bash
chmod 755 donation_panel/ucp/ipn/logs/
chmod 755 donation_panel/ucp/cache/
```

### 4. Probar Flujo Completo

1. **Crear una donación de prueba**:
   - Ir a la página de donaciones
   - Ingresar un personaje existente
   - Seleccionar 100 coins
   - Método: MercadoPago (Sandbox)

2. **Verificar en la BD**:
   ```sql
   SELECT TOP 5 * FROM site_donations ORDER BY data DESC;
   ```

3. **Completar el pago** con tarjeta de prueba

4. **Verificar acreditación**:
   ```sql
   -- Ver si los coins se acreditaron
   SELECT * FROM items 
   WHERE owner_id = (SELECT char_id FROM user_data WHERE char_name = 'TuPersonaje')
   AND item_id = 4037;
   ```

---

## 🔒 Seguridad y Buenas Prácticas

### 1. Proteger Archivos de Configuración

```apache
# .htaccess en donation_panel/ucp/private/
<Files "configs.php">
    Order Allow,Deny
    Deny from all
</Files>
```

### 2. Cambiar Clave Única

En `configs.php`, genera una clave única:

```php
$uniqueKey = bin2hex(random_bytes(32));
```

### 3. Habilitar HTTPS

**IMPORTANTE**: En producción, SIEMPRE usa HTTPS.

```apache
# Redirigir HTTP a HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 4. Configurar Permisos de Archivos

```bash
# Archivos PHP
find donation_panel/ -type f -name "*.php" -exec chmod 644 {} \;

# Directorios
find donation_panel/ -type d -exec chmod 755 {} \;

# Archivos de configuración (más restrictivos)
chmod 600 donation_panel/ucp/private/configs.php
```

### 5. Logs de Seguridad

Monitorea regularmente:
- `donation_panel/ucp/ipn/logs/mercadopago_*.txt`
- `donation_panel/ucp/error_log`

### 6. Backup de Base de Datos

Configura backups automáticos:

```sql
-- Script de backup
BACKUP DATABASE lin2site 
TO DISK = 'C:\Backups\lin2site_backup.bak'
WITH FORMAT, COMPRESSION;
```

### 7. Validación de Webhooks

El sistema ya valida:
- ✅ Firma de MercadoPago (x-signature)
- ✅ Monto pagado vs esperado
- ✅ Moneda correcta
- ✅ Prevención de duplicados

---

## 📝 Checklist de Producción

Antes de poner en producción, verifica:

### Base de Datos
- [ ] Todas las tablas creadas
- [ ] Campo `auto_credit` agregado
- [ ] Índices creados
- [ ] Conexión funcionando
- [ ] Backups configurados

### Backend PHP
- [ ] `configs.php` configurado con datos reales
- [ ] Credenciales de BD correctas
- [ ] MercadoPago en modo producción
- [ ] Webhook URL configurada
- [ ] Item ID del Donate Coin correcto
- [ ] `autoDelivery = 1`
- [ ] Permisos de archivos correctos

### Frontend React
- [ ] Compilado para producción (`npm run build`)
- [ ] Tasas de conversión correctas
- [ ] Variables de entorno configuradas

### Seguridad
- [ ] HTTPS habilitado
- [ ] Archivos de configuración protegidos
- [ ] Clave única generada
- [ ] Permisos de archivos correctos
- [ ] Logs monitoreados

### Pruebas
- [ ] Conexión a BD funciona
- [ ] Donación de prueba exitosa
- [ ] Webhook recibe notificaciones
- [ ] Coins se acreditan correctamente
- [ ] Logs se generan correctamente

---

## 🆘 Solución de Problemas

### Error: "No se puede conectar a la base de datos"

**Solución**:
1. Verificar credenciales en `configs.php`
2. Verificar que SQL Server esté corriendo
3. Verificar firewall (puerto 1433)
4. Verificar extensión PHP (`php_sqlsrv`)

### Error: "Webhook no recibe notificaciones"

**Solución**:
1. Verificar URL del webhook en MercadoPago
2. Verificar que la URL sea accesible públicamente
3. Revisar logs: `donation_panel/ucp/ipn/logs/`
4. Verificar que `autoDelivery = 1`

### Error: "Los coins no se acreditan"

**Solución**:
1. Verificar que `auto_credit = 1` en la donación
2. Verificar Item ID del Donate Coin
3. Revisar logs del webhook
4. Verificar que el personaje exista

---

## 📞 Soporte

Para más ayuda, revisa:
- `DONATION_DIRECT_SYSTEM.md` - Documentación técnica completa
- `TEST_DONATION.md` - Guía de pruebas
- Logs en `donation_panel/ucp/ipn/logs/`

---

## 📄 Archivos de Configuración Importantes

```
donation_panel/
├── ucp/
│   ├── private/
│   │   └── configs.php          ⚙️ Configuración principal
│   ├── ipn/
│   │   ├── mercadopago_webhook.php  🔔 Webhook de MercadoPago
│   │   └── logs/                📝 Logs de webhooks
│   └── engine/
│       └── donate/
│           └── create_direct_order.php  🎯 Endpoint de donaciones
│
components/
└── DonationPanel.tsx            🎨 Formulario de donaciones

vite.config.ts                   ⚙️ Configuración de Vite
.env                             🔐 Variables de entorno
```

---

**Última actualización**: Enero 2026  
**Versión del sistema**: 1.0.0
