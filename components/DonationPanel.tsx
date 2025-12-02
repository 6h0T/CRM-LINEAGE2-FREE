
import React, { useState, useEffect } from 'react';
import { Coins, ShoppingCart, ExternalLink } from 'lucide-react';

interface DonationPanelProps {
  t: any;
}

// Conversion Rates (Approximation based on static table)
// ARS: 15000 = 1000 => 1 coin = 15 ARS
// USD: 10 = 1000 => 1 coin = 0.01 USD
// CLP: 10000 = 1000 => 1 coin = 10 CLP
const RATES = {
  ARS: 15,
  USD: 0.01,
  CLP: 10
};

const PriceTable: React.FC<{ title: string; data: { amount: string, coins: string }[] }> = ({ title, data }) => (
  <div className="bg-[#1a1612] border border-[#2e2418] p-4 flex-1 min-w-[250px] mt-4">
    <h4 className="text-[#cbb085] font-bold text-center border-b border-[#4a3b2a] pb-2 mb-3">{title}</h4>
    <table className="w-full text-sm text-[#8b7d6b]">
        <thead>
            <tr className="text-[#5e4b35] text-xs uppercase">
                <th className="text-left py-1">Currency</th>
                <th className="text-right py-1">Coins</th>
            </tr>
        </thead>
        <tbody>
            {data.map((row, idx) => (
                <tr key={idx} className="border-b border-[#2e2418] last:border-0 hover:bg-[#2a2115] hover:text-[#cbb085] transition-colors">
                    <td className="py-1">{row.amount}</td>
                    <td className="py-1 text-right font-bold text-[#eecfa1]">{row.coins}</td>
                </tr>
            ))}
        </tbody>
    </table>
  </div>
);

const DonationPanel: React.FC<DonationPanelProps> = ({ t }) => {
  const [charName, setCharName] = useState('');
  const [currency, setCurrency] = useState<'ARS' | 'USD' | 'CLP'>('ARS');
  const [amount, setAmount] = useState<string>('');
  const [coins, setCoins] = useState<number>(0);

  useEffect(() => {
    if (!amount || isNaN(Number(amount))) {
        setCoins(0);
        return;
    }
    const val = parseFloat(amount);
    const rate = RATES[currency];
    // Coins = Amount / Rate
    const calculated = Math.floor(val / rate);
    setCoins(calculated);
  }, [amount, currency]);

  const handlePayment = (method: 'MP' | 'PP' | 'PREX') => {
    if (!charName || !amount || Number(amount) <= 0) {
        alert(t.form.fillAll);
        return;
    }
    // In a real app, this would call an API to generate a preference preference
    const msg = `Initiating payment via ${method} for ${charName}: ${amount} ${currency}`;
    console.log(msg);
    
    let url = '';
    if (method === 'MP') url = 'https://www.mercadopago.com.ar';
    if (method === 'PP') url = 'https://www.paypal.com';
    if (method === 'PREX') url = 'https://www.prexcard.com';

    window.open(url, '_blank');
  };

  const ButtonStyle = "w-full flex items-center justify-center gap-2 bg-[#1a1612] hover:bg-[#2a2115] text-[#cbb085] border border-[#5e4b35] hover:border-[#ffd700] hover:text-[#ffd700] hover:shadow-[0_0_10px_rgba(255,215,0,0.3)] py-3 font-bold rounded-sm transition-all uppercase tracking-widest text-sm";

  return (
    <div className="space-y-8 animate-fade-in">
        <div className="bg-[#12100e] border border-[#3d3122] p-6 text-center">
            <Coins className="w-12 h-12 text-[#ffd700] mx-auto mb-4" />
            <h2 className="text-2xl text-[#cbb085] font-serif mb-2">{t.title}</h2>
            <p className="text-[#8b7d6b] mb-4">{t.subtitle}</p>
            
            {/* Interactive Donation Form */}
            <div className="max-w-2xl mx-auto bg-[#0a0908] border border-[#5e4b35] p-6 rounded shadow-lg mb-12 text-left relative overflow-hidden">
                <div className="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#5e4b35] via-[#ffd700] to-[#5e4b35]"></div>
                
                <div className="flex items-center gap-2 mb-6 text-[#cbb085]">
                    <ShoppingCart className="w-5 h-5" />
                    <span className="uppercase font-bold tracking-wider text-sm">Purchase Coins</span>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    {/* Character Name */}
                    <div>
                        <label className="block text-[#8b7d6b] text-xs font-bold uppercase mb-2">{t.form.charName}</label>
                        <input 
                            type="text" 
                            value={charName}
                            onChange={(e) => setCharName(e.target.value)}
                            className="w-full bg-[#1a1612] border border-[#2e2418] text-[#eecfa1] p-3 focus:border-[#ffd700] outline-none transition-colors"
                        />
                    </div>

                    {/* Currency */}
                    <div>
                        <label className="block text-[#8b7d6b] text-xs font-bold uppercase mb-2">{t.form.currency}</label>
                        <select 
                            value={currency}
                            onChange={(e) => {
                                setCurrency(e.target.value as any);
                                setAmount(''); // Reset amount on currency change to avoid confusion
                            }}
                            className="w-full bg-[#1a1612] border border-[#2e2418] text-[#eecfa1] p-3 focus:border-[#ffd700] outline-none cursor-pointer"
                        >
                            <option value="ARS">ARS (Pesos Argentinos)</option>
                            <option value="USD">USD (Dólar)</option>
                            <option value="CLP">CLP (Pesos Chilenos)</option>
                        </select>
                    </div>

                    {/* Amount */}
                    <div>
                        <label className="block text-[#8b7d6b] text-xs font-bold uppercase mb-2">{t.form.amount}</label>
                        <div className="relative">
                            <input 
                                type="number" 
                                value={amount}
                                onChange={(e) => setAmount(e.target.value)}
                                className="w-full bg-[#1a1612] border border-[#2e2418] text-[#eecfa1] p-3 focus:border-[#ffd700] outline-none transition-colors"
                                placeholder="0"
                            />
                            <span className="absolute right-4 top-3 text-[#5e4b35] font-bold text-sm">{currency}</span>
                        </div>
                    </div>

                    {/* Result Preview */}
                    <div>
                        <label className="block text-[#8b7d6b] text-xs font-bold uppercase mb-2">{t.form.receive}</label>
                        <div className="w-full bg-[#050403] border border-[#4a3b2a] text-[#ffd700] p-3 font-mono font-bold text-lg flex items-center justify-between">
                            <span>{coins.toLocaleString()}</span>
                            <Coins className="w-4 h-4 text-[#ffd700]" />
                        </div>
                    </div>
                </div>

                {/* Payment Buttons */}
                <div className="space-y-3 border-t border-[#2e2418] pt-6">
                    {currency === 'ARS' && (
                        <button 
                            onClick={() => handlePayment('MP')}
                            className={ButtonStyle}
                        >
                            <ExternalLink className="w-4 h-4" /> {t.form.payMp}
                        </button>
                    )}
                    
                    {(currency === 'USD' || currency === 'CLP') && (
                         <button 
                            onClick={() => handlePayment('PP')}
                            className={ButtonStyle}
                         >
                            <ExternalLink className="w-4 h-4" /> {t.form.payPp}
                        </button>
                    )}

                    <button 
                        onClick={() => handlePayment('PREX')}
                        className={ButtonStyle}
                    >
                        <ExternalLink className="w-4 h-4" /> {t.form.payPrex}
                    </button>
                </div>

            </div>
            
            <p className="text-xs text-[#5e4b35] max-w-md mx-auto mb-8">
                {t.items}
            </p>

            {/* Reference Tables */}
            <div className="flex flex-wrap gap-4 justify-center">
                <PriceTable title={t.conversion.ars} data={t.table.ars} />
                <PriceTable title={t.conversion.usd} data={t.table.usd} />
                <PriceTable title={t.conversion.clp} data={t.table.clp} />
            </div>
        </div>
    </div>
  );
};

export default DonationPanel;
