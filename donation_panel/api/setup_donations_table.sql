-- Script SQL para crear la tabla de donaciones
-- Ejecutar este script en la base de datos lin2site

USE lin2site;
GO

-- Verificar si la tabla existe, si no, crearla
IF NOT EXISTS (SELECT * FROM sysobjects WHERE name='site_donations' AND xtype='U')
BEGIN
    CREATE TABLE site_donations (
        id INT IDENTITY(1,1) PRIMARY KEY,
        tid VARCHAR(50) NOT NULL UNIQUE,
        account VARCHAR(50) NOT NULL,
        price DECIMAL(10,2) NOT NULL,
        coins INT NOT NULL DEFAULT 0,
        method VARCHAR(20) NOT NULL,
        status VARCHAR(20) NOT NULL DEFAULT 'pending',
        date DATETIME NOT NULL DEFAULT GETDATE(),
        currency VARCHAR(3) DEFAULT 'ARS',
        processed_date DATETIME NULL,
        notes TEXT NULL
    );
    
    PRINT 'Tabla site_donations creada exitosamente';
END
ELSE
BEGIN
    PRINT 'La tabla site_donations ya existe';
END
GO

-- Crear índices para mejorar el rendimiento
IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_site_donations_account')
BEGIN
    CREATE INDEX IX_site_donations_account ON site_donations(account);
    PRINT 'Índice IX_site_donations_account creado';
END
GO

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_site_donations_status')
BEGIN
    CREATE INDEX IX_site_donations_status ON site_donations(status);
    PRINT 'Índice IX_site_donations_status creado';
END
GO

IF NOT EXISTS (SELECT * FROM sys.indexes WHERE name = 'IX_site_donations_date')
BEGIN
    CREATE INDEX IX_site_donations_date ON site_donations(date);
    PRINT 'Índice IX_site_donations_date creado';
END
GO

-- Verificar estructura de la tabla
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    CHARACTER_MAXIMUM_LENGTH,
    IS_NULLABLE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_NAME = 'site_donations'
ORDER BY ORDINAL_POSITION;
GO

PRINT 'Setup completado exitosamente';
