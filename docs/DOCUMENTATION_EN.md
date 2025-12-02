# CRM Lineage 2 - Project Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [Tech Stack](#tech-stack)
3. [Project Structure](#project-structure)
4. [Internationalization (i18n)](#internationalization-i18n)
5. [Customization Prompts](#customization-prompts)
   - [Changing Logos and Branding](#changing-logos-and-branding)
   - [Server Information and Rates](#server-information-and-rates)
   - [Donation Configuration](#donation-configuration)
   - [Social Links and Streams](#social-links-and-streams)
6. [Development Commands](#development-commands)

---

## Project Overview

**CRM Lineage 2** is a free, modern, responsive website template for Lineage 2 private servers. The site features:

- **Multi-language support**: Spanish (ES), English (EN), Portuguese (PT)
- **Dark fantasy theme** with gold/brown color palette
- **Responsive design** for mobile and desktop
- **Animated UI elements** with video backgrounds
- **Server information display** with rates, features, and rules
- **Donation system** with multiple currencies (ARS, USD, CLP)
- **Olympiad ranking** display
- **Download section** for game client
- **Skins and animations gallery**
- **User registration form**
- **Live streaming widget** (Twitch/Kick integration)

---

## Tech Stack

| Technology | Version | Purpose |
|------------|---------|---------|
| **React** | 19.2.0 | Frontend framework |
| **TypeScript** | 5.8.2 | Type-safe JavaScript |
| **Vite** | 6.2.0 | Build tool and dev server |
| **TailwindCSS** | CDN | Utility-first CSS framework |
| **Lucide React** | 0.554.0 | Icon library |
| **Google Genai** | 1.30.0 | AI integration (optional) |

### Fonts Used
- **Cinzel** - Fantasy serif font for headings
- **Lato** - Sans-serif font for body text

### Color Palette
```css
Primary Background: #050403 (Dark brown/black)
Secondary Background: #12100e, #1a1612
Borders: #3d3122, #5e4b35, #2e2418
Gold Accent: #ffd700
Text Primary: #cbb085
Text Secondary: #8b7d6b, #918671
```

---

## Project Structure

```
l2gaev1/
├── App.tsx                 # Main application component
├── index.tsx               # React entry point
├── index.html              # HTML template
├── types.ts                # TypeScript interfaces and enums
├── vite.config.ts          # Vite configuration
├── tsconfig.json           # TypeScript configuration
├── package.json            # Dependencies and scripts
├── src/
│   └── i18n/               # Internationalization
│       ├── index.ts        # i18next configuration
│       ├── useLanguage.ts  # Language hook
│       └── locales/        # Translation files
│           ├── es.json     # Spanish translations
│           ├── en.json     # English translations
│           └── pt.json     # Portuguese translations
├── components/
│   ├── Navigation.tsx      # Navigation bar component
│   ├── FeaturesList.tsx    # Server features display
│   ├── DonationPanel.tsx   # Donation system
│   ├── OlympiadRanking.tsx # Olympiad rankings
│   ├── FireSparks.tsx      # Fire particle effects
│   ├── ImageFactory.tsx    # Image generation
│   └── NewsOracle.tsx      # News component
└── public/
    ├── logo.png            # Your server logo (REPLACE)
    └── flame-svgrepo-com.svg # Loading flame icon
```

---

## Internationalization (i18n)

The project uses **react-i18next** for multi-language support. For detailed documentation, see **[I18N_GUIDE.md](./I18N_GUIDE.md)**.

### Quick Reference

**Translation Files Location:**
```
src/i18n/locales/
├── es.json   # Spanish
├── en.json   # English
└── pt.json   # Portuguese
```

**Using Translations in Components:**
```tsx
import { useTranslation } from 'react-i18next';

function MyComponent() {
  const { t, i18n } = useTranslation();
  
  return (
    <div>
      <h1>{t('home.welcome')}</h1>
      <button onClick={() => i18n.changeLanguage('en')}>English</button>
    </div>
  );
}
```

**Key Translation Sections:**
| Section | Keys | Description |
|---------|------|-------------|
| `nav.*` | home, downloads, info, ranking, donate | Navigation labels |
| `home.*` | welcome, desc, newsTitle | Home page content |
| `features.*` | general, premium, enchant, equipment | Server info |
| `donate.*` | title, form, conversion | Donation system |
| `register.*` | title, username, password, submit | Registration form |
| `sidebar.*` | status, online, players, login | Sidebar content |

---

## Customization Prompts

### Changing Logos and Branding

#### Prompt 1: Change Main Logo
```
Change the main logo of the website. The current logo is located at /public/logo.png. 
Replace it with a new logo and update any references in the code.
The logo should be optimized for web (PNG or SVG format, max 500KB).
```

#### Prompt 2: Change Loading Animation Icon
```
Change the loading screen flame icon. The current icon is at /public/flame-svgrepo-com.svg.
Replace it with a custom SVG icon that matches the server branding.
Update the reference in App.tsx at line 548.
```

#### Prompt 3: Change Site Title and Meta
```
Update the website title and metadata. Currently set to "L2 GaE - Interlude" in index.html.
Change to: [YOUR_SERVER_NAME]
Also update the footer text in translations.ts (lines 208, 413, 618).
```

#### Prompt 4: Change Background Images
```
Update the background images. Current background is from wallhaven:
- App.tsx line 64: Main site background image
- App.tsx lines 15 and 45: Video backgrounds

Replace with custom images/videos that match your server theme.
```

---

### Server Information and Rates

#### Prompt 5: Change Server Rates (EXP/SP/Adena)
```
Update the server rates in translations.ts. Current rates are x10.
Modify the following sections for all languages (es, en, pt):

- features.general.rates (lines 66, 271, 476)
  Current: "Rates: EXP/SP/Adena x10"
  Change to: "Rates: EXP/SP/Adena x[YOUR_RATE]"

- home.desc (lines 57, 262, 467)
  Current: "Servidor Lineage 2 Interlude x10"
  Change to: "Servidor Lineage 2 Interlude x[YOUR_RATE]"
```

#### Prompt 6: Change Chronicle Version
```
Update the chronicle version in translations.ts:
- features.general.chronicle (lines 65, 270, 475)
  Current: "Chronicle: Interlude"
  Change to: "Chronicle: [YOUR_CHRONICLE]" (e.g., High Five, Gracia Final)
```

#### Prompt 7: Change Enchant Rates
```
Update enchant configuration in translations.ts:
- features.enchant.safe (lines 84, 289, 494): Safe enchant level
- features.enchant.max (lines 85, 290, 495): Maximum enchant level
- features.enchant.blessed (lines 86, 291, 496): Blessed scroll behavior
- features.enchant.gae (lines 87, 292, 497): GAE blessed scroll behavior
- features.enchant.augmentChance (lines 88, 293, 498): Augment success rate
```

#### Prompt 8: Change Premium/Boost Rates
```
Update premium rates in translations.ts:
- features.premium.items (lines 76-80, 281-285, 486-490)
  Current: Drop 1.5x, Adena 1.5x, EXP/SP 1.5x
  Change to your custom premium rates.
```

#### Prompt 9: Change Olympiad Settings
```
Update Olympiad configuration in translations.ts:
- features.olympiad.time (lines 106, 311, 516): Schedule
- features.olympiad.maxEnchant (lines 107, 312, 517): Max enchant in Olympiad
- features.olympiad.cycle (lines 108, 313, 518): Cycle duration
- features.olympiad.jewels (lines 109, 314, 519): Boss jewels allowed
```

#### Prompt 10: Change Clan System Settings
```
Update clan settings in translations.ts:
- features.clan.maxMembers (lines 100, 305, 510): Maximum clan members
- features.clan.raidLimit (lines 101, 306, 511): Raid boss party limit
- features.clan.epicLimit (lines 102, 307, 512): Epic boss party limit
```

#### Prompt 11: Change Security Settings
```
Update security settings in translations.ts:
- features.security.clients (lines 119, 324, 529): Max clients per PC
- features.security.ddos (lines 120, 325, 530): DDoS protection status
```

---

### Donation Configuration

#### Prompt 12: Change Donation Prices (ARS)
```
Update Argentine Peso donation table in translations.ts:
- donate.table.ars (lines 163-167, 368-372, 573-577)

Format: { amount: "PRICE", coins: "COINS_RECEIVED" }
Example: { amount: "15.000", coins: "1.000" }
```

#### Prompt 13: Change Donation Prices (USD)
```
Update US Dollar donation table in translations.ts:
- donate.table.usd (lines 168-180, 373-385, 578-590)

Format: { amount: "PRICE", coins: "COINS_RECEIVED" }
Example: { amount: "10", coins: "1.000" }
```

#### Prompt 14: Change Donation Prices (CLP)
```
Update Chilean Peso donation table in translations.ts:
- donate.table.clp (lines 181-193, 386-398, 591-603)

Format: { amount: "PRICE", coins: "COINS_RECEIVED" }
Example: { amount: "10.000", coins: "1.000" }
```

#### Prompt 15: Change Donation Items Description
```
Update donation shop items description in translations.ts:
- donate.items (lines 146, 351, 556)
  Current: "Skin Stones, Level Up, Karma, Name Change, Name Color, AYS Weapons/Armors"
  Change to your available donation items.
```

#### Prompt 16: Add New Currency
```
Add a new currency option to the donation system.
1. Add currency name to donate.conversion in translations.ts
2. Add price table to donate.table
3. Update DonationPanel.tsx to include the new currency option
```

---

### Social Links and Streams

#### Prompt 17: Change Discord Link
```
Update Discord server link. Search for Discord references in App.tsx
and update the href attribute to your server's Discord invite link.
```

#### Prompt 18: Change Facebook Link
```
Update Facebook page link. Search for Facebook references in App.tsx
and update the href attribute to your server's Facebook page.
```

#### Prompt 19: Change Twitch Stream
```
Update Twitch stream embed in App.tsx line 107:
Current: src="https://player.twitch.tv/?channel=twitch&parent=localhost"
Change 'twitch' to your channel name and update 'parent' to your domain.
```

#### Prompt 20: Change Kick Stream
```
Update Kick stream embed in App.tsx line 117:
Current: src="https://player.kick.com/kick"
Change 'kick' to your channel name.
```

#### Prompt 21: Change Download Links
```
Update game client download links. Search for download-related
components in App.tsx and update the href attributes to your
file hosting links (Google Drive, Mega, MediaFire, etc.).
```

---

## Development Commands

```bash
# Install dependencies
npm install

# Start development server (port 3000)
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview
```

### Environment Variables

Create a `.env` file in the root directory:
```env
GEMINI_API_KEY=your_api_key_here
```

---

## Quick Reference: Key File Locations

| What to Change | File | Lines |
|----------------|------|-------|
| Server name | `translations.ts` | 56, 261, 466 |
| Rates | `translations.ts` | 66, 271, 476 |
| Enchant settings | `translations.ts` | 83-89, 288-294, 493-499 |
| Donation prices | `translations.ts` | 163-193, 368-398, 573-603 |
| Logo | `public/logo.png` | - |
| Loading icon | `public/flame-svgrepo-com.svg` | - |
| Background image | `App.tsx` | 64 |
| Stream embeds | `App.tsx` | 107, 117 |
| Site title | `index.html` | 6 |
| Footer | `translations.ts` | 208, 413, 618 |

---

## Support

For additional customization or technical support, refer to the following resources:
- [React Documentation](https://react.dev/)
- [Vite Documentation](https://vitejs.dev/)
- [TailwindCSS Documentation](https://tailwindcss.com/)
- [Lucide Icons](https://lucide.dev/)
