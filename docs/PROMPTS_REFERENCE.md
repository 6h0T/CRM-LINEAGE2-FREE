# CRM Lineage 2 - Quick Prompts Reference / Referencia Rápida de Prompts

This file contains copy-paste ready prompts for common customizations.
Este archivo contiene prompts listos para copiar y pegar para personalizaciones comunes.

---

## 🎨 Branding & Visual / Branding y Visual

### Change Server Name / Cambiar Nombre del Servidor
```
Change the server name from "[SERVER_NAME]" to "[NEW_NAME]" across all files including:
- translations.ts (welcome messages, footer)
- index.html (title)
- All references in App.tsx
```

### Change Logo / Cambiar Logo
```
Replace the logo at /public/logo.png with a new logo. The new logo should be:
- Format: PNG or SVG
- Recommended size: 200x200px minimum
- Background: Transparent preferred
Update any size/styling references if needed.
```

### Change Color Theme / Cambiar Tema de Colores
```
Change the website color theme from gold/brown to [NEW_COLORS].
Replace these hex values throughout the codebase:
- #ffd700 (gold accent) → [NEW_ACCENT]
- #cbb085 (light text) → [NEW_TEXT]
- #050403 (dark background) → [NEW_BG]
- #5e4b35 (borders) → [NEW_BORDER]
```

### Change Background Image / Cambiar Imagen de Fondo
```
Replace the background image in App.tsx line 64.
Current URL: https://w.wallhaven.cc/full/k7/wallhaven-k7yqxd.jpg
New URL: [YOUR_IMAGE_URL]
The image should be high resolution (2560x1440 recommended) and dark themed.
```

---

## ⚔️ Server Rates / Rates del Servidor

### Change XP Rate / Cambiar Rate de XP
```
Update the server XP/SP/Adena rate from x10 to x[NEW_RATE].
Files to modify:
- translations.ts: features.general.rates (all 3 languages)
- translations.ts: home.desc (all 3 languages)
```

### Change Enchant Settings / Cambiar Configuración de Enchant
```
Update enchant settings:
- Safe enchant: +[NUMBER]
- Max enchant: +[NUMBER]
- Blessed scroll fail result: +[NUMBER]
- Augment chance: [PERCENTAGE]%

Modify translations.ts features.enchant section for all languages.
```

### Change Premium Rates / Cambiar Rates Premium
```
Update premium account bonuses:
- Drop rate: [MULTIPLIER]x
- Adena rate: [MULTIPLIER]x
- EXP/SP rate: [MULTIPLIER]x

Modify translations.ts features.premium.items for all languages.
```

### Change Olympiad Settings / Cambiar Configuración de Olimpiadas
```
Update Olympiad configuration:
- Schedule: [START_TIME] - [END_TIME] (GMT-3)
- Max enchant allowed: +[NUMBER]
- Cycle duration: [NUMBER] days
- Boss jewels: [ALLOWED/NOT_ALLOWED]

Modify translations.ts features.olympiad section for all languages.
```

---

## 💰 Donations / Donaciones

### Update ARS Prices / Actualizar Precios ARS
```
Update Argentine Peso donation prices in translations.ts donate.table.ars:

New price table:
| ARS Amount | Donate Coins |
|------------|--------------|
| [AMOUNT1]  | [COINS1]     |
| [AMOUNT2]  | [COINS2]     |
| [AMOUNT3]  | [COINS3]     |

Apply to all 3 language sections.
```

### Update USD Prices / Actualizar Precios USD
```
Update US Dollar donation prices in translations.ts donate.table.usd:

New price table:
| USD Amount | Donate Coins |
|------------|--------------|
| [AMOUNT1]  | [COINS1]     |
| [AMOUNT2]  | [COINS2]     |
| [AMOUNT3]  | [COINS3]     |

Apply to all 3 language sections.
```

### Add Payment Method / Agregar Método de Pago
```
Add a new payment method button to the donation panel.
Payment method: [METHOD_NAME]
Button text (ES): [SPANISH_TEXT]
Button text (EN): [ENGLISH_TEXT]
Button text (PT): [PORTUGUESE_TEXT]
Payment URL: [PAYMENT_URL]
```

### Update Donation Items / Actualizar Items de Donación
```
Update the list of items available in the donation shop:
[LIST YOUR ITEMS HERE]

Example: Skin Stones, Level Up Scrolls, Name Change, Clan Reputation, etc.

Modify translations.ts donate.items for all languages.
```

---

## 🔗 Links & Social / Links y Redes Sociales

### Update Discord Link / Actualizar Link de Discord
```
Update the Discord server invite link to: [YOUR_DISCORD_INVITE]
Search for Discord references in App.tsx and update all href attributes.
```

### Update Twitch Channel / Actualizar Canal de Twitch
```
Update the Twitch stream embed in App.tsx:
- Channel name: [YOUR_CHANNEL]
- Parent domain: [YOUR_DOMAIN] (e.g., l2gae.com)

Line 107: Change channel=twitch to channel=[YOUR_CHANNEL]
```

### Update Download Links / Actualizar Links de Descarga
```
Update game client download links:
- Google Drive: [GDRIVE_URL]
- Mega: [MEGA_URL]
- MediaFire: [MEDIAFIRE_URL]
- Torrent: [TORRENT_URL]

Search for download section in App.tsx and update accordingly.
```

---

## 📰 Content / Contenido

### Update Server News / Actualizar Noticias del Servidor
```
Replace the mock news in App.tsx (lines 528-532) with:

News Item 1:
- Title: [TITLE]
- Date: [DATE]
- Author: [AUTHOR]
- Content: [CONTENT]

News Item 2:
- Title: [TITLE]
- Date: [DATE]
- Author: [AUTHOR]
- Content: [CONTENT]
```

### Add New Language / Agregar Nuevo Idioma
```
Add support for [LANGUAGE_CODE] language (e.g., 'fr' for French).
1. Add new language option to types.ts Language type
2. Add complete translation object in translations.ts
3. Add language button in Navigation.tsx mobile menu
4. Test all pages render correctly with new language
```

### Update Footer Year / Actualizar Año del Footer
```
Update the copyright year in the footer from 2024 to [YEAR].
Modify translations.ts footer text for all 3 languages (lines 208, 413, 618).
```

---

## 🛠️ Technical / Técnico

### Change Dev Server Port / Cambiar Puerto del Servidor Dev
```
Change the development server port from 3000 to [PORT].
Modify vite.config.ts line 9: port: [PORT]
```

### Add Google Analytics / Agregar Google Analytics
```
Add Google Analytics tracking to the website.
Tracking ID: [GA_TRACKING_ID]
Add the script tag to index.html in the <head> section.
```

### Enable/Disable Gemini AI / Habilitar/Deshabilitar Gemini AI
```
[ENABLE/DISABLE] the Gemini AI integration.
The API key is configured in vite.config.ts and loaded from .env file.
To disable, remove or comment out the @google/genai import and related code.
```

---

## 📋 Complete Server Setup Template / Plantilla Completa de Configuración

```
Configure the CRM Lineage 2 website with the following server settings:

SERVER NAME: [NAME]
CHRONICLE: [CHRONICLE]

RATES:
- EXP/SP: x[RATE]
- Adena: x[RATE]
- Drop: x[RATE]
- Spoil: x[RATE]

ENCHANT:
- Safe: +[NUMBER]
- Max: +[NUMBER]
- Blessed fail: +[NUMBER]

PREMIUM BONUSES:
- EXP/SP: [MULTIPLIER]x
- Drop: [MULTIPLIER]x
- Adena: [MULTIPLIER]x

OLYMPIAD:
- Schedule: [TIME] (GMT-3)
- Max enchant: +[NUMBER]
- Cycle: [DAYS] days

CLAN:
- Max members: [NUMBER]
- RB party limit: [NUMBER]
- Epic party limit: [NUMBER]

SECURITY:
- Max clients per PC: [NUMBER]
- DDoS protection: [YES/NO]

SOCIAL LINKS:
- Discord: [URL]
- Facebook: [URL]
- Twitch: [CHANNEL]

DONATIONS (USD):
- $10 = [COINS] coins
- $20 = [COINS] coins
- $50 = [COINS] coins
- $100 = [COINS] coins

Apply all these settings to translations.ts for all 3 languages (ES, EN, PT).
```

---

## Notes / Notas

- Always backup files before making changes / Siempre haz backup antes de hacer cambios
- Test in development before deploying / Prueba en desarrollo antes de desplegar
- Check all 3 languages after updates / Verifica los 3 idiomas después de actualizar
- Clear browser cache after visual changes / Limpia el caché del navegador después de cambios visuales
