
import React, { useState, useRef, useEffect } from 'react';
import { useTranslation } from 'react-i18next';
import Navigation from './components/Navigation';
import FeaturesList from './components/FeaturesList';
import DonationPanel from './components/DonationPanel';
import OlympiadRanking from './components/OlympiadRanking';
import { Page, NewsItem } from './types';
import { Language } from './src/i18n';
import { Shield, Download, Globe, Server, Cloud, Video, X, Play, Eye, ArrowLeft, Zap, Users, Sword, Hammer, Film, UserPlus, Menu, Facebook } from 'lucide-react';

// Helper Component for Hover Video Effect (Cards)
const HoverVideo: React.FC = () => (
    <div className="absolute inset-0 z-0 overflow-hidden opacity-0 group-hover:opacity-40 transition-opacity duration-700 pointer-events-none mix-blend-screen">
        <video
            src="/bgcards.mp4"
            autoPlay
            muted
            loop
            playsInline
            className="w-full h-full object-cover scale-110"
        />
    </div>
);

const DiscordIcon: React.FC<{ className?: string }> = ({ className }) => (
    <svg className={className} viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
        <path d="M20.317 4.3698a19.7913 19.7913 0 00-4.8851-1.5152.0741.0741 0 00-.0785.0371c-.211.3753-.4447.772-.6083 1.1588a18.2532 18.2532 0 00-5.4964 0 12.6164 12.6164 0 00-.6173-1.1588.077.077 0 00-.0785-.0371 19.7363 19.7363 0 00-4.8852 1.5151.0699.0699 0 00-.0321.0277C.5334 9.0458-.319 13.5799.0992 18.0578a.0824.0824 0 00.0312.0561c2.0528 1.5076 4.0413 2.4228 5.9929 3.0294a.0777.0777 0 00.0842-.0276c.4616-.6304.8731-1.2952 1.226-1.9942a.076.076 0 00-.0416-.1057c-.6528-.2476-1.2743-.5495-1.8722-.8923a.077.077 0 01-.0076-.1277c.1432-.1064.2868-.2132.4233-.3219a.0757.0757 0 01.0792-.0115c3.9251 1.7931 8.18 1.7931 12.0614 0a.0739.0739 0 01.0792.0115c.1365.1087.2801.2155.4233.3219a.077.077 0 01-.0076.1277 12.2988 12.2988 0 01-1.8734.8923.0766.0766 0 00-.0408.1067c.3604.698.7719 1.3628 1.225 1.9932a.076.076 0 00.0842.0286c1.961-.6067 3.9495-1.5219 6.0023-3.0294a.077.077 0 00.0313-.0551c.5004-5.177-.8382-9.6739-3.5485-13.6604a.061.061 0 00-.0312-.0286zM8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.9555 2.4189-2.1569 2.4189zm7.9748 0c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9554-2.4189 2.1569-2.4189 1.2108 0 2.1757 1.0952 2.1568 2.419 0 1.3332-.946 2.4189-2.1568 2.4189Z"/>
    </svg>
);

// Helper Component for Header Background Video (Always visible, low opacity)
const HeaderVideo: React.FC = () => {
    const videoRef = useRef<HTMLVideoElement>(null);

    useEffect(() => {
        if (videoRef.current) {
            videoRef.current.play().catch(e => console.log("Autoplay prevented:", e));
        }
    }, []);

    return (
        <div className="absolute inset-0 z-0 overflow-hidden opacity-30 pointer-events-none mix-blend-screen">
            <video
                ref={videoRef}
                src="/bgheader.mp4"
                autoPlay
                muted
                loop
                playsInline
                className="w-full h-full object-cover scale-150"
            />
        </div>
    );
};

// Site Background Component: Holds the static image for the bottom/global background
const SiteBackground: React.FC = () => {
    return (
        <div className="fixed inset-0 w-full h-full -z-50 bg-[#050403]">
             {/* Bottom Layer: Static Image */}
             <div 
                className="absolute inset-0 w-full h-full bg-cover bg-center bg-no-repeat opacity-50 z-[-10]"
                style={{ 
                    backgroundImage: 'url("https://w.wallhaven.cc/full/k7/wallhaven-k7yqxd.jpg")',
                    backgroundAttachment: 'fixed'
                }}
             ></div>
             <div className="absolute inset-0 bg-gradient-to-t from-[#050403] via-transparent to-transparent opacity-80"></div>
        </div>
    );
};


const SidebarItem: React.FC<{ title: string; children: React.ReactNode }> = ({ title, children }) => (
  <div className="bg-[#12100e] border border-[#3d3122] mb-6 shadow-lg">
    <div className="relative bg-[#1a1612] px-4 py-2 border-b border-[#2e2418] overflow-hidden">
      <HeaderVideo />
      <h4 className="relative z-10 text-[#cbb085] font-serif text-sm font-bold uppercase tracking-wider">{title}</h4>
    </div>
    <div className="p-4">
      {children}
    </div>
  </div>
);

// Fixed Bottom-Left Floating Stream Widget
const FloatingStreamWidget: React.FC<{ t: any }> = ({ t }) => {
  const [isOpen, setIsOpen] = useState(false);

  return (
    <div className="fixed bottom-6 left-6 z-50 flex flex-col items-start gap-3">
        {isOpen && (
            <div className="bg-[#12100e] border border-[#3d3122] p-3 rounded-sm shadow-[0_0_30px_rgba(0,0,0,0.9)] w-[320px] animate-fade-in-up origin-bottom-left">
                <div className="flex justify-between items-center border-b border-[#2e2418] pb-2 mb-3">
                    <h3 className="text-[#cbb085] font-serif text-sm tracking-wider uppercase flex items-center gap-2">
                        <span className="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span> {t.subtitle}
                    </h3>
                    <button onClick={() => setIsOpen(false)} className="text-[#5e4b35] hover:text-[#cbb085]">
                        <X className="w-4 h-4" />
                    </button>
                </div>
                
                <div className="space-y-3 max-h-[400px] overflow-y-auto scrollbar-thin">
                    {/* Twitch Stream */}
                    <div className="bg-black border border-[#2e2418] relative group">
                        <div className="aspect-video">
                             <iframe className="w-full h-full" src="https://player.twitch.tv/?channel=twitch&parent=localhost" frameBorder="0" allowFullScreen></iframe>
                        </div>
                        <div className="p-2 bg-[#1a1612]">
                            <span className="text-[#8b7d6b] text-xs uppercase font-bold">Twitch Stream</span>
                        </div>
                    </div>

                    {/* Kick Stream */}
                    <div className="bg-black border border-[#2e2418] relative group">
                         <div className="aspect-video">
                            <iframe className="w-full h-full" src="https://player.kick.com/kick" frameBorder="0" allowFullScreen></iframe>
                         </div>
                         <div className="p-2 bg-[#1a1612]">
                            <span className="text-[#8b7d6b] text-xs uppercase font-bold">Kick Stream</span>
                        </div>
                    </div>
                </div>
            </div>
        )}

        <button 
            onClick={() => setIsOpen(!isOpen)}
            className={`
                flex items-center gap-2 px-5 py-3 rounded-full font-bold uppercase text-xs tracking-widest shadow-2xl transition-all duration-300 border
                ${isOpen 
                    ? 'bg-[#3d3122] text-[#cbb085] border-[#ffd700]' 
                    : 'bg-[#12100e] text-[#cbb085] border-[#5e4b35] hover:bg-[#1a1612] hover:border-[#cbb085] hover:scale-105'
                }
            `}
        >
            <Video className={`w-4 h-4 ${isOpen ? 'text-[#ffd700]' : ''}`} />
            {t.title}
        </button>
    </div>
  );
};

// Sub-component for Unique Features (Above News)
const UniqueFeatures: React.FC<{ t: any, onOpenSkins: () => void, onOpenAnims: () => void }> = ({ t, onOpenSkins, onOpenAnims }) => (
    <div className="mb-8">
         <div className="flex items-center gap-2 border-b border-[#2e2418] pb-2 mb-4">
            <Eye className="text-[#5e4b35]" />
            <h2 className="text-xl text-[#cbb085] font-serif">{t.title}</h2>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            
            {/* Skins Card */}
            <div 
                onClick={onOpenSkins}
                className="bg-[#0a0908] border border-[#5e4b35] p-6 cursor-pointer hover:bg-[#1a1612] hover:border-[#ffd700] transition-all group relative overflow-hidden"
            >
                <HoverVideo />
                <div className="absolute inset-0 bg-gradient-to-r from-black via-transparent to-transparent opacity-60 z-10 pointer-events-none"></div>
                
                <div className="relative z-20 flex justify-between items-center h-full">
                    <div>
                        <h3 className="text-[#ffd700] font-serif text-xl mb-2 group-hover:scale-105 transition-transform origin-left">{t.skinsTitle}</h3>
                        <p className="text-[#8b7d6b] text-xs max-w-[150px]">{t.skinsDesc}</p>
                    </div>
                    <div className="bg-[#3d3122] text-[#cbb085] px-3 py-1 text-[10px] font-bold uppercase border border-[#5e4b35] group-hover:bg-[#ffd700] group-hover:text-black transition-colors">
                        {t.viewGallery}
                    </div>
                </div>
            </div>

            {/* Character Animations Card */}
            <div 
                onClick={onOpenAnims}
                className="bg-[#0a0908] border border-[#5e4b35] p-6 cursor-pointer hover:bg-[#1a1612] hover:border-[#ffd700] transition-all group relative overflow-hidden"
            >
                <HoverVideo />
                <div className="absolute inset-0 bg-gradient-to-r from-black via-transparent to-transparent opacity-60 z-10 pointer-events-none"></div>
                
                <div className="relative z-20 flex justify-between items-center h-full">
                    <div>
                        <h3 className="text-[#ffd700] font-serif text-xl mb-2 group-hover:scale-105 transition-transform origin-left flex items-center gap-2">
                           {t.animTitle}
                        </h3>
                        <p className="text-[#8b7d6b] text-xs max-w-[150px]">{t.animDesc}</p>
                    </div>
                    {/* Updated button to match Skins style (Gold/Brown) */}
                    <div className="bg-[#3d3122] text-[#cbb085] px-3 py-1 text-[10px] font-bold uppercase border border-[#5e4b35] group-hover:bg-[#ffd700] group-hover:text-black transition-colors">
                        {t.viewAnim}
                    </div>
                </div>
            </div>

        </div>
    </div>
);

// Sub-component: Animations Gallery
const AnimationsGallery: React.FC<{ t: any, onBack: () => void }> = ({ t, onBack }) => {
    const [selectedRace, setSelectedRace] = useState<string | null>(null);

    // Corrected path to access races: t.animations.races
    const races = [
        { id: 'human', title: t.animations.races.human, icon: Users },
        { id: 'elf', title: t.animations.races.elf, icon: Shield },
        { id: 'darkElf', title: t.animations.races.darkElf, icon: Sword },
        { id: 'orc', title: t.animations.races.orc, icon: Zap },
        { id: 'dwarf', title: t.animations.races.dwarf, icon: Hammer },
    ];

    return (
        <div className="animate-fade-in bg-[#12100e] border border-[#3d3122] p-6">
             <button onClick={onBack} className="mb-4 flex items-center gap-2 text-[#8b7d6b] hover:text-[#cbb085] uppercase text-xs font-bold tracking-wider">
                <ArrowLeft className="w-4 h-4" /> {t.nav?.back || "Back"}
            </button>

            <div className="flex items-center gap-3 mb-6 border-b border-[#2e2418] pb-4">
                {/* Updated icon and color to match theme */}
                <Film className="w-8 h-8 text-[#ffd700]" />
                <div>
                    <h2 className="text-[#cbb085] font-serif text-2xl">{t.animations.title}</h2>
                    <p className="text-[#8b7d6b] text-sm">{t.animations.subtitle}</p>
                </div>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                {races.map((race) => {
                    const Icon = race.icon;
                    return (
                        <div 
                            key={race.id}
                            onClick={() => setSelectedRace(race.title)}
                            className="bg-[#0a0908] border border-[#2e2418] p-6 cursor-pointer hover:border-[#ffd700] hover:shadow-[0_0_15px_rgba(255,215,0,0.2)] transition-all group relative overflow-hidden flex flex-col items-center text-center gap-4"
                        >
                             <div className="w-16 h-16 bg-[#1a1612] rounded-full flex items-center justify-center border border-[#5e4b35] group-hover:border-[#ffd700] transition-colors">
                                <Icon className="w-8 h-8 text-[#8b7d6b] group-hover:text-[#ffd700]" />
                             </div>
                             <h3 className="text-[#eecfa1] font-serif text-xl group-hover:text-[#ffd700] transition-colors">{race.title}</h3>
                             <div className="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <Play className="w-10 h-10 text-[#ffd700] fill-current" />
                             </div>
                        </div>
                    );
                })}
            </div>

            {/* Popup Modal for Animations */}
            {selectedRace && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4 animate-fade-in">
                    <div className="relative w-full max-w-4xl bg-[#12100e] border border-[#5e4b35] p-1 shadow-[0_0_50px_rgba(255,215,0,0.2)]">
                        <button 
                            onClick={() => setSelectedRace(null)}
                            className="absolute -top-10 right-0 text-[#cbb085] hover:text-white"
                        >
                            <X className="w-8 h-8" />
                        </button>
                        <div className="aspect-video bg-black">
                             {/* Placeholder video */}
                             <div className="w-full h-full flex items-center justify-center text-[#5e4b35] flex-col gap-4">
                                <Film className="w-16 h-16" />
                                <p className="uppercase tracking-widest">Animation Preview: {selectedRace}</p>
                             </div>
                        </div>
                        <div className="p-4 bg-[#0a0908]">
                            <h3 className="text-[#ffd700] font-serif text-xl">{selectedRace} Combat Animations</h3>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};

// Sub-component: Skins Gallery
const SkinsGallery: React.FC<{ t: any, onBack: () => void }> = ({ t, onBack }) => {
    const [selectedSkin, setSelectedSkin] = useState<number | null>(null);
    
    // Generate 16 mock items
    const skins = Array.from({ length: 16 }, (_, i) => ({ id: i + 1, title: `Custom Skin Set ${i + 1}` }));

    return (
        <div className="animate-fade-in bg-[#12100e] border border-[#3d3122] p-6">
             <button onClick={onBack} className="mb-4 flex items-center gap-2 text-[#8b7d6b] hover:text-[#cbb085] uppercase text-xs font-bold tracking-wider">
                <ArrowLeft className="w-4 h-4" /> {t.nav.back}
            </button>

            <div className="flex items-center gap-3 mb-6 border-b border-[#2e2418] pb-4">
                <Shield className="w-8 h-8 text-[#ffd700]" />
                <div>
                    <h2 className="text-[#cbb085] font-serif text-2xl">{t.skins.title}</h2>
                    <p className="text-[#8b7d6b] text-sm">{t.skins.subtitle}</p>
                </div>
            </div>

            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                {skins.map(skin => (
                    <div 
                        key={skin.id} 
                        onClick={() => setSelectedSkin(skin.id)}
                        className="aspect-square bg-[#0a0908] border border-[#2e2418] p-2 cursor-pointer hover:border-[#ffd700] hover:shadow-[0_0_15px_rgba(255,215,0,0.2)] transition-all group relative overflow-hidden"
                    >
                        <div className="w-full h-full bg-[#1a1612] flex items-center justify-center flex-col gap-2">
                            <Shield className="w-10 h-10 text-[#5e4b35] group-hover:text-[#ffd700] transition-colors" />
                            <span className="text-[#8b7d6b] text-xs font-bold uppercase text-center group-hover:text-[#cbb085]">{skin.title}</span>
                        </div>
                        <div className="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <Play className="w-8 h-8 text-[#ffd700] fill-current" />
                        </div>
                    </div>
                ))}
            </div>

            {/* Popup Modal */}
            {selectedSkin && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4 animate-fade-in">
                    <div className="relative w-full max-w-4xl bg-[#12100e] border border-[#5e4b35] p-1 shadow-[0_0_50px_rgba(255,215,0,0.2)]">
                        <button 
                            onClick={() => setSelectedSkin(null)}
                            className="absolute -top-10 right-0 text-[#cbb085] hover:text-white"
                        >
                            <X className="w-8 h-8" />
                        </button>
                        <div className="aspect-video bg-black">
                             {/* Placeholder video */}
                             <div className="w-full h-full flex items-center justify-center text-[#5e4b35] flex-col gap-4">
                                <Play className="w-16 h-16" />
                                <p>Video Preview for Skin {selectedSkin}</p>
                             </div>
                        </div>
                        <div className="p-4 bg-[#0a0908]">
                            <h3 className="text-[#ffd700] font-serif text-xl">Custom Skin Set {selectedSkin}</h3>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
};

// Sub-component: Registration Form
const RegistrationForm: React.FC<{ t: any, onBack: () => void }> = ({ t, onBack }) => {
    const [username, setUsername] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [confirmPass, setConfirmPass] = useState('');
    const [error, setError] = useState('');
    const [success, setSuccess] = useState(false);

    const handleSubmit = () => {
        setError('');
        if (password !== confirmPass) {
            setError(t.register.errorMatch);
            return;
        }
        // Logic to create account would go here
        setSuccess(true);
    };

    if (success) {
         return (
            <div className="animate-fade-in bg-[#12100e] border border-[#3d3122] p-8 text-center min-h-[400px] flex flex-col items-center justify-center">
                <div className="w-16 h-16 bg-[#1a1612] rounded-full flex items-center justify-center border border-[#ffd700] mb-6">
                    <UserPlus className="w-8 h-8 text-[#ffd700]" />
                </div>
                <h2 className="text-[#cbb085] font-serif text-3xl mb-2">{t.register.success}</h2>
                <p className="text-[#8b7d6b] mb-8">{t.register.subtitle}</p>
                <button onClick={onBack} className="px-8 py-3 bg-[#3d3122] text-[#cbb085] border border-[#5e4b35] hover:border-[#ffd700] uppercase font-bold tracking-widest">
                    {t.nav.back}
                </button>
            </div>
         );
    }

    return (
        <div className="animate-fade-in bg-[#12100e] border border-[#3d3122] p-6 md:p-10">
             <button onClick={onBack} className="mb-6 flex items-center gap-2 text-[#8b7d6b] hover:text-[#cbb085] uppercase text-xs font-bold tracking-wider">
                <ArrowLeft className="w-4 h-4" /> {t.nav.back}
            </button>

            <div className="flex flex-col items-center mb-8">
                <div className="w-12 h-12 bg-[#1a1612] rounded-full flex items-center justify-center border border-[#5e4b35] mb-3">
                     <UserPlus className="w-6 h-6 text-[#cbb085]" />
                </div>
                <h2 className="text-[#cbb085] font-serif text-3xl">{t.register.title}</h2>
                <p className="text-[#8b7d6b] text-sm uppercase tracking-widest">{t.register.subtitle}</p>
            </div>

            <div className="max-w-lg mx-auto space-y-5">
                <div>
                    <label className="block text-[#8b7d6b] text-xs font-bold uppercase mb-2">{t.register.username}</label>
                    <input 
                        type="text" 
                        value={username}
                        onChange={(e) => setUsername(e.target.value)}
                        className="w-full bg-[#0a0908] border border-[#2e2418] text-[#eecfa1] p-3 focus:border-[#ffd700] outline-none transition-colors"
                    />
                </div>

                <div>
                    <label className="block text-[#8b7d6b] text-xs font-bold uppercase mb-2">{t.register.email}</label>
                    <input 
                        type="email" 
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        className="w-full bg-[#0a0908] border border-[#2e2418] text-[#eecfa1] p-3 focus:border-[#ffd700] outline-none transition-colors"
                    />
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label className="block text-[#8b7d6b] text-xs font-bold uppercase mb-2">{t.register.password}</label>
                        <input 
                            type="password" 
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            className="w-full bg-[#0a0908] border border-[#2e2418] text-[#eecfa1] p-3 focus:border-[#ffd700] outline-none transition-colors"
                        />
                    </div>
                    <div>
                        <label className="block text-[#8b7d6b] text-xs font-bold uppercase mb-2">{t.register.confirmPass}</label>
                        <input 
                            type="password" 
                            value={confirmPass}
                            onChange={(e) => setConfirmPass(e.target.value)}
                            className="w-full bg-[#0a0908] border border-[#2e2418] text-[#eecfa1] p-3 focus:border-[#ffd700] outline-none transition-colors"
                        />
                    </div>
                </div>

                {error && (
                    <p className="text-red-500 text-xs text-center font-bold uppercase animate-pulse">{error}</p>
                )}

                <button 
                    onClick={handleSubmit}
                    className="w-full mt-6 bg-[#1a1612] text-[#ffd700] py-4 text-sm font-bold border border-[#ffd700] shadow-[0_0_15px_rgba(255,215,0,0.2)] hover:shadow-[0_0_25px_rgba(255,215,0,0.4)] hover:bg-[#2a2115] transition-all duration-300 uppercase tracking-widest"
                >
                    {t.register.submit}
                </button>
                
                <div className="text-center pt-4 border-t border-[#2e2418]">
                    <p className="text-[#5e4b35] text-xs">
                        {t.register.terms}
                    </p>
                </div>
            </div>
        </div>
    );
};


// Sub-component: News Detail View
const NewsDetail: React.FC<{ item: NewsItem, onBack: () => void, t: any }> = ({ item, onBack, t }) => (
    <div className="animate-fade-in bg-[#12100e] border border-[#3d3122] p-8">
        <button onClick={onBack} className="mb-6 flex items-center gap-2 text-[#8b7d6b] hover:text-[#cbb085] uppercase text-xs font-bold tracking-wider">
            <ArrowLeft className="w-4 h-4" /> {t.back}
        </button>
        
        <div className="border-b border-[#5e4b35] pb-4 mb-6">
            <h1 className="text-[#ffd700] font-serif text-3xl mb-2">{item.title}</h1>
            <div className="flex gap-4 text-xs text-[#8b7d6b] uppercase tracking-widest">
                <span>{item.date}</span>
                <span>By {item.author}</span>
            </div>
        </div>

        <div className="prose prose-invert max-w-none text-[#a89f91] leading-loose font-serif">
            <p>{item.content}</p>
            {/* Simulated extra content */}
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>
    </div>
);

const App: React.FC = () => {
  const { t, i18n } = useTranslation();
  const [page, setPageInternal] = useState<Page>(Page.HOME);
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [selectedNews, setSelectedNews] = useState<NewsItem | null>(null);
  const [isLoading, setIsLoading] = useState(true);
  const [loadingMsgIndex, setLoadingMsgIndex] = useState(0);
  const contentRef = useRef<HTMLElement>(null);

  const lang = i18n.language as Language;
  const setLang = (newLang: Language) => i18n.changeLanguage(newLang);

  const loadingMessages = [
      t('loading.interface'),
      t('loading.optimizing'),
      t('loading.serverName')
  ];

  // Preloader Logic
  useEffect(() => {
    const handleLoad = () => {
        // Ensure we show all messages before finishing (optional, but good for UX if fast load)
        // For now, we stick to the 1.5s minimum delay to allow the sequence to play at least once
        setTimeout(() => setIsLoading(false), 2000); 
    };

    // Message Rotation Logic
    const msgInterval = setInterval(() => {
        setLoadingMsgIndex(prev => (prev + 1) % loadingMessages.length);
    }, 600);

    if (document.readyState === 'complete') {
      handleLoad();
    } else {
      window.addEventListener('load', handleLoad);
    }

    return () => {
        window.removeEventListener('load', handleLoad);
        clearInterval(msgInterval);
    };
  }, []);

  const setPage = (newPage: Page) => {
      setPageInternal(newPage);
      // Scroll to the content container to ensure user sees the selected section immediately
      // This is especially important on mobile to skip the sidebar
      setTimeout(() => {
        contentRef.current?.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }, 50);
  };

  // Create translation objects for components that expect object-style translations
  // This provides backward compatibility with existing component props
  const translations = {
    nav: {
      home: t('nav.home'),
      downloads: t('nav.downloads'),
      info: t('nav.info'),
      ranking: t('nav.ranking'),
      donate: t('nav.donate'),
      back: t('nav.back')
    },
    streams: {
      title: t('streams.title'),
      subtitle: t('streams.subtitle'),
      watch: t('streams.watch')
    },
    uniqueFeatures: {
      title: t('uniqueFeatures.title'),
      skinsTitle: t('uniqueFeatures.skinsTitle'),
      skinsDesc: t('uniqueFeatures.skinsDesc'),
      animTitle: t('uniqueFeatures.animTitle'),
      animDesc: t('uniqueFeatures.animDesc'),
      viewGallery: t('uniqueFeatures.viewGallery'),
      viewAnim: t('uniqueFeatures.viewAnim')
    },
    skins: {
      title: t('skins.title'),
      subtitle: t('skins.subtitle')
    },
    animations: {
      title: t('animations.title'),
      subtitle: t('animations.subtitle'),
      races: {
        human: t('animations.races.human'),
        elf: t('animations.races.elf'),
        darkElf: t('animations.races.darkElf'),
        orc: t('animations.races.orc'),
        dwarf: t('animations.races.dwarf')
      }
    },
    register: {
      title: t('register.title'),
      subtitle: t('register.subtitle'),
      username: t('register.username'),
      email: t('register.email'),
      password: t('register.password'),
      confirmPass: t('register.confirmPass'),
      submit: t('register.submit'),
      terms: t('register.terms'),
      success: t('register.success'),
      errorMatch: t('register.errorMatch')
    },
    home: {
      welcome: t('home.welcome'),
      desc: t('home.desc'),
      newsTitle: t('home.newsTitle'),
      readMore: t('home.readMore')
    },
    features: t('features', { returnObjects: true }) as any,
    ranking: {
      title: t('ranking.title'),
      subtitle: t('ranking.subtitle'),
      class: t('ranking.class'),
      player: t('ranking.player'),
      points: t('ranking.points'),
      status: t('ranking.status')
    },
    downloads: {
      title: t('downloads.title'),
      subtitle: t('downloads.subtitle'),
      clientTitle: t('downloads.clientTitle'),
      clientDesc: t('downloads.clientDesc'),
      instructions: t('downloads.instructions'),
      step1: t('downloads.step1'),
      step2: t('downloads.step2'),
      troubleshooting: t('downloads.troubleshooting'),
      troubleDesc: t('downloads.troubleDesc')
    },
    donate: t('donate', { returnObjects: true }) as any,
    sidebar: {
      status: t('sidebar.status'),
      online: t('sidebar.online'),
      players: t('sidebar.players'),
      vote: t('sidebar.vote'),
      login: t('sidebar.login'),
      user: t('sidebar.user'),
      pass: t('sidebar.pass'),
      enter: t('sidebar.enter'),
      admin: t('sidebar.admin'),
      register: t('sidebar.register')
    },
    footer: t('footer')
  };

  // Get translated news from i18n
  const news: NewsItem[] = t('news.items', { returnObjects: true }) as NewsItem[];

  const handleNewsClick = (item: NewsItem) => {
      setSelectedNews(item);
      setPage(Page.NEWS_DETAIL);
  };

  return (
    <div className="min-h-screen text-[#a89f91] pb-12 flex flex-col items-center relative">
      
      {/* Preloader Overlay */}
      <div className={`fixed inset-0 z-[9999] bg-[#050403] flex flex-col items-center justify-center transition-all duration-1000 ease-in-out transform ${isLoading ? 'opacity-100 scale-100' : 'opacity-0 scale-150 pointer-events-none'}`}>
          <div className="relative mb-8">
              {/* Glow effect behind flame */}
              <div className="absolute inset-0 bg-orange-600 blur-[40px] opacity-40 animate-pulse"></div>
              <img 
                src="/flame-svgrepo-com.svg" 
                alt="Loading..." 
                className="w-24 h-24 relative z-10 animate-pulse drop-shadow-[0_0_25px_rgba(255,100,0,0.8)]" 
              />
          </div>
          
          {/* Cycling Text */}
          <div className="h-8 flex items-center justify-center overflow-hidden">
             <p className="text-[#cbb085] font-serif font-bold tracking-[0.2em] uppercase text-sm animate-fade-in key={loadingMsgIndex}">
                {loadingMessages[loadingMsgIndex]}
             </p>
          </div>
          
          {/* Progress Bar Line */}
          <div className="w-48 h-[2px] bg-[#2e2418] mt-4 rounded-full overflow-hidden">
              <div className="h-full bg-[#ffd700] animate-[progress_2s_infinite_linear] w-full"></div>
          </div>
      </div>

      <SiteBackground />

      {/* Floating Stream CTA */}
      <FloatingStreamWidget t={translations.streams} />

      {/* Mobile Menu Button - Positioned absolutely relative to the main container to sit at the top right of viewport/page */}
      {!isMenuOpen && (
        <div className="lg:hidden fixed top-6 right-6 z-50">
            <button 
                onClick={() => setIsMenuOpen(true)}
                className="p-3 rounded-lg bg-[#0a0908]/80 border border-[#5e4b35] text-[#cbb085] hover:text-[#ffd700] hover:border-[#ffd700] backdrop-blur-md transition-all shadow-lg"
                aria-label="Open Menu"
            >
                <Menu className="w-6 h-6" />
            </button>
        </div>
      )}

      {/* Header Image Area */}
      <header className="w-full h-[450px] relative flex flex-col justify-start items-center overflow-hidden">
        
        {/* Background Video Layer */}
        <div className="absolute inset-0 w-full h-full z-0">
            <video 
                src="/Firefly A medieval war camp simmers around a majestic castle, with flickering campfires casting danc.mp4"
                autoPlay
                muted
                loop
                playsInline
                className="w-full h-full object-cover object-[center_50%]"
                style={{ 
                    maskImage: 'linear-gradient(to bottom, black 70%, transparent 100%)',
                    WebkitMaskImage: 'linear-gradient(to bottom, black 70%, transparent 100%)',
                }}
            />
        </div>

        {/* Hero Logo */}
        <div className="absolute inset-0 z-10 flex items-center justify-center">
            <img 
                src="/lineage-ii-logo.png" 
                alt="Lineage II Logo" 
                className="w-[280px] max-w-[56%] h-auto drop-shadow-[0_0_30px_rgba(255,215,0,0.5)]"
            />
        </div>

        {/* Language Selector (Desktop Only) */}
        <div className="absolute top-4 right-4 z-30 hidden lg:flex items-center gap-2 bg-[#00000088] p-1 rounded border border-[#2e2418]">
            <Globe className="w-4 h-4 text-[#cbb085]" />
            <div className="flex text-xs font-bold text-[#8b7d6b]">
                <button 
                    onClick={() => setLang('es')} 
                    className={`px-2 py-1 hover:text-[#ffd700] ${lang === 'es' ? 'text-[#cbb085]' : ''}`}
                >ES</button>
                <span className="text-[#4a3b2a]">|</span>
                <button 
                    onClick={() => setLang('en')} 
                    className={`px-2 py-1 hover:text-[#ffd700] ${lang === 'en' ? 'text-[#cbb085]' : ''}`}
                >EN</button>
                <span className="text-[#4a3b2a]">|</span>
                <button 
                    onClick={() => setLang('pt')} 
                    className={`px-2 py-1 hover:text-[#ffd700] ${lang === 'pt' ? 'text-[#cbb085]' : ''}`}
                >PT</button>
            </div>
        </div>
      </header>

      {/* Main Container */}
      <div className="w-full max-w-[1400px] bg-[#0c0a09]/85 backdrop-blur-sm border-x border-[#2e2418] shadow-[0_0_50px_rgba(0,0,0,0.8)] min-h-[800px]">
        
        <Navigation 
            activePage={page} 
            setPage={setPage} 
            t={translations.nav} 
            lang={lang} 
            setLang={setLang} 
            isOpen={isMenuOpen} 
            onClose={() => setIsMenuOpen(false)}
        />

        <div className="flex flex-col lg:flex-row p-6 gap-6">
          
          {/* Sidebar Area (Left) */}
          <aside className="w-full lg:w-[300px] flex-shrink-0">
            
            <SidebarItem title={translations.sidebar.status}>
                <div className="space-y-3 text-sm">
                    <div className="flex justify-between items-center">
                        <span className="text-[#8b7d6b]">Login</span>
                        <span className="text-green-500 font-bold flex items-center gap-1"><span className="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> {translations.sidebar.online}</span>
                    </div>
                    <div className="flex justify-between items-center">
                        <span className="text-[#8b7d6b]">Game</span>
                        <span className="text-green-500 font-bold flex items-center gap-1"><span className="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> {translations.sidebar.online}</span>
                    </div>
                    <div className="border-t border-[#2e2418] pt-2 mt-2">
                        <div className="flex justify-between">
                            <span className="text-[#8b7d6b]">{translations.sidebar.players}</span>
                            <span className="text-[#cbb085]">854</span>
                        </div>
                    </div>
                </div>
            </SidebarItem>

            <SidebarItem title={translations.sidebar.login}>
                <div className="space-y-3">
                    <input type="text" placeholder={translations.sidebar.user} className="w-full bg-[#0a0908] border border-[#2e2418] p-2 text-sm text-[#cbb085] focus:border-[#5e4b35] outline-none" />
                    <input type="password" placeholder={translations.sidebar.pass} className="w-full bg-[#0a0908] border border-[#2e2418] p-2 text-sm text-[#cbb085] focus:border-[#5e4b35] outline-none" />
                    <button className="w-full bg-[#3d3122] hover:bg-[#4d3e2b] text-[#cbb085] py-2 text-sm font-bold border border-[#5e4b35]">
                        {translations.sidebar.enter}
                    </button>
                    
                    {/* Register Button with Glow */}
                    <button 
                        onClick={() => setPage(Page.REGISTER)}
                        className="w-full mt-2 bg-[#0a0908] text-[#ffd700] py-3 text-sm font-bold border border-[#ffd700] shadow-[0_0_15px_rgba(255,215,0,0.3)] hover:shadow-[0_0_25px_rgba(255,215,0,0.6)] hover:bg-[#1a1612] transition-all duration-500 animate-pulse uppercase tracking-widest"
                    >
                        {translations.sidebar.register}
                    </button>
                </div>
            </SidebarItem>

            <SidebarItem title={translations.sidebar.vote}>
                <div className="grid grid-cols-1 gap-2">
                    <button className="bg-[#1a1612] hover:bg-[#2a2115] text-[#8b7d6b] hover:text-[#cbb085] py-2 border border-[#2e2418] transition-all text-xs font-bold uppercase">
                        Hopzone
                    </button>
                    <button className="bg-[#1a1612] hover:bg-[#2a2115] text-[#8b7d6b] hover:text-[#cbb085] py-2 border border-[#2e2418] transition-all text-xs font-bold uppercase">
                        TopZone
                    </button>
                    <button className="bg-[#1a1612] hover:bg-[#2a2115] text-[#8b7d6b] hover:text-[#cbb085] py-2 border border-[#2e2418] transition-all text-xs font-bold uppercase">
                        L2Network
                    </button>
                </div>
            </SidebarItem>

          </aside>

          {/* Main Content Area (Right) */}
          <main ref={contentRef} className="flex-1 scroll-mt-8">
            
            {page === Page.HOME && (
                <div className="space-y-8 animate-fade-in">
                    
                    {/* Added Unique Features Section */}
                    <UniqueFeatures t={translations.uniqueFeatures} onOpenSkins={() => setPage(Page.SKINS)} onOpenAnims={() => setPage(Page.ANIMATIONS)} />

                    {/* Server News List */}
                    <div className="space-y-4">
                        <div className="flex items-center gap-2 border-b border-[#2e2418] pb-2">
                            <Shield className="text-[#5e4b35]" />
                            <h2 className="text-xl text-[#cbb085] font-serif">{translations.home.newsTitle}</h2>
                        </div>
                        
                        {news.map(item => (
                            <div 
                                key={item.id} 
                                onClick={() => handleNewsClick(item)}
                                className="bg-[#12100e] border border-[#2e2418] p-4 hover:border-[#5e4b35] transition-colors group cursor-pointer relative overflow-hidden"
                            >
                                <HoverVideo />
                                <div className="relative z-10">
                                    <div className="flex justify-between items-start mb-2">
                                        <h3 className="text-[#eecfa1] font-bold group-hover:text-[#ffd700] transition-colors">{item.title}</h3>
                                        <span className="text-xs text-[#5e4b35] border border-[#2e2418] px-2 py-1 bg-black/50">{item.date}</span>
                                    </div>
                                    <p className="text-sm text-[#918671] leading-relaxed line-clamp-2">
                                        {item.content}
                                    </p>
                                    <span className="text-xs text-[#cbb085] mt-2 inline-block border-b border-transparent group-hover:border-[#cbb085] transition-all">
                                        {translations.home.readMore}
                                    </span>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            )}

            {/* Added Skins Gallery View */}
            {page === Page.SKINS && (
                <SkinsGallery t={translations} onBack={() => setPage(Page.HOME)} />
            )}

            {/* Added Animations Gallery View */}
            {page === Page.ANIMATIONS && (
                <AnimationsGallery t={translations} onBack={() => setPage(Page.HOME)} />
            )}

            {/* Added Registration Form View */}
            {page === Page.REGISTER && (
                <RegistrationForm t={translations} onBack={() => setPage(Page.HOME)} />
            )}

            {/* Added News Detail View */}
            {page === Page.NEWS_DETAIL && selectedNews && (
                <NewsDetail item={selectedNews} t={translations.nav} onBack={() => setPage(Page.HOME)} />
            )}

            {page === Page.INFO && (
                <FeaturesList t={translations.features} />
            )}

            {page === Page.RANKING && (
                <OlympiadRanking t={translations.ranking} />
            )}

            {page === Page.DOWNLOADS && (
                <div className="animate-fade-in py-8 bg-[#12100e] border border-[#2e2418] px-6">
                    
                    {/* Instructions Top */}
                    <div className="text-left bg-[#0a0908] p-6 border border-[#2e2418] text-sm text-[#918671] mb-8 shadow-inner rounded-sm">
                        <h4 className="text-[#cbb085] font-bold mb-3 uppercase tracking-wider border-b border-[#2e2418] pb-2">{translations.downloads.instructions}</h4>
                        <p className="mb-2">{translations.downloads.step1}</p>
                        <p>{translations.downloads.step2}</p>
                    </div>
                    
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        
                        {/* Left Column: Main Client Download */}
                        <div className="space-y-6">
                             <div className="bg-[#1a1612] border border-[#5e4b35] p-6 shadow-lg">
                                <div className="flex items-center gap-3 mb-4 border-b border-[#2e2418] pb-3">
                                    <Server className="w-6 h-6 text-[#ffd700]" />
                                    <h3 className="text-[#cbb085] font-serif text-xl leading-none">
                                        {translations.downloads.clientTitle}
                                    </h3>
                                </div>
                                <p className="text-xs text-[#8b7d6b] mb-6">{translations.downloads.clientDesc}</p>
                                
                                <div className="space-y-4">
                                    <a 
                                        href="#" // TODO: Add your download link
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="block w-full bg-[#3d3122] hover:bg-[#4d3e2b] text-[#cbb085] hover:text-[#ffd700] px-6 py-4 border border-[#5e4b35] hover:border-[#ffd700] font-bold text-sm uppercase text-center transition-all shadow-lg hover:shadow-[0_0_15px_rgba(255,215,0,0.2)] tracking-widest"
                                    >
                                        <span className="flex items-center justify-center gap-2">
                                            <Cloud className="w-5 h-5" /> Mediafire
                                        </span>
                                    </a>
                                    
                                    <a 
                                        href="https://drive.google.com/file/d/1VqDSs6Xc0h3vMAEtIvivWIi63P_ZFemX/view"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="block w-full bg-[#3d3122] hover:bg-[#4d3e2b] text-[#cbb085] hover:text-[#ffd700] px-6 py-4 border border-[#5e4b35] hover:border-[#ffd700] font-bold text-sm uppercase text-center transition-all shadow-lg hover:shadow-[0_0_15px_rgba(255,215,0,0.2)] tracking-widest"
                                    >
                                        <span className="flex items-center justify-center gap-2">
                                            <Download className="w-5 h-5" /> Google Drive
                                        </span>
                                    </a>
                                </div>
                             </div>
                        </div>

                        {/* Right Column: Troubleshooting */}
                        <div className="space-y-4">
                            {/* Video Container */}
                            <div className="bg-[#050403] border border-[#2e2418] p-4 h-full">
                                <h4 className="text-[#8b7d6b] text-xs font-bold uppercase mb-4 flex items-center gap-2">
                                    <span className="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span> {translations.downloads.troubleshooting}
                                </h4>
                                <div className="aspect-video w-full bg-black border border-[#2e2418]">
                                    <iframe 
                                        className="w-full h-full"
                                        src="https://www.youtube.com/embed/FN7Osaacfgk" 
                                        title="Solution Video" 
                                        frameBorder="0" 
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                        allowFullScreen
                                    ></iframe>
                                </div>
                                <p className="text-xs text-[#5e4b35] mt-4 text-center italic">
                                    {translations.downloads.troubleDesc}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            )}
            
            {page === Page.DONATE && (
                <DonationPanel t={translations.donate} />
            )}

          </main>

        </div>

        {/* Social Media / Support Section (Separate Container) */}
        <div className="w-full bg-[#0c0a09] border-t border-[#2e2418] p-8 flex flex-col items-center justify-center gap-4 relative z-20">
            <h4 className="text-[#5e4b35] font-serif uppercase tracking-widest text-xs font-bold border-b border-[#2e2418] pb-2">Comunidad & Soporte</h4>
            <div className="flex gap-6">
                <a href="#" target="_blank" rel="noopener noreferrer" className="group"> {/* TODO: Add your Discord link */}
                    <div className="w-12 h-12 bg-[#1a1612] border border-[#5e4b35] rounded-full flex items-center justify-center group-hover:border-[#ffd700] group-hover:bg-[#2a2115] group-hover:shadow-[0_0_20px_rgba(255,215,0,0.4)] transition-all duration-300">
                         <DiscordIcon className="w-6 h-6 text-[#8b7d6b] group-hover:text-[#ffd700] transition-colors" />
                    </div>
                </a>
                <a href="#" target="_blank" rel="noopener noreferrer" className="group"> {/* TODO: Add your Facebook link */}
                    <div className="w-12 h-12 bg-[#1a1612] border border-[#5e4b35] rounded-full flex items-center justify-center group-hover:border-[#ffd700] group-hover:bg-[#2a2115] group-hover:shadow-[0_0_20px_rgba(255,215,0,0.4)] transition-all duration-300">
                         <Facebook className="w-6 h-6 text-[#8b7d6b] group-hover:text-[#ffd700] transition-colors" />
                    </div>
                </a>
            </div>
        </div>

        {/* Footer */}
        <footer className="relative bg-[#050403] border-t border-[#2e2418] p-6 text-center w-full overflow-hidden min-h-[150px] flex flex-col items-center justify-center gap-2">
            {/* Footer Background Video with Overlay and Fade */}
            <div className="absolute inset-0 z-0 w-full h-full">
                 <video 
                     src="/bgheader.mp4"
                     autoPlay
                     muted
                     loop
                     playsInline
                     className="w-full h-full object-cover opacity-40 mix-blend-overlay"
                 />
                 {/* Top Fade */}
                 <div className="absolute inset-x-0 top-0 h-16 bg-gradient-to-b from-[#050403] to-transparent z-10"></div>
                 {/* Bottom Fade */}
                 <div className="absolute inset-x-0 bottom-0 h-16 bg-gradient-to-t from-[#050403] to-transparent z-10"></div>
                 {/* Left Fade */}
                 <div className="absolute inset-y-0 left-0 w-16 bg-gradient-to-r from-[#050403] to-transparent z-10"></div>
                 {/* Right Fade */}
                 <div className="absolute inset-y-0 right-0 w-16 bg-gradient-to-l from-[#050403] to-transparent z-10"></div>
            </div>
            
            <p className="relative z-20 text-[#ffd700] font-serif font-bold text-sm tracking-widest drop-shadow-md">
                {translations.footer}
            </p>
            <a href="https://gh0tstudio.com" target="_blank" rel="noopener noreferrer" className="relative z-20 text-[#8b7d6b] text-xs hover:text-[#cbb085] transition-colors uppercase tracking-wider">
                Desarrollado por gh0tstudio.com
            </a>
        </footer>

      </div>
    </div>
  );
};

export default App;
