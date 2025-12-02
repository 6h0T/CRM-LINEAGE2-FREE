
import React from 'react';
import { Trophy } from 'lucide-react';

interface OlympiadRankingProps {
  t: any;
}

// Mock data based on description
const heroes = [
    { class: "Duelist", player: "XxSlayerxX", points: 1540, status: "Hero" },
    { class: "Dreadnought", player: "Warlord01", points: 890, status: "Hero" },
    { class: "Phoenix Knight", player: "TankMaster", points: 1200, status: "Hero" },
    { class: "Hell Knight", player: "DarkAvenger", points: 950, status: "Hero" },
    { class: "Sagittarius", player: "LegolasL2", points: 1800, status: "Hero" },
    { class: "Adventurer", player: "DaggerGod", points: 1650, status: "Hero" },
    { class: "Archmage", player: "Gandalf", points: 1300, status: "Hero" },
    { class: "Soultaker", player: "NecroLord", points: 1450, status: "Hero" },
];

const OlympiadRanking: React.FC<OlympiadRankingProps> = ({ t }) => {
  return (
    <div className="animate-fade-in bg-[#12100e] border border-[#3d3122] p-6">
        <div className="flex items-center gap-3 mb-6 border-b border-[#2e2418] pb-4">
            <Trophy className="w-8 h-8 text-[#ffd700]" />
            <div>
                <h2 className="text-[#cbb085] font-serif text-2xl">{t.title}</h2>
                <p className="text-[#8b7d6b] text-sm">{t.subtitle}</p>
            </div>
        </div>

        <div className="overflow-x-auto">
            <table className="w-full text-left">
                <thead className="bg-[#1a1612] text-[#5e4b35] uppercase text-xs font-bold">
                    <tr>
                        <th className="p-3">{t.class}</th>
                        <th className="p-3">{t.player}</th>
                        <th className="p-3 text-right">{t.points}</th>
                        <th className="p-3 text-center">{t.status}</th>
                    </tr>
                </thead>
                <tbody className="text-sm">
                    {heroes.map((hero, idx) => (
                        <tr key={idx} className="border-b border-[#2e2418] hover:bg-[#0a0908] text-[#8b7d6b] hover:text-[#cbb085] transition-colors">
                            <td className="p-3 font-bold">{hero.class}</td>
                            <td className="p-3 text-[#eecfa1]">{hero.player}</td>
                            <td className="p-3 text-right font-mono">{hero.points}</td>
                            <td className="p-3 text-center">
                                <span className="text-[#ffd700] text-[10px] border border-[#ffd700] px-2 py-0.5 rounded bg-[#ffd700]/10">HERO</span>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    </div>
  );
};

export default OlympiadRanking;
