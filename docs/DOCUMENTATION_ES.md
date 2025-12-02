# CRM Lineage 2 - Documentación del Proyecto

## Tabla de Contenidos
1. [Descripción del Proyecto](#descripción-del-proyecto)
2. [Stack Tecnológico](#stack-tecnológico)
3. [Estructura del Proyecto](#estructura-del-proyecto)
4. [Internacionalización (i18n)](#internacionalización-i18n)
5. [Prompts de Personalización](#prompts-de-personalización)
   - [Cambiar Logos y Branding](#cambiar-logos-y-branding)
   - [Información del Servidor y Rates](#información-del-servidor-y-rates)
   - [Configuración de Donaciones](#configuración-de-donaciones)
   - [Links Sociales y Streams](#links-sociales-y-streams)
6. [Comandos de Desarrollo](#comandos-de-desarrollo)

---

## Descripción del Proyecto

**CRM Lineage 2** es una plantilla web gratuita, moderna y responsiva para servidores privados de Lineage 2. El sitio incluye:

- **Soporte multi-idioma**: Español (ES), Inglés (EN), Portugués (PT)
- **Tema fantasía oscura** con paleta de colores dorado/marrón
- **Diseño responsivo** para móvil y escritorio
- **Elementos UI animados** con fondos de video
- **Información del servidor** con rates, características y reglas
- **Sistema de donaciones** con múltiples monedas (ARS, USD, CLP)
- **Ranking de Olimpiadas**
- **Sección de descargas** para el cliente del juego
- **Galería de skins y animaciones**
- **Formulario de registro de usuarios**
- **Widget de streaming en vivo** (integración Twitch/Kick)

---

## Stack Tecnológico

| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| **React** | 19.2.0 | Framework frontend |
| **TypeScript** | 5.8.2 | JavaScript con tipos |
| **Vite** | 6.2.0 | Herramienta de build y servidor dev |
| **TailwindCSS** | CDN | Framework CSS utility-first |
| **Lucide React** | 0.554.0 | Librería de iconos |
| **Google Genai** | 1.30.0 | Integración IA (opcional) |

### Fuentes Utilizadas
- **Cinzel** - Fuente serif fantasía para títulos
- **Lato** - Fuente sans-serif para texto

### Paleta de Colores
```css
Fondo Principal: #050403 (Marrón oscuro/negro)
Fondo Secundario: #12100e, #1a1612
Bordes: #3d3122, #5e4b35, #2e2418
Acento Dorado: #ffd700
Texto Principal: #cbb085
Texto Secundario: #8b7d6b, #918671
```

---

## Estructura del Proyecto

```
l2gaev1/
├── App.tsx                 # Componente principal de la aplicación
├── index.tsx               # Punto de entrada de React
├── index.html              # Plantilla HTML
├── types.ts                # Interfaces y enums de TypeScript
├── vite.config.ts          # Configuración de Vite
├── tsconfig.json           # Configuración de TypeScript
├── package.json            # Dependencias y scripts
├── src/
│   └── i18n/               # Internacionalización
│       ├── index.ts        # Configuración de i18next
│       ├── useLanguage.ts  # Hook de idioma
│       └── locales/        # Archivos de traducción
│           ├── es.json     # Traducciones en español
│           ├── en.json     # Traducciones en inglés
│           └── pt.json     # Traducciones en portugués
├── components/
│   ├── Navigation.tsx      # Componente de navegación
│   ├── FeaturesList.tsx    # Características del servidor
│   ├── DonationPanel.tsx   # Sistema de donaciones
│   ├── OlympiadRanking.tsx # Rankings de Olimpiadas
│   ├── FireSparks.tsx      # Efectos de partículas de fuego
│   ├── ImageFactory.tsx    # Generación de imágenes
│   └── NewsOracle.tsx      # Componente de noticias
└── public/
    ├── logo.png            # Logo de tu servidor (REEMPLAZAR)
    └── flame-svgrepo-com.svg # Icono de llama para carga
```

---

## Internacionalización (i18n)

El proyecto usa **react-i18next** para soporte multi-idioma. Para documentación detallada, ver **[I18N_GUIDE.md](./I18N_GUIDE.md)**.

### Referencia Rápida

**Ubicación de Archivos de Traducción:**
```
src/i18n/locales/
├── es.json   # Español
├── en.json   # Inglés
└── pt.json   # Portugués
```

**Usar Traducciones en Componentes:**
```tsx
import { useTranslation } from 'react-i18next';

function MiComponente() {
  const { t, i18n } = useTranslation();
  
  return (
    <div>
      <h1>{t('home.welcome')}</h1>
      <button onClick={() => i18n.changeLanguage('es')}>Español</button>
    </div>
  );
}
```

**Secciones de Traducción Principales:**
| Sección | Claves | Descripción |
|---------|--------|-------------|
| `nav.*` | home, downloads, info, ranking, donate | Etiquetas de navegación |
| `home.*` | welcome, desc, newsTitle | Contenido de inicio |
| `features.*` | general, premium, enchant, equipment | Info del servidor |
| `donate.*` | title, form, conversion | Sistema de donaciones |
| `register.*` | title, username, password, submit | Formulario de registro |
| `sidebar.*` | status, online, players, login | Contenido del sidebar |

---

## Prompts de Personalización

### Cambiar Logos y Branding

#### Prompt 1: Cambiar Logo Principal
```
Cambia el logo principal del sitio web. El logo actual está en /public/logo.png.
Reemplázalo con un nuevo logo y actualiza las referencias en el código.
El logo debe estar optimizado para web (formato PNG o SVG, máximo 500KB).
```

#### Prompt 2: Cambiar Icono de Animación de Carga
```
Cambia el icono de llama de la pantalla de carga. El icono actual está en /public/flame-svgrepo-com.svg.
Reemplázalo con un icono SVG personalizado que coincida con el branding del servidor.
Actualiza la referencia en App.tsx en la línea 548.
```

#### Prompt 3: Cambiar Título del Sitio y Meta
```
Actualiza el título del sitio web y metadatos. Actualmente está como "L2 GaE - Interlude" en index.html.
Cambia a: [NOMBRE_DE_TU_SERVIDOR]
También actualiza el texto del footer en translations.ts (líneas 208, 413, 618).
```

#### Prompt 4: Cambiar Imágenes de Fondo
```
Actualiza las imágenes de fondo. El fondo actual es de wallhaven:
- App.tsx línea 64: Imagen de fondo principal del sitio
- App.tsx líneas 15 y 45: Fondos de video

Reemplaza con imágenes/videos personalizados que coincidan con el tema de tu servidor.
```

---

### Información del Servidor y Rates

#### Prompt 5: Cambiar Rates del Servidor (EXP/SP/Adena)
```
Actualiza los rates del servidor en translations.ts. Los rates actuales son x10.
Modifica las siguientes secciones para todos los idiomas (es, en, pt):

- features.general.rates (líneas 66, 271, 476)
  Actual: "Rates: EXP/SP/Adena x10"
  Cambiar a: "Rates: EXP/SP/Adena x[TU_RATE]"

- home.desc (líneas 57, 262, 467)
  Actual: "Servidor Lineage 2 Interlude x10"
  Cambiar a: "Servidor Lineage 2 Interlude x[TU_RATE]"
```

#### Prompt 6: Cambiar Versión de Crónica
```
Actualiza la versión de crónica en translations.ts:
- features.general.chronicle (líneas 65, 270, 475)
  Actual: "Crónica: Interlude"
  Cambiar a: "Crónica: [TU_CRONICA]" (ej: High Five, Gracia Final)
```

#### Prompt 7: Cambiar Rates de Encantamiento
```
Actualiza la configuración de encantamiento en translations.ts:
- features.enchant.safe (líneas 84, 289, 494): Nivel de encantamiento seguro
- features.enchant.max (líneas 85, 290, 495): Nivel máximo de encantamiento
- features.enchant.blessed (líneas 86, 291, 496): Comportamiento de scroll blessed
- features.enchant.gae (líneas 87, 292, 497): Comportamiento de scroll blessed GAE
- features.enchant.augmentChance (líneas 88, 293, 498): Tasa de éxito de augment
```

#### Prompt 8: Cambiar Rates Premium/Boost
```
Actualiza los rates premium en translations.ts:
- features.premium.items (líneas 76-80, 281-285, 486-490)
  Actual: Drop 1.5x, Adena 1.5x, EXP/SP 1.5x
  Cambia a tus rates premium personalizados.
```

#### Prompt 9: Cambiar Configuración de Olimpiadas
```
Actualiza la configuración de Olimpiadas en translations.ts:
- features.olympiad.time (líneas 106, 311, 516): Horario
- features.olympiad.maxEnchant (líneas 107, 312, 517): Enchant máximo en Olimpiadas
- features.olympiad.cycle (líneas 108, 313, 518): Duración del ciclo
- features.olympiad.jewels (líneas 109, 314, 519): Joyas de boss permitidas
```

#### Prompt 10: Cambiar Configuración del Sistema de Clan
```
Actualiza la configuración de clan en translations.ts:
- features.clan.maxMembers (líneas 100, 305, 510): Miembros máximos del clan
- features.clan.raidLimit (líneas 101, 306, 511): Límite de partys para raid boss
- features.clan.epicLimit (líneas 102, 307, 512): Límite de partys para epic boss
```

#### Prompt 11: Cambiar Configuración de Seguridad
```
Actualiza la configuración de seguridad en translations.ts:
- features.security.clients (líneas 119, 324, 529): Máximo de clientes por PC
- features.security.ddos (líneas 120, 325, 530): Estado de protección DDoS
```

---

### Configuración de Donaciones

#### Prompt 12: Cambiar Precios de Donación (ARS)
```
Actualiza la tabla de donaciones en Pesos Argentinos en translations.ts:
- donate.table.ars (líneas 163-167, 368-372, 573-577)

Formato: { amount: "PRECIO", coins: "COINS_RECIBIDOS" }
Ejemplo: { amount: "15.000", coins: "1.000" }
```

#### Prompt 13: Cambiar Precios de Donación (USD)
```
Actualiza la tabla de donaciones en Dólares en translations.ts:
- donate.table.usd (líneas 168-180, 373-385, 578-590)

Formato: { amount: "PRECIO", coins: "COINS_RECIBIDOS" }
Ejemplo: { amount: "10", coins: "1.000" }
```

#### Prompt 14: Cambiar Precios de Donación (CLP)
```
Actualiza la tabla de donaciones en Pesos Chilenos en translations.ts:
- donate.table.clp (líneas 181-193, 386-398, 591-603)

Formato: { amount: "PRECIO", coins: "COINS_RECIBIDOS" }
Ejemplo: { amount: "10.000", coins: "1.000" }
```

#### Prompt 15: Cambiar Descripción de Items de Donación
```
Actualiza la descripción de items de la tienda de donación en translations.ts:
- donate.items (líneas 146, 351, 556)
  Actual: "Piedras de Skin, Level Up, Karma, Cambio de Nombre, Color de Nombre, Armas/Armaduras AYS"
  Cambia a tus items de donación disponibles.
```

#### Prompt 16: Agregar Nueva Moneda
```
Agrega una nueva opción de moneda al sistema de donaciones.
1. Agrega el nombre de la moneda a donate.conversion en translations.ts
2. Agrega la tabla de precios a donate.table
3. Actualiza DonationPanel.tsx para incluir la nueva opción de moneda
```

---

### Links Sociales y Streams

#### Prompt 17: Cambiar Link de Discord
```
Actualiza el link del servidor de Discord. Busca las referencias de Discord en App.tsx
y actualiza el atributo href al link de invitación de tu servidor de Discord.
```

#### Prompt 18: Cambiar Link de Facebook
```
Actualiza el link de la página de Facebook. Busca las referencias de Facebook en App.tsx
y actualiza el atributo href a la página de Facebook de tu servidor.
```

#### Prompt 19: Cambiar Stream de Twitch
```
Actualiza el embed de Twitch en App.tsx línea 107:
Actual: src="https://player.twitch.tv/?channel=twitch&parent=localhost"
Cambia 'twitch' por el nombre de tu canal y actualiza 'parent' a tu dominio.
```

#### Prompt 20: Cambiar Stream de Kick
```
Actualiza el embed de Kick en App.tsx línea 117:
Actual: src="https://player.kick.com/kick"
Cambia 'kick' por el nombre de tu canal.
```

#### Prompt 21: Cambiar Links de Descarga
```
Actualiza los links de descarga del cliente del juego. Busca los componentes
relacionados con descarga en App.tsx y actualiza los atributos href a tus
links de hosting de archivos (Google Drive, Mega, MediaFire, etc.).
```

---

## Comandos de Desarrollo

```bash
# Instalar dependencias
npm install

# Iniciar servidor de desarrollo (puerto 3000)
npm run dev

# Compilar para producción
npm run build

# Previsualizar build de producción
npm run preview
```

### Variables de Entorno

Crea un archivo `.env` en el directorio raíz:
```env
GEMINI_API_KEY=tu_api_key_aqui
```

---

## Referencia Rápida: Ubicación de Archivos Clave

| Qué Cambiar | Archivo | Líneas |
|-------------|---------|--------|
| Nombre del servidor | `translations.ts` | 56, 261, 466 |
| Rates | `translations.ts` | 66, 271, 476 |
| Config. encantamiento | `translations.ts` | 83-89, 288-294, 493-499 |
| Precios donación | `translations.ts` | 163-193, 368-398, 573-603 |
| Logo | `public/logo.png` | - |
| Icono de carga | `public/flame-svgrepo-com.svg` | - |
| Imagen de fondo | `App.tsx` | 64 |
| Embeds de stream | `App.tsx` | 107, 117 |
| Título del sitio | `index.html` | 6 |
| Footer | `translations.ts` | 208, 413, 618 |

---

## Prompts Adicionales Útiles

### Prompt 22: Cambiar Colores del Tema
```
Cambia la paleta de colores del sitio. Los colores principales están definidos en:
- index.html (líneas 10-30): Estilos CSS base
- App.tsx: Clases de Tailwind con colores hexadecimales

Colores actuales a reemplazar:
- #050403 (fondo principal)
- #ffd700 (dorado/acento)
- #cbb085 (texto claro)
- #5e4b35 (bordes)
```

### Prompt 23: Agregar Nueva Página
```
Agrega una nueva página al sitio web.
1. Agrega el nuevo valor al enum Page en types.ts
2. Agrega las traducciones correspondientes en translations.ts
3. Agrega el NavItem en Navigation.tsx
4. Agrega el renderizado condicional en App.tsx
```

### Prompt 24: Cambiar Noticias del Servidor
```
Actualiza las noticias del servidor en App.tsx líneas 528-532.
El formato es:
{ id: number, title: string, date: string, author: string, content: string }

Ejemplo:
{ id: 1, title: 'Gran Apertura', date: 'Dic 2024', author: 'Admin', content: 'Texto...' }
```

### Prompt 25: Cambiar Datos del Ranking de Olimpiadas
```
Actualiza los datos del ranking de Olimpiadas en components/OlympiadRanking.tsx.
Conecta con tu API del servidor o actualiza los datos mock para mostrar
los héroes actuales y sus puntuaciones.
```

---

## Soporte

Para personalización adicional o soporte técnico, consulta los siguientes recursos:
- [Documentación de React](https://react.dev/)
- [Documentación de Vite](https://vitejs.dev/)
- [Documentación de TailwindCSS](https://tailwindcss.com/)
- [Iconos Lucide](https://lucide.dev/)
