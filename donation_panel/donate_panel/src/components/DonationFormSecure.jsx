import { useState, useEffect } from 'react'
import { useTranslation } from 'react-i18next'
import { useSecurity } from '../hooks/useSecurity'
import securityService from '../services/securityService'
import toast from 'react-hot-toast'
import { donationAPI } from '../services/api'

/**
 * Componente de Formulario de Donación con Seguridad Integrada
 * 
 * Implementa:
 * - Sanitización de entrada
 * - Validación de datos
 * - Protección CSRF
 * - Rate limiting
 * - Logging de accesos
 */
export default function DonationFormSecure() {
  const { t } = useTranslation()
  const {
    csrfToken,
    generateToken,
    sanitize,
    isValidEmail,
    isValidAmount,
    checkRateLimit,
    validateForm,
    validationErrors,
    clearErrors
  } = useSecurity()

  const [formData, setFormData] = useState({
    email: '',
    amount: 1000,
    paymentMethod: 'paypal'
  })
  const [loading, setLoading] = useState(false)

  // Generar token CSRF al montar
  useEffect(() => {
    generateToken()
  }, [generateToken])

  // Manejar cambios en el formulario
  const handleInputChange = (e) => {
    const { name, value } = e.target
    clearErrors()

    setFormData(prev => ({
      ...prev,
      [name]: value
    }))
  }

  // Validar y enviar formulario
  const handleSubmit = async (e) => {
    e.preventDefault()
    setLoading(true)

    try {
      // 1. Verificar rate limiting (máximo 3 intentos por minuto)
      if (checkRateLimit('donation_submit', 3, 60000)) {
        toast.error(t('common.error') + ': ' + 'Demasiados intentos. Intenta más tarde.')
        setLoading(false)
        return
      }

      // 2. Validar formulario
      const isFormValid = validateForm(formData, ['email', 'amount'])
      if (!isFormValid) {
        toast.error('Por favor completa todos los campos correctamente')
        setLoading(false)
        return
      }

      // 3. Sanitizar entrada
      const sanitizedEmail = sanitize(formData.email)
      const sanitizedAmount = parseInt(formData.amount)

      // 4. Validar email
      if (!isValidEmail(sanitizedEmail)) {
        toast.error('Email inválido')
        setLoading(false)
        return
      }

      // 5. Validar cantidad (entre 100 y 50000)
      if (!isValidAmount(sanitizedAmount, 100, 50000)) {
        toast.error('Cantidad inválida (mínimo 100, máximo 50000)')
        setLoading(false)
        return
      }

      // 6. Preparar datos con token CSRF
      const requestData = securityService.prepareRequest({
        email: sanitizedEmail,
        amount: sanitizedAmount,
        paymentMethod: sanitize(formData.paymentMethod)
      })

      // 7. Enviar solicitud
      const response = await donationAPI.addDonation(requestData)

      // 8. Validar respuesta del servidor
      const validatedResponse = securityService.validateServerResponse(response)
      if (!validatedResponse.isValid) {
        toast.error(validatedResponse.error)
        setLoading(false)
        return
      }

      // 9. Registrar acceso exitoso
      await securityService.logAccessAttempt('donation_submit', {
        amount: sanitizedAmount,
        paymentMethod: formData.paymentMethod,
        email: sanitizedEmail
      })

      // 10. Mostrar éxito
      toast.success(t('donation.success'))
      setFormData({
        email: '',
        amount: 1000,
        paymentMethod: 'paypal'
      })

      // Redirigir a página de pago
      if (validatedResponse.data.redirect_url) {
        window.location.href = validatedResponse.data.redirect_url
      }

    } catch (error) {
      console.error('Error en donación:', error)
      toast.error('Error al procesar la donación')

      // Registrar error
      await securityService.logAccessAttempt('donation_error', {
        error: error.message
      })
    } finally {
      setLoading(false)
    }
  }

  return (
    <div className="animate-fade-in">
      <h1 className="text-3xl font-bold mb-8">{t('donation.title')}</h1>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {/* Información */}
        <div className="bg-white rounded-lg shadow-md p-6">
          <h2 className="text-xl font-semibold mb-4">Información</h2>
          <div className="space-y-4 text-sm text-gray-600">
            <p>
              <strong>Seguridad:</strong> Tu información está protegida con encriptación y validación en tiempo real.
            </p>
            <p>
              <strong>Métodos de Pago:</strong> PayPal, MercadoPago, PagSeguro y más.
            </p>
            <p>
              <strong>Bonus:</strong> Recibe coins adicionales según la cantidad donada.
            </p>
            <p>
              <strong>Instantáneo:</strong> Las coins se acreditan inmediatamente después del pago.
            </p>
          </div>
        </div>

        {/* Formulario */}
        <div className="bg-white rounded-lg shadow-md p-6">
          <form onSubmit={handleSubmit} className="space-y-6">
            {/* Token CSRF (oculto) */}
            <input
              type="hidden"
              name="csrf_token"
              value={csrfToken}
            />

            {/* Email */}
            <div>
              <label className="block text-sm font-semibold mb-2">
                Email *
              </label>
              <input
                type="email"
                name="email"
                value={formData.email}
                onChange={handleInputChange}
                placeholder="tu@email.com"
                className={`w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary ${
                  validationErrors.email ? 'border-red-500' : 'border-gray-300'
                }`}
                required
              />
              {validationErrors.email && (
                <p className="text-red-500 text-sm mt-1">{validationErrors.email}</p>
              )}
            </div>

            {/* Cantidad */}
            <div>
              <label className="block text-sm font-semibold mb-2">
                {t('donation.selectAmount')} *
              </label>
              <div className="grid grid-cols-2 gap-2 mb-4">
                {[100, 500, 1000, 5000].map(amount => (
                  <button
                    key={amount}
                    type="button"
                    onClick={() => {
                      setFormData(prev => ({ ...prev, amount }))
                      clearErrors()
                    }}
                    className={`py-2 rounded-lg font-semibold transition ${
                      formData.amount === amount
                        ? 'bg-primary text-white'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                    }`}
                  >
                    {amount}
                  </button>
                ))}
              </div>
              <input
                type="number"
                name="amount"
                value={formData.amount}
                onChange={handleInputChange}
                min="100"
                max="50000"
                className={`w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary ${
                  validationErrors.amount ? 'border-red-500' : 'border-gray-300'
                }`}
                required
              />
              {validationErrors.amount && (
                <p className="text-red-500 text-sm mt-1">{validationErrors.amount}</p>
              )}
              <p className="text-xs text-gray-500 mt-2">Mínimo: 100 | Máximo: 50000</p>
            </div>

            {/* Método de Pago */}
            <div>
              <label className="block text-sm font-semibold mb-2">
                {t('donation.selectPayment')} *
              </label>
              <select
                name="paymentMethod"
                value={formData.paymentMethod}
                onChange={handleInputChange}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
              >
                <option value="paypal">PayPal</option>
                <option value="mercadopago">MercadoPago</option>
                <option value="pagseguro">PagSeguro</option>
                <option value="banking">Transferencia Bancaria</option>
                <option value="picpay">PicPay</option>
              </select>
            </div>

            {/* Resumen */}
            <div className="bg-gray-50 p-4 rounded-lg">
              <div className="flex justify-between mb-2">
                <span>Cantidad:</span>
                <span className="font-semibold">{formData.amount} Coins</span>
              </div>
              <div className="flex justify-between mb-2">
                <span>Bonus (10%):</span>
                <span className="font-semibold text-green-600">+{Math.floor(formData.amount * 0.1)} Coins</span>
              </div>
              <div className="border-t pt-2 flex justify-between">
                <span className="font-bold">Total:</span>
                <span className="font-bold text-lg text-primary">
                  {formData.amount + Math.floor(formData.amount * 0.1)} Coins
                </span>
              </div>
            </div>

            {/* Botón Enviar */}
            <button
              type="submit"
              disabled={loading}
              className="w-full bg-primary text-white py-3 rounded-lg hover:bg-primary/90 transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {loading ? 'Procesando...' : t('donation.confirm')}
            </button>

            {/* Aviso de Seguridad */}
            <p className="text-xs text-gray-500 text-center">
              🔒 Tu información está protegida con encriptación SSL y validación de seguridad.
            </p>
          </form>
        </div>
      </div>
    </div>
  )
}
