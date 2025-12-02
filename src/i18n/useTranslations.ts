import { useTranslation } from 'react-i18next';
import { Language } from './index';

/**
 * Custom hook that provides both the i18next t function
 * and a structured translation object for backward compatibility
 * 
 * Usage:
 * const { t, translations, lang, setLang } = useTranslations();
 * 
 * // Using t function (recommended for new code):
 * t('nav.home') // Returns translated string
 * 
 * // Using translations object (legacy compatibility):
 * translations.nav.home // Returns translated string
 */
export const useTranslations = () => {
  const { t, i18n } = useTranslation();

  const lang = i18n.language as Language;
  const setLang = (newLang: Language) => i18n.changeLanguage(newLang);

  // Create a proxy object that converts property access to t() calls
  const createTranslationProxy = (prefix: string = ''): any => {
    return new Proxy({}, {
      get: (_, prop: string) => {
        const key = prefix ? `${prefix}.${prop}` : prop;
        const value = t(key, { returnObjects: true });
        
        // If the value is an object, return another proxy
        if (typeof value === 'object' && value !== null && !Array.isArray(value)) {
          return createTranslationProxy(key);
        }
        
        // If it's an array, return it directly
        if (Array.isArray(value)) {
          return value;
        }
        
        // Otherwise return the translated string
        return t(key);
      }
    });
  };

  const translations = createTranslationProxy();

  return {
    t,
    i18n,
    translations,
    lang,
    setLang
  };
};

export default useTranslations;
