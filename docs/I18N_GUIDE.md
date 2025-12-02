# Internationalization (i18n) Guide / Guía de Internacionalización

This document explains how to manage translations in the CRM Lineage 2.
Este documento explica cómo gestionar las traducciones en CRM Lineage 2.

---

## Table of Contents / Tabla de Contenidos

1. [Overview / Descripción General](#overview)
2. [File Structure / Estructura de Archivos](#file-structure)
3. [Adding Translations / Agregar Traducciones](#adding-translations)
4. [Using Translations in Components / Usar Traducciones en Componentes](#using-translations)
5. [Adding a New Language / Agregar un Nuevo Idioma](#adding-new-language)
6. [AI Agent Prompts / Prompts para Agentes IA](#ai-prompts)

---

## Overview

The project uses **react-i18next** for internationalization with the following features:

- **3 Languages**: Spanish (es), English (en), Portuguese (pt)
- **Browser Detection**: Automatically detects user's preferred language
- **LocalStorage Persistence**: Remembers user's language choice
- **JSON-based Translations**: Easy to edit and maintain

### Technology Stack

| Package | Version | Purpose |
|---------|---------|---------|
| i18next | ^23.16.0 | Core i18n framework |
| react-i18next | ^15.1.0 | React bindings |
| i18next-browser-languagedetector | ^8.0.0 | Auto language detection |

---

## File Structure

```
src/
└── i18n/
    ├── index.ts              # i18next configuration
    ├── useLanguage.ts        # Custom hook for language management
    ├── useTranslations.ts    # Backward-compatible translation hook
    └── locales/
        ├── es.json           # Spanish translations
        ├── en.json           # English translations
        └── pt.json           # Portuguese translations
```

### Translation File Structure

Each locale JSON file follows this structure:

```json
{
  "nav": {
    "home": "Home",
    "downloads": "Downloads",
    "info": "Info",
    "ranking": "Ranking",
    "donate": "Donate",
    "back": "Back"
  },
  "home": {
    "welcome": "Welcome",
    "desc": "Server description here"
  },
  "features": {
    "title": "Server Information",
    "general": {
      "title": "General",
      "chronicle": "Chronicle: Interlude",
      "rates": "Rates: EXP/SP/Adena x10"
    }
  }
}
```

---

## Adding Translations

### Step 1: Locate the Translation Files

Translation files are located in:
- `src/i18n/locales/es.json` - Spanish
- `src/i18n/locales/en.json` - English
- `src/i18n/locales/pt.json` - Portuguese

### Step 2: Add Your Translation Key

Add the same key to ALL three files:

**es.json:**
```json
{
  "mySection": {
    "myKey": "Mi texto en español"
  }
}
```

**en.json:**
```json
{
  "mySection": {
    "myKey": "My text in English"
  }
}
```

**pt.json:**
```json
{
  "mySection": {
    "myKey": "Meu texto em português"
  }
}
```

### Step 3: Use in Component

```tsx
import { useTranslation } from 'react-i18next';

function MyComponent() {
  const { t } = useTranslation();
  
  return <h1>{t('mySection.myKey')}</h1>;
}
```

---

## Using Translations

### Method 1: useTranslation Hook (Recommended)

```tsx
import { useTranslation } from 'react-i18next';

function MyComponent() {
  const { t, i18n } = useTranslation();
  
  // Get translation
  const text = t('nav.home');
  
  // Change language
  const switchToEnglish = () => i18n.changeLanguage('en');
  
  // Get current language
  const currentLang = i18n.language;
  
  return (
    <div>
      <h1>{t('home.welcome')}</h1>
      <p>{t('home.desc')}</p>
      <button onClick={switchToEnglish}>English</button>
    </div>
  );
}
```

### Method 2: Nested Keys

```tsx
// Access nested translations
t('features.general.chronicle')  // "Chronicle: Interlude"
t('features.enchant.safe')       // "Safe: +3"
```

### Method 3: Arrays

For array translations (like premium items):

```json
{
  "features": {
    "premium": {
      "items": ["Drop: 1.5x", "Adena: 1.5x", "EXP/SP: 1.5x"]
    }
  }
}
```

```tsx
const items = t('features.premium.items', { returnObjects: true }) as string[];
items.map(item => <li>{item}</li>);
```

---

## Adding New Language

### Step 1: Create Locale File

Create `src/i18n/locales/[code].json` (e.g., `fr.json` for French):

```json
{
  "nav": {
    "home": "Accueil",
    "downloads": "Téléchargements"
  }
}
```

### Step 2: Update i18n Configuration

Edit `src/i18n/index.ts`:

```typescript
// Import the new locale
import fr from './locales/fr.json';

// Add to languages object
export const languages = {
  es: { nativeName: 'Español', flag: '🇪🇸' },
  en: { nativeName: 'English', flag: '🇺🇸' },
  pt: { nativeName: 'Português', flag: '🇧🇷' },
  fr: { nativeName: 'Français', flag: '🇫🇷' }  // NEW
} as const;

// Add to resources
i18n.init({
  resources: {
    es: { translation: es },
    en: { translation: en },
    pt: { translation: pt },
    fr: { translation: fr }  // NEW
  }
});
```

### Step 3: Add Language Selector Button

In `Navigation.tsx` or language selector component, add the new language button.

---

## AI Prompts

### Prompt: Change Server Name in All Languages

```
Update the server name placeholder [SERVER_NAME] to "[YOUR_SERVER_NAME]" in all translation files:
- src/i18n/locales/es.json
- src/i18n/locales/en.json  
- src/i18n/locales/pt.json

Search for "[SERVER_NAME]" and replace with the actual server name.
```

### Prompt: Update Server Rates

```
Update the server rates in all translation files. Change the rates from x10 to x[NEW_RATE].

Files to modify:
- src/i18n/locales/es.json: features.general.rates
- src/i18n/locales/en.json: features.general.rates
- src/i18n/locales/pt.json: features.general.rates

Also update home.desc in all files to reflect the new rate.
```

### Prompt: Add New Translation Section

```
Add a new translation section called "[SECTION_NAME]" with the following keys:
- key1: [ES_TEXT] / [EN_TEXT] / [PT_TEXT]
- key2: [ES_TEXT] / [EN_TEXT] / [PT_TEXT]

Add to all three locale files:
- src/i18n/locales/es.json
- src/i18n/locales/en.json
- src/i18n/locales/pt.json
```

### Prompt: Translate Existing Content

```
Translate the following content to all supported languages and add to the translation files:

English text: "[YOUR_TEXT]"

Add as key "[SECTION].[KEY]" in:
- es.json: Spanish translation
- en.json: English (original)
- pt.json: Portuguese translation
```

### Prompt: Add New Language Support

```
Add support for [LANGUAGE_NAME] ([LANGUAGE_CODE]) to the project:

1. Create src/i18n/locales/[CODE].json with all translations
2. Update src/i18n/index.ts to import and configure the new language
3. Add language selector button in Navigation.tsx
4. Test that all pages render correctly
```

---

## Translation Keys Reference

### Navigation (nav.*)
| Key | Description |
|-----|-------------|
| nav.home | Home page link |
| nav.downloads | Downloads page link |
| nav.info | Info page link |
| nav.ranking | Ranking page link |
| nav.donate | Donate page link |
| nav.back | Back button text |

### Home Page (home.*)
| Key | Description |
|-----|-------------|
| home.welcome | Welcome message |
| home.desc | Server description |
| home.newsTitle | News section title |
| home.readMore | Read more button |

### Server Features (features.*)
| Key | Description |
|-----|-------------|
| features.title | Section title |
| features.general.* | General server info |
| features.premium.* | Premium features |
| features.enchant.* | Enchant settings |
| features.equipment.* | Equipment info |
| features.clan.* | Clan system |
| features.olympiad.* | Olympiad settings |
| features.events.* | Events info |
| features.security.* | Security settings |

### Donations (donate.*)
| Key | Description |
|-----|-------------|
| donate.title | Section title |
| donate.subtitle | Section subtitle |
| donate.form.* | Form labels |
| donate.conversion.* | Currency names |

### Registration (register.*)
| Key | Description |
|-----|-------------|
| register.title | Form title |
| register.username | Username label |
| register.email | Email label |
| register.password | Password label |
| register.submit | Submit button |

---

## Troubleshooting

### Translation Not Showing

1. Check if the key exists in ALL locale files
2. Verify the key path is correct (use dots for nesting)
3. Clear browser localStorage and refresh

### Language Not Changing

1. Check browser console for errors
2. Verify i18n is initialized in index.tsx
3. Check localStorage for `i18nextLng` key

### Missing Translation Warning

If you see the key instead of translation:
1. The key doesn't exist in the current locale
2. There's a typo in the key path
3. The locale file has a syntax error (invalid JSON)

---

## Best Practices

1. **Always add keys to ALL locale files** - Missing keys show the key path instead of text
2. **Use descriptive key names** - `features.enchant.maxLevel` is better than `f.e.ml`
3. **Group related translations** - Keep navigation keys under `nav.*`, forms under `form.*`
4. **Test all languages** - Switch languages and verify all text displays correctly
5. **Keep translations short** - Long text may break layouts on mobile

---

## Quick Commands

```bash
# Install dependencies (required after cloning)
npm install

# Start development server
npm run dev

# Build for production
npm run build
```
