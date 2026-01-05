import { useState, useCallback } from 'react'
import * as Security from '../utils/security'

/**
 * Hook para manejar seguridad en formularios
 * Proporciona validación, sanitización y protección contra ataques
 */
export const useSecurity = () => {
  const [csrfToken, setCSRFToken] = useState('')
  const [rateLimitExceeded, setRateLimitExceeded] = useState(false)
  const [validationErrors, setValidationErrors] = useState({})

  // Generar token CSRF al montar el componente
  const generateToken = useCallback(() => {
    const token = Security.generateCSRFToken()
    setCSRFToken(token)
    sessionStorage.setItem('csrfToken', token)
    return token
  }, [])

  // Validar token CSRF
  const validateToken = useCallback((token) => {
    const sessionToken = sessionStorage.getItem('csrfToken')
    return Security.validateCSRFToken(token, sessionToken)
  }, [])

  // Sanitizar entrada
  const sanitize = useCallback((input) => {
    return Security.sanitizeInput(input)
  }, [])

  // Validar email
  const isValidEmail = useCallback((email) => {
    return Security.validateEmail(email)
  }, [])

  // Validar login
  const isValidLogin = useCallback((login) => {
    return Security.validateLogin(login)
  }, [])

  // Validar contraseña
  const isValidPassword = useCallback((password) => {
    return Security.validatePassword(password)
  }, [])

  // Validar cantidad
  const isValidAmount = useCallback((amount, min, max) => {
    return Security.validateAmount(amount, min, max)
  }, [])

  // Verificar rate limiting
  const checkRateLimit = useCallback((key, limit = 5, windowMs = 60000) => {
    const exceeded = Security.checkRateLimit(key, limit, windowMs)
    setRateLimitExceeded(exceeded)
    return exceeded
  }, [])

  // Validar formulario completo
  const validateForm = useCallback((formData, requiredFields) => {
    const result = Security.validateFormData(formData, requiredFields)
    
    if (!result.isValid) {
      const errors = {}
      result.errors.forEach(error => {
        errors[error.split(' ')[2]] = error
      })
      setValidationErrors(errors)
    } else {
      setValidationErrors({})
    }
    
    return result.isValid
  }, [])

  // Limpiar errores
  const clearErrors = useCallback(() => {
    setValidationErrors({})
  }, [])

  return {
    csrfToken,
    generateToken,
    validateToken,
    sanitize,
    isValidEmail,
    isValidLogin,
    isValidPassword,
    isValidAmount,
    checkRateLimit,
    validateForm,
    clearErrors,
    validationErrors,
    rateLimitExceeded
  }
}

export default useSecurity
