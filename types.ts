
export interface ServerRate {
  label: string;
  value: string;
}

export interface NewsItem {
  id: number;
  title: string;
  date: string;
  author: string;
  content: string;
}

export interface GroundingSource {
  web?: {
    uri: string;
    title: string;
  };
}

export enum Page {
  HOME = 'HOME',
  DOWNLOADS = 'DOWNLOADS',
  INFO = 'INFO',
  RANKING = 'RANKING',
  DONATE = 'DONATE',
  SKINS = 'SKINS',
  NEWS_DETAIL = 'NEWS_DETAIL',
  ANIMATIONS = 'ANIMATIONS',
  REGISTER = 'REGISTER'
}

// Language type is now exported from src/i18n/index.ts