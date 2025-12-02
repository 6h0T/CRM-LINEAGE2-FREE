import { useTranslation } from 'react-i18next';
import { languages, Language, supportedLanguages } from './index';

/**
 * Custom hook for language management
 * Provides translation function and language switching utilities
 */
export const useLanguage = () => {
  const { t, i18n } = useTranslation();

  const currentLanguage = i18n.language as Language;

  const changeLanguage = (lang: Language) => {
    if (supportedLanguages.includes(lang)) {
      i18n.changeLanguage(lang);
    }
  };

  return {
    t,
    i18n,
    currentLanguage,
    changeLanguage,
    languages,
    supportedLanguages
  };
};

export default useLanguage;
