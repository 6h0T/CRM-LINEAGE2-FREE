import React, { useState } from 'react';
import { UserPlus, Mail, Lock, User, AlertCircle, CheckCircle } from 'lucide-react';
import { registerAccount } from '../src/services/donationApi';

interface RegisterFormProps {
  t: any;
  onSuccess?: () => void;
  onCancel?: () => void;
}

const RegisterForm: React.FC<RegisterFormProps> = ({ t, onSuccess, onCancel }) => {
  const [formData, setFormData] = useState({
    login: '',
    email: '',
    email2: '',
    pass: '',
    pass2: ''
  });
  const [loading, setLoading] = useState(false);
  const [message, setMessage] = useState<{ type: 'success' | 'error', text: string } | null>(null);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
    setMessage(null);
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setMessage(null);

    // Validaciones básicas
    if (!formData.login || !formData.email || !formData.pass) {
      setMessage({ type: 'error', text: t.register?.fillAll || 'Por favor completa todos los campos' });
      setLoading(false);
      return;
    }

    if (formData.email !== formData.email2) {
      setMessage({ type: 'error', text: t.register?.emailMismatch || 'Los emails no coinciden' });
      setLoading(false);
      return;
    }

    if (formData.pass !== formData.pass2) {
      setMessage({ type: 'error', text: t.register?.passMismatch || 'Las contraseñas no coinciden' });
      setLoading(false);
      return;
    }

    if (formData.login.length < 4 || formData.login.length > 14) {
      setMessage({ type: 'error', text: t.register?.loginLength || 'El usuario debe tener entre 4 y 14 caracteres' });
      setLoading(false);
      return;
    }

    if (!/^[a-zA-Z0-9]+$/.test(formData.login) || !/^[a-zA-Z0-9]+$/.test(formData.pass)) {
      setMessage({ type: 'error', text: t.register?.alphanumeric || 'Solo caracteres alfanuméricos permitidos' });
      setLoading(false);
      return;
    }

    try {
      const result = await registerAccount(formData);
      
      if (result.success) {
        setMessage({ type: 'success', text: result.message || t.register?.success || 'Cuenta creada exitosamente' });
        setTimeout(() => {
          onSuccess?.();
        }, 2000);
      } else {
        setMessage({ type: 'error', text: result.error || t.register?.error || 'Error al crear cuenta' });
      }
    } catch (error) {
      setMessage({ type: 'error', text: t.register?.connectionError || 'Error de conexión' });
    } finally {
      setLoading(false);
    }
  };

  const inputClass = "w-full bg-[#1a1612] border border-[#2e2418] text-[#eecfa1] p-3 pl-10 focus:border-[#ffd700] outline-none transition-colors";
  const labelClass = "block text-[#8b7d6b] text-xs font-bold uppercase mb-2";

  return (
    <div className="max-w-md mx-auto bg-[#12100e] border border-[#3d3122] p-6 rounded shadow-lg animate-fade-in">
      <div className="text-center mb-6">
        <UserPlus className="w-12 h-12 text-[#ffd700] mx-auto mb-3" />
        <h2 className="text-2xl text-[#cbb085] font-serif mb-2">{t.register?.title || 'Crear Cuenta'}</h2>
        <p className="text-[#8b7d6b] text-sm">{t.register?.subtitle || 'Registra tu cuenta para jugar'}</p>
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
          <label className={labelClass}>{t.register?.username || 'Usuario'}</label>
          <div className="relative">
            <User className="absolute left-3 top-3 w-4 h-4 text-[#5e4b35]" />
            <input
              type="text"
              name="login"
              value={formData.login}
              onChange={handleChange}
              className={inputClass}
              placeholder="Username"
              maxLength={14}
              disabled={loading}
            />
          </div>
          <p className="text-[#5e4b35] text-xs mt-1">{t.register?.usernameHint || '4-14 caracteres alfanuméricos'}</p>
        </div>

        <div>
          <label className={labelClass}>{t.register?.email || 'Email'}</label>
          <div className="relative">
            <Mail className="absolute left-3 top-3 w-4 h-4 text-[#5e4b35]" />
            <input
              type="email"
              name="email"
              value={formData.email}
              onChange={handleChange}
              className={inputClass}
              placeholder="email@example.com"
              disabled={loading}
            />
          </div>
        </div>

        <div>
          <label className={labelClass}>{t.register?.confirmEmail || 'Confirmar Email'}</label>
          <div className="relative">
            <Mail className="absolute left-3 top-3 w-4 h-4 text-[#5e4b35]" />
            <input
              type="email"
              name="email2"
              value={formData.email2}
              onChange={handleChange}
              className={inputClass}
              placeholder="email@example.com"
              disabled={loading}
            />
          </div>
        </div>

        <div>
          <label className={labelClass}>{t.register?.password || 'Contraseña'}</label>
          <div className="relative">
            <Lock className="absolute left-3 top-3 w-4 h-4 text-[#5e4b35]" />
            <input
              type="password"
              name="pass"
              value={formData.pass}
              onChange={handleChange}
              className={inputClass}
              placeholder="••••••••"
              disabled={loading}
            />
          </div>
        </div>

        <div>
          <label className={labelClass}>{t.register?.confirmPassword || 'Confirmar Contraseña'}</label>
          <div className="relative">
            <Lock className="absolute left-3 top-3 w-4 h-4 text-[#5e4b35]" />
            <input
              type="password"
              name="pass2"
              value={formData.pass2}
              onChange={handleChange}
              className={inputClass}
              placeholder="••••••••"
              disabled={loading}
            />
          </div>
        </div>

        <div className="flex gap-3 pt-4">
          <button
            type="submit"
            disabled={loading}
            className="flex-1 bg-[#1a1612] hover:bg-[#2a2115] text-[#cbb085] border border-[#5e4b35] hover:border-[#ffd700] hover:text-[#ffd700] hover:shadow-[0_0_10px_rgba(255,215,0,0.3)] py-3 font-bold rounded-sm transition-all uppercase tracking-widest text-sm disabled:opacity-50 disabled:cursor-not-allowed"
          >
            {loading ? (t.register?.creating || 'Creando...') : (t.register?.create || 'Crear Cuenta')}
          </button>
          
          {onCancel && (
            <button
              type="button"
              onClick={onCancel}
              className="px-6 bg-transparent border border-[#2e2418] text-[#8b7d6b] hover:border-[#5e4b35] hover:text-[#cbb085] py-3 font-bold rounded-sm transition-all uppercase tracking-widest text-sm"
            >
              {t.register?.cancel || 'Cancelar'}
            </button>
          )}
        </div>
      </form>
    </div>
  );
};

export default RegisterForm;
