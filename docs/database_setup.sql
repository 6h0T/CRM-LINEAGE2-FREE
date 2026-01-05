-- ============================================
-- SCRIPT DE CONFIGURACIÓN DE BASE DE DATOS
-- Sistema de Donaciones Directas
-- ============================================

USE lin2site;
GO

PRINT '=== INICIANDO CONFIGURACIÓN DE BASE DE DATOS ===';
PRINT '';

-- ============================================
-- 1. CREAR TABLA DE DONACIONES
-- ============================================

PRINT '1. Verificando tabla site_donations...';

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='site_donations' AND xtype='U')
BEGIN
    PRINT '   Creando tabla site_donations...';
    
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
        protocolo VARCHAR(50),
        transaction_code VARCHAR(100),
        status INT NOT NULL DEFAULT 0,
        status_real VARCHAR(50),
        ultima_alteracao BIGINT,
        auto_credit INT DEFAULT 0,
        CONSTRAINT UQ_protocolo UNIQUE (protocolo)
    );
    
    PRINT '   ✓ Tabla site_donations creada exitosamente';
END
ELSE
BEGIN
    PRINT '   ✓ Tabla site_donations ya existe';
END
GO

-- ============================================
-- 2. AGREGAR CAMPO auto_credit (si no existe)
-- ============================================

PRINT '';
PRINT '2. Verificando campo auto_credit...';

IF NOT EXISTS (
    SELECT * FROM sys.columns 
    WHERE object_id = OBJECT_ID('site_donations') 
    AND name = 'auto_credit'
)
BEGIN
    PRINT '   Agregando campo auto_credit...';
    
    ALTER TABLE site_donations
    ADD auto_credit INT DEFAULT 0;
    
    PRINT '   ✓ Campo auto_credit agregado exitosamente';
END
ELSE
BEGIN
    PRINT '   ✓ Campo auto_credit ya existe';
END
GO

-- ============================================
-- 3. CREAR TABLA DE BALANCE
-- ============================================

PRINT '';
PRINT '3. Verificando tabla site_balance...';

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='site_balance' AND xtype='U')
BEGIN
    PRINT '   Creando tabla site_balance...';
    
    CREATE TABLE site_balance (
        id INT IDENTITY(1,1) PRIMARY KEY,
        account VARCHAR(50) NOT NULL UNIQUE,
        saldo INT NOT NULL DEFAULT 0
    );
    
    PRINT '   ✓ Tabla site_balance creada exitosamente';
END
ELSE
BEGIN
    PRINT '   ✓ Tabla site_balance ya existe';
END
GO

-- ============================================
-- 4. CREAR TABLA DE LOG DE CONVERSIONES
-- ============================================

PRINT '';
PRINT '4. Verificando tabla site_log_convertcoins...';

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='site_log_convertcoins' AND xtype='U')
BEGIN
    PRINT '   Creando tabla site_log_convertcoins...';
    
    CREATE TABLE site_log_convertcoins (
        id INT IDENTITY(1,1) PRIMARY KEY,
        quant_coins INT NOT NULL,
        account VARCHAR(50) NOT NULL,
        destinatario INT NOT NULL,
        cdata DATETIME NOT NULL DEFAULT GETDATE()
    );
    
    PRINT '   ✓ Tabla site_log_convertcoins creada exitosamente';
END
ELSE
BEGIN
    PRINT '   ✓ Tabla site_log_convertcoins ya existe';
END
GO

-- ============================================
-- 5. CREAR TABLA DE LOG DE TRANSFERENCIAS
-- ============================================

PRINT '';
PRINT '5. Verificando tabla site_log_transfercoins...';

IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='site_log_transfercoins' AND xtype='U')
BEGIN
    PRINT '   Creando tabla site_log_transfercoins...';
    
    CREATE TABLE site_log_transfercoins (
        id INT IDENTITY(1,1) PRIMARY KEY,
        quant_coins INT NOT NULL,
        remetente VARCHAR(50) NOT NULL,
        destinatario VARCHAR(50) NOT NULL,
        destinatario_char INT NOT NULL,
        tdata DATETIME NOT NULL DEFAULT GETDATE()
    );
    
    PRINT '   ✓ Tabla site_log_transfercoins creada exitosamente';
END
ELSE
BEGIN
    PRINT '   ✓ Tabla site_log_transfercoins ya existe';
END
GO

-- ============================================
-- 6. CREAR ÍNDICES PARA OPTIMIZACIÓN
-- ============================================

PRINT '';
PRINT '6. Creando índices...';

-- Índice para búsquedas por cuenta
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_site_donations_account' AND object_id = OBJECT_ID('site_donations'))
BEGIN
    CREATE INDEX IX_site_donations_account ON site_donations(account);
    PRINT '   ✓ Índice IX_site_donations_account creado';
END
ELSE
BEGIN
    PRINT '   ✓ Índice IX_site_donations_account ya existe';
END

-- Índice para búsquedas por estado
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_site_donations_status' AND object_id = OBJECT_ID('site_donations'))
BEGIN
    CREATE INDEX IX_site_donations_status ON site_donations(status);
    PRINT '   ✓ Índice IX_site_donations_status creado';
END
ELSE
BEGIN
    PRINT '   ✓ Índice IX_site_donations_status ya existe';
END

-- Índice para búsquedas por protocolo
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_site_donations_protocolo' AND object_id = OBJECT_ID('site_donations'))
BEGIN
    CREATE INDEX IX_site_donations_protocolo ON site_donations(protocolo);
    PRINT '   ✓ Índice IX_site_donations_protocolo creado';
END
ELSE
BEGIN
    PRINT '   ✓ Índice IX_site_donations_protocolo ya existe';
END

-- Índice para búsquedas por fecha
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_site_donations_data' AND object_id = OBJECT_ID('site_donations'))
BEGIN
    CREATE INDEX IX_site_donations_data ON site_donations(data);
    PRINT '   ✓ Índice IX_site_donations_data creado';
END
ELSE
BEGIN
    PRINT '   ✓ Índice IX_site_donations_data ya existe';
END

-- Índice para site_balance
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_site_balance_account' AND object_id = OBJECT_ID('site_balance'))
BEGIN
    CREATE INDEX IX_site_balance_account ON site_balance(account);
    PRINT '   ✓ Índice IX_site_balance_account creado';
END
ELSE
BEGIN
    PRINT '   ✓ Índice IX_site_balance_account ya existe';
END
GO

-- ============================================
-- 7. VERIFICAR ESTRUCTURA DE TABLAS
-- ============================================

PRINT '';
PRINT '7. Verificando estructura de tablas...';
PRINT '';

PRINT '   Estructura de site_donations:';
SELECT 
    COLUMN_NAME as Campo,
    DATA_TYPE as Tipo,
    CHARACTER_MAXIMUM_LENGTH as Longitud,
    IS_NULLABLE as Nulo,
    COLUMN_DEFAULT as Valor_Default
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'site_donations'
ORDER BY ORDINAL_POSITION;

PRINT '';
PRINT '   Estructura de site_balance:';
SELECT 
    COLUMN_NAME as Campo,
    DATA_TYPE as Tipo,
    IS_NULLABLE as Nulo
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'site_balance'
ORDER BY ORDINAL_POSITION;

PRINT '';
PRINT '   Estructura de site_log_convertcoins:';
SELECT 
    COLUMN_NAME as Campo,
    DATA_TYPE as Tipo,
    IS_NULLABLE as Nulo
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'site_log_convertcoins'
ORDER BY ORDINAL_POSITION;

-- ============================================
-- 8. VERIFICAR ITEM ID DEL DONATE COIN
-- ============================================

PRINT '';
PRINT '8. Buscando Donate Coin en la base de datos...';
PRINT '';

USE lin2world;
GO

PRINT '   Items que contienen "donate" o "coin":';
SELECT TOP 10
    item_id as ID,
    name as Nombre,
    name_en as Nombre_EN
FROM etcitem 
WHERE name LIKE '%donate%' 
   OR name LIKE '%coin%' 
   OR name_en LIKE '%donate%'
   OR name_en LIKE '%coin%'
ORDER BY item_id;

PRINT '';
PRINT '   IMPORTANTE: Anota el item_id del Donate Coin para configurar en el webhook';
PRINT '';

-- ============================================
-- 9. ESTADÍSTICAS FINALES
-- ============================================

USE lin2site;
GO

PRINT '';
PRINT '=== RESUMEN DE CONFIGURACIÓN ===';
PRINT '';

DECLARE @donations_count INT, @balance_count INT, @log_count INT;

SELECT @donations_count = COUNT(*) FROM site_donations;
SELECT @balance_count = COUNT(*) FROM site_balance;
SELECT @log_count = COUNT(*) FROM site_log_convertcoins;

PRINT '   Registros en site_donations: ' + CAST(@donations_count AS VARCHAR(10));
PRINT '   Registros en site_balance: ' + CAST(@balance_count AS VARCHAR(10));
PRINT '   Registros en site_log_convertcoins: ' + CAST(@log_count AS VARCHAR(10));

PRINT '';
PRINT '=== CONFIGURACIÓN COMPLETADA EXITOSAMENTE ===';
PRINT '';
PRINT 'Próximos pasos:';
PRINT '1. Configurar configs.php con las credenciales de la base de datos';
PRINT '2. Configurar el Item ID del Donate Coin en mercadopago_webhook.php';
PRINT '3. Configurar las credenciales de MercadoPago';
PRINT '4. Probar el sistema con una donación de prueba';
PRINT '';

GO
