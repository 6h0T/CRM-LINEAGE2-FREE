-- =============================================
-- Vote Reward System - Tabla de votos
-- Ejecutar este script en la base de datos SITE
-- =============================================

CREATE TABLE site_votes (
    id INT IDENTITY(1,1) PRIMARY KEY,
    account VARCHAR(50) NOT NULL,
    topsite_id INT NOT NULL,
    topsite_name VARCHAR(100) NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    reward INT NOT NULL DEFAULT 1,
    vote_date INT NOT NULL,
    status TINYINT NOT NULL DEFAULT 1
);

-- Índices para mejorar el rendimiento
CREATE INDEX idx_votes_account ON site_votes(account);
CREATE INDEX idx_votes_topsite ON site_votes(topsite_id);
CREATE INDEX idx_votes_date ON site_votes(vote_date);
CREATE INDEX idx_votes_account_topsite ON site_votes(account, topsite_id);
