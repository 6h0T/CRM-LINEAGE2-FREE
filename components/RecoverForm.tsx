import React, { useState } from 'react';
import { Mail, AlertCircle, CheckCircle, KeyRound } from 'lucide-react';
import { recoverPassword } from '../src/services/donationApi';

interface RecoverFormProps {
  t: any;
  onSuccess?: () => void;
  onCancel?: () => void;
}

const RecoverForm: React.FC<RecoverFormProps> = ({ t, onSuccess, onCancel }) => {
  const [email, setEmail] = useState('');
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState<{ type: 'success' | 'error', text: string } | null>(null);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setMessage(null);

    if (!email) {
      setMessage({ type: 'error', text: t.recover?.fillEmail || 'Por favor ingresa tu email' });
      setLoading(false);
      return;
    }

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      setMessage({ type: 'error', text: t.recover?.invalidEmail || 'Email inválido' });
      setLoading(false);
      return;
    }

    try {
      const result = await recoverPassword({ email });
      
      if (result.success) {
        setMessage({ 
          type: 'success', 
          text: result.message || t.recover?.success || 'Email de recuperación enviado. Revisa tu bandeja de entrada.' 
        });
        setTimeout(() => {
          onSuccess?.();
        }, 3000);
      } else {
        setMessage({ type: 'error', text: result.error || t.recover?.error || 'Error al enviar email' });
      }
    } catch (error) {
      setMessage({ type: 'error', text: t.recover?.connectionError || 'Error de conexión' });
    } finally {
      setLoading(false);
    }
  };

  const inputClass = "w-full bg-[#1a1612] border border-[#2e2418] text-[#eecfa1] p-3 pl-10 focus:border-[#ffd700] outline-none transition-colors";

  return (
    <div className="max-w-md mx-auto bg-[#12100e] border border-[#3d3122] p-6 rounded shadow-lg animate-fade-in">
      <div className="text-center mb-6">
        <KeyRound className="w-12 h-12 text-[#ffd700] mx-auto mb-3" />
        <h2 className="text-2xl text-[#cbb085] font-serif mb-2">{t.recover?.title || 'Recuperar Contraseña'}</h2>
        <p className="text-[#8b7d6b] text-sm">{t.recover?.subtitle || 'Ingresa tu email para recuperar tu cuenta'}</p>
      </div>

      {message && (
        <div className={`mb-4 p-3 rounded flex items-center gap-2 ${
          message.type === 'success' 
            ? 'bg-green-900/30 border border-green-700 text-green-400' 
            : 'bg-red-900/30 border border-red-700 text-red-400'
        }`}>
          {message.type === 'success' ? <CheckCircle className="w-5 h-5" /> : <AlertCircle className="w-5 h-5" />}
          <span className="text-sm">{message.text}</span>
        </div>
      )}

      <form onSubmit={handleSubmit} className="space-y-4">
        <div>
          <label className="block text-[#8b7d6b] text-xs font-bold uppercase mb-2">
            {t.recover?.email || 'Email'}
          </label>
          <div className="relative">
            <Mail className="absolute left-3 top-3 w-4 h-4 text-[#5e4b35]" />
            <input
              type="email"
              value={email}
              onChange={(e) => {
                setEmail(e.target.value);
                setMessage(null);
              }}
              className={inputClass}
              placeholder="email@example.com"
              disabled={loading}
            />
          </div>
          <p className="text-[#5e4b35] text-xs mt-2">
            {t.recover?.hint || 'Recibirás un email con instrucciones para recuperar tu cuenta'}
          </p>
        </div>

        <div className="flex gap-3 pt-4">
          <button
            type="submit"
            disabled={loading}
            className="flex-1 bg-[#1a1612] hover:bg-[#2a2115] text-[#cbb085] border border-[#5e4b35] hover:border-[#ffd700] hover:text-[#ffd700] hover:shadow-[0_0_10px_rgba(255,215,0,0.3)] py-3 font-bold rounded-sm transition-all uppercase tracking-widest text-sm disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {loading ? (t.recover?.sending || 'Enviando...') : (t.recover?.send || 'Enviar Email')}
          </button>
          
          {onCancel && (
            <button
              type="button"
              onClick={onCancel}
              className="px-6 bg-transparent border border-[#2e2418] text-[#8b7d6b] hover:border-[#5e4b35] hover:text-[#cbb085] py-3 font-bold rounded-sm transition-all uppercase tracking-widest text-sm"
            >
              {t.recover?.cancel || 'Cancelar'}
            </button>
          )}
        </div>
      </form>
    </div>
  );
};

export default RecoverForm;
