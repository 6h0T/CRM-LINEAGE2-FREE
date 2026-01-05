import api from './api'
import * as Security from '../utils/security'

/**
 * Servicio de seguridad
 * Maneja validaciones y protecciones en el cliente
 */

export const securityService = {
  /**
   * Validar sesión con el backend
   */
  validateSession: async () => {
    try {
      const response = await api.get('auth/validate-session.php')
      return response.data.success
    } catch (error) {
      console.error('Error validando sesión:', error)
      return false
    }
  },

  /**
   * Obtener token CSRF del backend
   */
  getCSRFToken: async () => {
    try {
      const response = await api.get('auth/csrf-token.php')
      if (response.data.success) {
        sessionStorage.setItem('csrfToken', response.data.token)
        return response.data.token
      }
    } catch (error) {
      console.error('Error obteniendo token CSRF:', error)
    }
    return null
  },

  /**
   * Validar entrada de usuario
   */
  validateInput: (input, type = 'text') => {
    const sanitized = Security.sanitizeInput(input)
    
    switch (type) {
      case 'email':
        return {
          isValid: Security.validateEmail(sanitized),
          value: sanitized
        }
      case 'login':
        return {
          isValid: Security.validateLogin(sanitized),
          value: sanitized
        }
      case 'password':
        return {
          isValid: Security.validatePassword(sanitized),
          value: sanitized
        }
      case 'amount':
        return {
          isValid: Security.validateAmount(sanitized),
          value: parseInt(sanitized) || 0
        }
      default:
        return {
          isValid: true,
          value: sanitized
        }
    }
  },

  /**
   * Validar formulario antes de enviar
   */
  validateFormBeforeSubmit: (formData, rules = {}) => {
    const errors = {}
    
    Object.keys(rules).forEach(field => {
      const rule = rules[field]
      const value = formData[field]
      
      if (rule.required && (!value || value.toString().trim() === '')) {
        errors[field] = `${field} es requerido`
      }
      
      if (rule.type && value) {
        const validation = securityService.validateInput(value, rule.type)
        if (!validation.isValid) {
          errors[field] = `${field} no es válido`
        }
      }
      
      if (rule.minLength && value && value.length < rule.minLength) {
        errors[field] = `${field} debe tener al menos ${rule.minLength} caracteres`
      }
      
      if (rule.maxLength && value && value.length > rule.maxLength) {
        errors[field] = `${field} no puede exceder ${rule.maxLength} caracteres`
      }
      
      if (rule.pattern && value && !rule.pattern.test(value)) {
        errors[field] = `${field} tiene un formato inválido`
      }
    })
    
    return {
      isValid: Object.keys(errors).length === 0,
      errors
    }
  },

  /**
   * Sanitizar datos antes de enviar al servidor
   */
  sanitizeFormData: (formData) => {
    const sanitized = {}
    
    Object.keys(formData).forEach(key => {
      const value = formData[key]
      
      if (typeof value === 'string') {
        sanitized[key] = Security.sanitizeInput(value)
      } else if (typeof value === 'number') {
        sanitized[key] = parseInt(value) || 0
      } else {
        sanitized[key] = value
      }
    })
    
    return sanitized
  },

  /**
   * Preparar solicitud con token CSRF
   */
  prepareRequest: (data = {}) => {
    const csrfToken = sessionStorage.getItem('csrfToken')
    
    return {
      ...data,
      csrf_token: csrfToken
    }
  },

  /**
   * Verificar rate limiting
   */
  checkRateLimit: (action, limit = 5, windowMs = 60000) => {
    return !Security.checkRateLimit(action, limit, windowMs)
  },

  /**
   * Encriptar datos sensibles
   */
  encryptSensitiveData: (data) => {
    return Security.encryptData(JSON.stringify(data))
  },

  /**
   * Desencriptar datos
   */
  decryptSensitiveData: (encrypted) => {
    try {
      return JSON.parse(Security.decryptData(encrypted))
    } catch (error) {
      console.error('Error desencriptando datos:', error)
      return null
    }
  },

  /**
   * Registrar intento de acceso (para auditoría)
   */
  logAccessAttempt: async (action, details = {}) => {
    try {
      await api.post('auth/log-access.php', {
        action,
        details: Security.sanitizeInput(JSON.stringify(details)),
        timestamp: new Date().toISOString()
      })
    } catch (error) {
      console.error('Error registrando acceso:', error)
    }
  },

  /**
   * Validar respuesta del servidor
   */
  validateServerResponse: (response) => {
    if (!response.data) {
      return {
        isValid: false,
        error: 'Respuesta del servidor inválida'
      }
    }
    
    if (!response.data.success) {
      return {
        isValid: false,
        error: response.data.message || 'Error desconocido'
      }
    }
    
    return {
      isValid: true,
      data: response.data.data
    }
  }
}

export default securityService
