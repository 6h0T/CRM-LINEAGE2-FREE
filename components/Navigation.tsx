
import React, { useEffect } from 'react';
import { createPortal } from 'react-dom';
import { Page } from '../types';
import { Language } from '../src/i18n';
import { X, Globe } from 'lucide-react';

interface NavigationProps {
  activePage: Page;
  setPage: (page: Page) => void;
  t: {
    home: string;
    downloads: string;
    info: string;
    ranking: string;
    donate: string;
  };
  lang: Language;
  setLang: (lang: Language) => void;
  isOpen: boolean;
  onClose: () => void;
}

const NavItem: React.FC<{ 
  label: string; 
  active: boolean; 
  onClick: () => void;
  mobile?: boolean;
}> = ({ label, active, onClick, mobile }) => (
  <button
    onClick={onClick}
    className={`
      relative font-serif font-bold transition-all duration-300
      ${mobile 
        ? 'w-full text-right pr-4 text-xl py-4 border-b border-[#2e2418]/50 hover:bg-[#1a1612]' 
        : 'px-6 py-4 text-sm border-r border-[#4a3b2a] last:border-r-0 tracking-wide'
      }
      overflow-hidden
      ${active 
        ? 'text-[#ffd700] z-10' 
        : 'text-[#8b7d6b] hover:text-[#ffd700]'
      }
      ${!mobile && active ? 'bg-[#2a2115] shadow-[inset_0_0_20px_rgba(255,215,0,0.25)]' : ''}
      ${!mobile && !active ? 'bg-[#1a1612] hover:bg-[#2e2418] hover:shadow-[inset_0_0_15px_rgba(255,215,0,0.15)] hover:z-10' : ''}
      ${mobile && active ? 'bg-gradient-to-l from-[#2a2115] to-transparent border-r-4 border-r-[#ffd700]' : ''}
    `}
  >
    {label}
  </button>
);

const Navigation: React.FC<NavigationProps> = ({ activePage, setPage, t, lang, setLang, isOpen, onClose }) => {
  
  // Prevent scrolling when menu is open
  useEffect(() => {
    if (isOpen) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'unset';
    }
    return () => { document.body.style.overflow = 'unset'; };
  }, [isOpen]);

  const handleNavClick = (page: Page) => {
    setPage(page);
    onClose();
  };

  return (
    <>
        {/* Desktop Navigation (Standard Flow) */}
        <nav className="hidden lg:flex w-full border-y-2 border-[#5e4b35] bg-[#1a1612] shadow-2xl relative z-30 flex-wrap justify-center">
            <NavItem label={t.home} active={activePage === Page.HOME} onClick={() => setPage(Page.HOME)} />
            <NavItem label={t.downloads} active={activePage === Page.DOWNLOADS} onClick={() => setPage(Page.DOWNLOADS)} />
            <NavItem label={t.info} active={activePage === Page.INFO} onClick={() => setPage(Page.INFO)} />
            <NavItem label={t.ranking} active={activePage === Page.RANKING} onClick={() => setPage(Page.RANKING)} />
            <NavItem label={t.donate} active={activePage === Page.DONATE} onClick={() => setPage(Page.DONATE)} />
        </nav>

        {/* Mobile Drawer Navigation */}
        {isOpen && createPortal(
            <div className="fixed inset-0 z-[100] flex justify-end">
                
                {/* Backdrop */}
                <div 
                    className="absolute inset-0 bg-black/60 backdrop-blur-sm animate-fade-in"
                    onClick={onClose}
                />

                {/* Drawer Panel */}
                <div className="relative w-full max-w-xs h-full bg-[#0c0a09] border-l border-[#5e4b35] shadow-2xl flex flex-col animate-slide-in-right">
                    
                    {/* Close Button - Matches App.tsx Trigger Position & Style */}
                    <div className="absolute top-6 right-6 z-50">
                        <button 
                            onClick={onClose}
                            className="p-3 rounded-lg bg-[#0a0908]/80 border border-[#5e4b35] text-[#cbb085] hover:text-[#ffd700] hover:border-[#ffd700] backdrop-blur-md transition-all shadow-lg"
                        >
                            <X className="w-6 h-6" />
                        </button>
                    </div>

                    {/* Menu Content */}
                    <div className="flex-1 flex flex-col py-24 px-6 overflow-y-auto">
                        
                        <div className="flex flex-col space-y-2 mb-8">
                            <NavItem mobile label={t.home} active={activePage === Page.HOME} onClick={() => handleNavClick(Page.HOME)} />
                            <NavItem mobile label={t.downloads} active={activePage === Page.DOWNLOADS} onClick={() => handleNavClick(Page.DOWNLOADS)} />
                            <NavItem mobile label={t.info} active={activePage === Page.INFO} onClick={() => handleNavClick(Page.INFO)} />
                            <NavItem mobile label={t.ranking} active={activePage === Page.RANKING} onClick={() => handleNavClick(Page.RANKING)} />
                            <NavItem mobile label={t.donate} active={activePage === Page.DONATE} onClick={() => handleNavClick(Page.DONATE)} />
                        </div>

                        {/* Language Selector for Mobile */}
                        <div className="mt-auto flex flex-col items-center gap-4 pt-8 border-t border-[#2e2418]">
                            <div className="flex items-center gap-2 text-[#5e4b35]">
                                <Globe className="w-4 h-4" />
                                <span className="text-xs uppercase tracking-widest">Language</span>
                            </div>
                            <div className="flex gap-3 w-full justify-center">
                                <button 
                                    onClick={() => setLang('es')}
                                    className={`flex-1 py-2 border ${lang === 'es' ? 'border-[#ffd700] text-[#ffd700] bg-[#2a2115]' : 'border-[#2e2418] text-[#8b7d6b] bg-[#0a0908]'} transition-all font-bold text-sm uppercase tracking-widest`}
                                >
                                    ES
                                </button>
                                <button 
                                    onClick={() => setLang('en')}
                                    className={`flex-1 py-2 border ${lang === 'en' ? 'border-[#ffd700] text-[#ffd700] bg-[#2a2115]' : 'border-[#2e2418] text-[#8b7d6b] bg-[#0a0908]'} transition-all font-bold text-sm uppercase tracking-widest`}
                                >
                                    EN
                                </button>
                                <button 
                                    onClick={() => setLang('pt')}
                                    className={`flex-1 py-2 border ${lang === 'pt' ? 'border-[#ffd700] text-[#ffd700] bg-[#2a2115]' : 'border-[#2e2418] text-[#8b7d6b] bg-[#0a0908]'} transition-all font-bold text-sm uppercase tracking-widest`}
                                >
                                    PT
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>,
            document.body
        )}
    </>
  );
};

export default Navigation;
