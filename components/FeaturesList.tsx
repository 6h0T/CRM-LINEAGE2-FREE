
import React from 'react';
import { Shield, Sword, Zap, Box, Users, Lock, ScrollText, Crown, Sparkles, Trophy, Swords, ShieldCheck, Gem } from 'lucide-react';

interface FeaturesListProps {
  t: any;
}

// Reusable Icon Container matching Home/Studio styling
const IconContainer: React.FC<{ icon: React.ElementType }> = ({ icon: Icon }) => (
  <div className="w-10 h-10 bg-[#2a2115] rounded-full flex items-center justify-center border border-[#5e4b35] shadow-[0_0_10px_rgba(0,0,0,0.3)] flex-shrink-0 group-hover:border-[#ffd700] group-hover:shadow-[0_0_15px_rgba(255,215,0,0.4)] group-active:border-[#ffd700] transition-all duration-300">
    <Icon className="w-5 h-5 text-[#8b7d6b] group-hover:text-[#ffd700] group-active:text-[#ffd700] transition-colors duration-300" />
  </div>
);

const FeatureSection: React.FC<{ title: string; icon: React.ReactNode; children: React.ReactNode }> = ({ title, icon, children }) => (
  <div className="bg-[#12100e] border border-[#3d3122] p-6 mb-6 group hover:border-[#5e4b35] hover:bg-[#1a1612] transition-all duration-300 cursor-default">
    <div className="flex items-center gap-4 border-b border-[#2e2418] pb-4 mb-4">
        {icon}
        <h3 className="text-[#cbb085] font-serif text-xl group-hover:text-[#ffd700] transition-colors duration-300">{title}</h3>
    </div>
    <div className="text-sm text-[#918671] leading-relaxed">
        {children}
    </div>
  </div>
);

const FeaturesList: React.FC<FeaturesListProps> = ({ t }) => {
  return (
    <div className="animate-fade-in">
      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <FeatureSection 
            title={t.general.title} 
            icon={<IconContainer icon={ScrollText} />}
        >
            <ul className="space-y-2">
                <li className="flex justify-between"><span className="text-[#cbb085]">{t.general.chronicle}</span></li>
                <li className="flex justify-between"><span className="text-[#cbb085]">{t.general.rates}</span></li>
                <li className="flex justify-between"><span className="text-[#cbb085]">{t.general.level}</span></li>
                <li className="flex justify-between"><span className="text-[#cbb085]">{t.general.difficulty}</span></li>
                <li className="flex justify-between"><span className="text-[#cbb085]">{t.general.autofarm}</span></li>
                <li className="flex justify-between"><span className="text-[#cbb085]">{t.general.buffs}</span></li>
                <li className="flex justify-between"><span className="text-[#cbb085]">{t.general.slots}</span></li>
                <li className="flex justify-between"><span className="text-[#cbb085]">{t.general.noblesse}</span></li>
            </ul>
        </FeatureSection>

        <FeatureSection 
            title={t.premium.title} 
            icon={<IconContainer icon={Crown} />}
        >
            <ul className="space-y-2 list-disc list-inside text-[#cbb085]">
                {t.premium.items.map((item: string, i: number) => (
                    <li key={i}>{item}</li>
                ))}
            </ul>
        </FeatureSection>

        <FeatureSection 
            title={t.enchant.title} 
            icon={<IconContainer icon={Sparkles} />}
        >
            <ul className="space-y-2">
                <li><strong className="text-[#8b7d6b]">{t.enchant.safe}</strong></li>
                <li><strong className="text-[#8b7d6b]">{t.enchant.max}</strong></li>
                <li className="text-[#cbb085]">{t.enchant.blessed}</li>
                <li className="text-[#ffd700]">{t.enchant.gae}</li>
                <li className="pt-2 border-t border-[#2e2418] mt-2">{t.enchant.augmentChance}</li>
                <li>{t.enchant.augmentMax}</li>
            </ul>
        </FeatureSection>

        <FeatureSection 
            title={t.equipment.title} 
            icon={<IconContainer icon={Shield} />}
        >
            <ul className="space-y-2">
                <li>{t.equipment.gradeC}</li>
                <li>{t.equipment.gradeAS}</li>
                <li>{t.equipment.bossJewels}</li>
                <li>{t.equipment.tattoos}</li>
            </ul>
        </FeatureSection>

        <FeatureSection 
            title={t.clan.title} 
            icon={<IconContainer icon={Users} />}
        >
            <ul className="space-y-2">
                <li>{t.clan.maxMembers}</li>
                <li>{t.clan.raidLimit}</li>
                <li>{t.clan.epicLimit}</li>
            </ul>
        </FeatureSection>

        <FeatureSection 
            title={t.olympiad.title} 
            icon={<IconContainer icon={Trophy} />}
        >
             <ul className="space-y-2">
                <li>{t.olympiad.time}</li>
                <li>{t.olympiad.maxEnchant}</li>
                <li>{t.olympiad.cycle}</li>
                <li>{t.olympiad.jewels}</li>
            </ul>
        </FeatureSection>

        <FeatureSection 
            title={t.events.title} 
            icon={<IconContainer icon={Swords} />}
        >
             <ul className="space-y-2">
                <li>{t.events.tvt}</li>
                <li>{t.events.pvp}</li>
                <li className="text-[#ffd700] font-bold">{t.events.reward}</li>
            </ul>
        </FeatureSection>

        <FeatureSection 
            title={t.security.title} 
            icon={<IconContainer icon={ShieldCheck} />}
        >
             <ul className="space-y-2">
                <li>{t.security.clients}</li>
                <li className="text-green-500">{t.security.ddos}</li>
            </ul>
        </FeatureSection>

      </div>
    </div>
  );
};

export default FeaturesList;
