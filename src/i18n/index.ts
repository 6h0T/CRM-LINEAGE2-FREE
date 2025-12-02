import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';
import LanguageDetector from 'i18next-browser-languagedetector';

// Import translation files
import es from './locales/es.json';
import en from './locales/en.json';
import pt from './locales/pt.json';

// Available languages configuration
export const languages = {
  es: { nativeName: 'Español', flag: '🇪🇸' },
  en: { nativeName: 'English', flag: '🇺🇸' },
  pt: { nativeName: 'Português', flag: '🇧🇷' }
} as const;

export type Language = keyof typeof languages;
export const defaultLanguage: Language = 'es';
export const supportedLanguages = Object.keys(languages) as Language[];

// i18next configuration
i18n
  .use(LanguageDetector) // Detect user language
  .use(initReactI18next) // Pass i18n to react-i18next
  .init({
    resources: {
      es: { translation: es },
      en: { translation: en },
      pt: { translation: pt }
    },
    fallbackLng: defaultLanguage,
    supportedLngs: supportedLanguages,
    
    // Language detection options
    detection: {
      order: ['localStorage', 'navigator', 'htmlTag'],
      lookupLocalStorage: 'i18nextLng',
      caches: ['localStorage'],
    },

    interpolation: {
      escapeValue: false // React already escapes values
    },

    // Debug mode (disable in production)
    debug: false
  });

export default i18n;
