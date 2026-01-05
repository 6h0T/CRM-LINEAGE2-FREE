# Sistema de Vote Reward

## Descripción
Sistema de votación que permite a los usuarios votar en topsites y recibir recompensas en coins.

## Instalación

### 1. Ejecutar el script SQL
Ejecuta el siguiente script en tu base de datos **SITE**:

```sql
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

CREATE INDEX idx_votes_account ON site_votes(account);
CREATE INDEX idx_votes_topsite ON site_votes(topsite_id);
CREATE INDEX idx_votes_date ON site_votes(vote_date);
CREATE INDEX idx_votes_account_topsite ON site_votes(account, topsite_id);
```

El script también está disponible en: `ucp/private/sql/site_votes.sql`

### 2. Configurar Topsites
Edita el archivo `ucp/private/configs.php` y configura los topsites:

```php
// Activar/desactivar el sistema
$funct['vote'] = 1; // 1 = Activo, 0 = Desactivado

// Recompensa por voto
$voteReward = 1; // Cantidad de coins por voto

// Tiempo de espera entre votos (en horas)
$voteCooldown = 12;

// Método de entrega de recompensas
$voteDeliveryMethod = 'balance'; // 'balance' = saldo online | 'ingame' = directo al personaje

// Configurar cada topsite
$topsites[1]['actived'] = 1;
$topsites[1]['name'] = 'L2TopZone';
$topsites[1]['image'] = 'l2topzone.png';
$topsites[1]['vote_url'] = 'https://l2topzone.com/vote/TU_SERVER_ID';
$topsites[1]['api_key'] = 'TU_API_KEY';
$topsites[1]['api_url'] = 'https://api.l2topzone.com/v1/vote?token=TU_API_KEY&ip=';
```

## Métodos de Entrega

### Balance Online (`$voteDeliveryMethod = 'balance'`)
- Las coins se agregan al saldo online del usuario
- El usuario puede convertirlas a coins in-game desde el panel
- **Recomendado** para mayor control

### Entrega Directa In-Game (`$voteDeliveryMethod = 'ingame'`)
- Las coins se entregan directamente al primer personaje de la cuenta
- Usa el sistema **ItemDelivery** o **user_delivery** (L2OFF)
- El personaje recibe las coins al conectarse o via NPC de delivery

### 3. Agregar imágenes de topsites (opcional)
Coloca las imágenes de los topsites en: `ucp/imgs/topsites/`
- l2topzone.png
- hopzone.png
- l2network.png
- l2servers.png

## Topsites Soportados

### L2TopZone
- URL de voto: `https://l2topzone.com/vote/SERVER_ID`
- API: `https://api.l2topzone.com/v1/vote?token=API_KEY&ip=`

### Hopzone
- URL de voto: `https://hopzone.net/vote/SERVER_ID`
- API: `https://api.hopzone.net/v1/vote?token=API_KEY&ip=`

### L2Network
- URL de voto: `https://l2network.eu/vote/SERVER_ID`
- API: `https://l2network.eu/api/vote?apiKey=API_KEY&ip=`

### L2Servers
- URL de voto: `https://l2servers.com/vote/SERVER_ID`
- API: `https://l2servers.com/api/vote?key=API_KEY&ip=`

## Funcionamiento

1. El usuario accede a **Vote Reward** en el panel
2. Hace clic en **Votar** para ir al topsite
3. Vota en el topsite
4. Regresa al panel y hace clic en **Reclamar**
5. El sistema verifica el voto via API del topsite
6. Si es válido, se acreditan los coins al saldo del usuario

## Archivos del Sistema

```
ucp/
├── private/
│   ├── classes/
│   │   └── classVote.php          # Clase principal
│   ├── configs.php                 # Configuración de topsites
│   └── sql/
│       └── site_votes.sql          # Script SQL
├── pages/
│   └── vote/
│       ├── index.php               # Página principal de votación
│       └── history.php             # Historial de votos
├── engine/
│   └── vote/
│       └── claim_vote.php          # Endpoint para reclamar votos
├── imgs/
│   └── topsites/                   # Imágenes de topsites
└── lang/
    ├── es.php                      # Traducciones español
    ├── en.php                      # Traducciones inglés
    └── pt.php                      # Traducciones portugués
```

## Notas

- El sistema usa la IP del usuario para verificar votos
- Compatible con Cloudflare (detecta `HTTP_CF_CONNECTING_IP`)
- El cooldown se aplica por topsite (puede votar en diferentes topsites simultáneamente)
- Las recompensas se acreditan al saldo online del usuario
