import React, { useState, useEffect } from 'react';
import { Coins, CreditCard, DollarSign, AlertCircle, CheckCircle } from 'lucide-react';

interface DonationPanelProps {
  t: any;
}

const DonationPanel: React.FC<DonationPanelProps> = ({ t }) => {
  const [formData, setFormData] = useState({
    charName: '',
    qtdCoins: '',
    metodo_pgto: 'MercadoPago'
  });
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState<{ type: 'success' | 'error', text: string } | null>(null);
  const [calculatedPrice, setCalculatedPrice] = useState(0);

  // Tasas de conversión según el método de pago
  const conversionRates: { [key: string]: { rate: number, currency: string, symbol: string } } = {
    MercadoPago: { rate: 30, currency: 'ARS', symbol: '$' },  // 30 ARS = 1 coin
    PayPal_USD: { rate: 0.033, currency: 'USD', symbol: '$' }, // 3.30 USD = 100 coins
    PagSeguro: { rate: 0.5, currency: 'BRL', symbol: 'R$' }    // Ajustar según configuración
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
    setMessage(null);

    // Calcular precio cuando cambia la cantidad de coins o el método
    if (name === 'qtdCoins' || name === 'metodo_pgto') {
      const coins = name === 'qtdCoins' ? parseInt(value) : parseInt(formData.qtdCoins);
      const method = name === 'metodo_pgto' ? value : formData.metodo_pgto;
      
      if (!isNaN(coins) && coins > 0 && conversionRates[method]) {
        const price = coins * conversionRates[method].rate;
        setCalculatedPrice(price);
      } else {
        setCalculatedPrice(0);
      }
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setMessage(null);

    console.log('=== INICIO DONACIÓN ===' );
    console.log('Datos del formulario:', formData);

    // Validaciones
    if (!formData.charName || !formData.qtdCoins || !formData.metodo_pgto) {
      console.error('Validación fallida: campos vacíos');
      setMessage({ type: 'error', text: t.form?.fillAll || 'Por favor completa todos los campos' });
      setLoading(false);
      return;
    }

    const coins = parseInt(formData.qtdCoins);
    if (isNaN(coins) || coins <= 0) {
      console.error('Validación fallida: coins inválidos');
      setMessage({ type: 'error', text: 'Cantidad de coins inválida' });
      setLoading(false);
      return;
    }

    if (coins < 100) {
      console.error('Validación fallida: mínimo 100 coins');
      setMessage({ type: 'error', text: 'La cantidad mínima es 100 coins' });
      setLoading(false);
      return;
    }

    try {
      console.log('Creando formulario para enviar al UCP...');
      
      // Crear un formulario y enviarlo al UCP con el nuevo endpoint
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '/donation_panel/ucp/?module=donate&engine=create_direct_order';
      
      console.log('URL destino:', form.action);
      
      const inputCharName = document.createElement('input');
      inputCharName.type = 'hidden';
      inputCharName.name = 'charName';
      inputCharName.value = formData.charName;
      form.appendChild(inputCharName);
      
      const inputCoins = document.createElement('input');
      inputCoins.type = 'hidden';
      inputCoins.name = 'qtdCoins';
      inputCoins.value = formData.qtdCoins;
      form.appendChild(inputCoins);
      
      const inputMethod = document.createElement('input');
      inputMethod.type = 'hidden';
      inputMethod.name = 'metodo_pgto';
      inputMethod.value = formData.metodo_pgto;
      form.appendChild(inputMethod);
      
      console.log('Formulario creado con campos:', {
        charName: formData.charName,
        qtdCoins: formData.qtdCoins,
        metodo_pgto: formData.metodo_pgto
      });
      
      document.body.appendChild(form);
      console.log('Enviando formulario...');
      form.submit();
      
    } catch (error) {
      console.error('Error al enviar formulario:', error);
      setMessage({ 
        type: 'error', 
        text: 'Error al procesar la solicitud: ' + (error instanceof Error ? error.message : 'Error desconocido')
      });
      setLoading(false);
    }
  };

  const inputClass = "w-full bg-[#1a1612] border border-[#2e2418] text-[#eecfa1] p-3 focus:border-[#ffd700] outline-none transition-colors";
  const labelClass = "block text-[#8b7d6b] text-xs font-bold uppercase mb-2";

  return (
    <div className="bg-[#12100e] border border-[#3d3122] p-6 shadow-lg">
      <div className="text-center mb-6">
        <Coins className="w-12 h-12 text-[#ffd700] mx-auto mb-3" />
        <h2 className="text-2xl text-[#cbb085] font-serif mb-2">{t.title || 'Donaciones'}</h2>
        <p className="text-[#8b7d6b] text-sm">{t.subtitle || 'Apoya al servidor y obtén Donate Coins'}</p>
      </div>

      {message && (
        <div className={`mb-4 p-3 rounded flex items-center gap-2 max-w-2xl mx-auto ${
          message.type === 'success' 
            ? 'bg-green-900/30 border border-green-700 text-green-400' 
            : 'bg-red-900/30 border border-red-700 text-red-400'
        }`}>
          {message.type === 'success' ? <CheckCircle className="w-5 h-5" /> : <AlertCircle className="w-5 h-5" />}
          <span className="text-sm">{message.text}</span>
        </div>
      )}

      <form onSubmit={handleSubmit} className="max-w-2xl mx-auto space-y-6">
        <div className="bg-[#0a0908] border border-[#5e4b35] p-6 rounded">
          <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            {/* Nombre del Personaje */}
            <div className="md:col-span-2">
              <label className={labelClass}>Nombre del Personaje</label>
              <input
                type="text"
                name="charName"
                value={formData.charName}
                onChange={handleChange}
                className={inputClass}
                placeholder="Ingresa el nombre de tu personaje"
                disabled={loading}
                required
              />
              <p className="text-[#5e4b35] text-xs mt-1">Los coins se acreditarán directamente a este personaje</p>
            </div>
            
            {/* Método de Pago */}
            <div>
              <label className={labelClass}>Método de Pago</label>
              <select
                name="metodo_pgto"
                value={formData.metodo_pgto}
                onChange={handleChange}
                className={inputClass}
                disabled={loading}
              >
                <option value="MercadoPago">MercadoPago (ARS)</option>
                <option value="PayPal_USD">PayPal (USD)</option>
                <option value="PagSeguro">PagSeguro (BRL)</option>
              </select>
            </div>

            {/* Cantidad de Coins */}
            <div>
              <label className={labelClass}>{t.form?.receive || 'Cantidad de Donate Coins'}</label>
              <div className="relative">
                <Coins className="absolute left-3 top-3 w-4 h-4 text-[#5e4b35]" />
                <input
                  type="number"
                  name="qtdCoins"
                  value={formData.qtdCoins}
                  onChange={handleChange}
                  className={`${inputClass} pl-10`}
                  placeholder="100"
                  min="100"
                  step="100"
                  disabled={loading}
                  required
                />
              </div>
              <p className="text-[#5e4b35] text-xs mt-1">Mínimo: 100 coins</p>
            </div>

            {/* Precio Calculado */}
            <div className="md:col-span-2">
              <label className={labelClass}>Total a Pagar</label>
              <div className="w-full bg-[#050403] border border-[#4a3b2a] text-[#ffd700] p-4 font-mono font-bold text-2xl flex items-center justify-center">
                <DollarSign className="w-6 h-6 mr-2" />
                <span>
                  {conversionRates[formData.metodo_pgto]?.symbol} {calculatedPrice.toFixed(2)} {conversionRates[formData.metodo_pgto]?.currency}
                </span>
              </div>
            </div>
          </div>
        </div>

        {/* Botón de Envío */}
        <button
          type="submit"
          disabled={loading || !formData.charName || !formData.qtdCoins}
          className="w-full bg-[#1a1612] hover:bg-[#2a2115] text-[#cbb085] border border-[#5e4b35] hover:border-[#ffd700] hover:text-[#ffd700] hover:shadow-[0_0_10px_rgba(255,215,0,0.3)] py-4 font-bold rounded-sm transition-all uppercase tracking-widest text-sm disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
        >
          <CreditCard className="w-5 h-5" />
          {loading ? 'Procesando...' : 'Continuar al Pago'}
        </button>
      </form>

      {/* Información Adicional */}
      <div className="mt-8 text-center">
        <p className="text-[#5e4b35] text-xs mb-4">
          {t.items || 'Los Donate Coins se agregarán automáticamente a tu cuenta una vez confirmado el pago.'}
        </p>
        
        {/* Tabla de Conversión */}
        {t.table && (
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
            {t.table.ars && (
              <div className="bg-[#1a1612] border border-[#2e2418] p-4">
                <h4 className="text-[#cbb085] font-bold text-center border-b border-[#4a3b2a] pb-2 mb-3">
                  {t.conversion?.ars || 'ARS'}
                </h4>
                <div className="space-y-2 text-sm">
                  {t.table.ars.slice(0, 3).map((item: any, idx: number) => (
                    <div key={idx} className="flex justify-between text-[#8b7d6b]">
                      <span>{item.amount}</span>
                      <span className="text-[#eecfa1] font-bold">{item.coins} coins</span>
                    </div>
                  ))}
                </div>
              </div>
            )}
            {t.table.usd && (
              <div className="bg-[#1a1612] border border-[#2e2418] p-4">
                <h4 className="text-[#cbb085] font-bold text-center border-b border-[#4a3b2a] pb-2 mb-3">
                  {t.conversion?.usd || 'USD'}
                </h4>
                <div className="space-y-2 text-sm">
                  {t.table.usd.slice(0, 3).map((item: any, idx: number) => (
                    <div key={idx} className="flex justify-between text-[#8b7d6b]">
                      <span>{item.amount}</span>
                      <span className="text-[#eecfa1] font-bold">{item.coins} coins</span>
                    </div>
                  ))}
                </div>
              </div>
            )}
            {t.table.clp && (
              <div className="bg-[#1a1612] border border-[#2e2418] p-4">
                <h4 className="text-[#cbb085] font-bold text-center border-b border-[#4a3b2a] pb-2 mb-3">
                  {t.conversion?.clp || 'CLP'}
                </h4>
                <div className="space-y-2 text-sm">
                  {t.table.clp.slice(0, 3).map((item: any, idx: number) => (
                    <div key={idx} className="flex justify-between text-[#8b7d6b]">
                      <span>{item.amount}</span>
                      <span className="text-[#eecfa1] font-bold">{item.coins} coins</span>
                    </div>
                  ))}
                </div>
              </div>
            )}
          </div>
        )}
      </div>
    </div>
  );
};

export default DonationPanel;
