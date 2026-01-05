/**
 * Security Utilities - Basado en métodos de seguridad del proyecto
 * Validación, sanitización y protección contra ataques comunes
 */

/**
 * Sanitizar entrada de usuario (equivalente a vCode() en PHP)
 * - Trim whitespace
 * - HTML entities encoding
 * - Escape quotes
 * @param {string} content - Contenido a sanitizar
 * @returns {string} Contenido sanitizado
 */
export const sanitizeInput = (content) => {
  if (typeof content !== 'string') return ''
  
  return content
    .trim()
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#x27;')
}

/**
 * Validar email
 * Patrón: [_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})
 * @param {string} email - Email a validar
 * @returns {boolean} True si es válido
 */
export const validateEmail = (email) => {
  const emailRegex = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i
  return emailRegex.test(email)
}

/**
 * Validar login (solo alfanuméricos)
 * @param {string} login - Login a validar
 * @returns {boolean} True si es válido
 */
export const validateLogin = (login) => {
  return /^[a-zA-Z0-9]{3,14}$/.test(login)
}

/**
 * Validar contraseña (solo alfanuméricos, sin caracteres especiales)
 * @param {string} password - Contraseña a validar
 * @returns {boolean} True si es válida
 */
export const validatePassword = (password) => {
  return /^[a-zA-Z0-9]{6,}$/.test(password)
}

/**
 * Validar cantidad numérica
 * @param {number} amount - Cantidad a validar
 * @param {number} min - Mínimo permitido
 * @param {number} max - Máximo permitido
 * @returns {boolean} True si es válida
 */
export const validateAmount = (amount, min = 0, max = 999999) => {
  const num = parseInt(amount)
  return !isNaN(num) && num >= min && num <= max
}

/**
 * Generar token CSRF
 * Basado en: md5(time + random + uniqueKey)
 * @returns {string} Token CSRF
 */
export const generateCSRFToken = () => {
  const timestamp = Date.now()
  const random = Math.floor(Math.random() * 1000)
  const data = `${timestamp}${random}${import.meta.env.VITE_UNIQUE_KEY || 'default'}`
  
  // Simulación simple de hash (en producción usar librería crypto)
  return btoa(data).substring(0, 32)
}

/**
 * Validar token CSRF
 * @param {string} token - Token a validar
 * @param {string} sessionToken - Token almacenado en sesión
 * @returns {boolean} True si es válido
 */
export const validateCSRFToken = (token, sessionToken) => {
  return token === sessionToken && token.length === 32
}

/**
 * Encriptar datos sensibles (básico)
 * Nota: Para producción usar librería crypto robusta
 * @param {string} data - Datos a encriptar
 * @returns {string} Datos encriptados (base64)
 */
export const encryptData = (data) => {
  return btoa(encodeURIComponent(data))
}

/**
 * Desencriptar datos
 * @param {string} encrypted - Datos encriptados
 * @returns {string} Datos desencriptados
 */
export const decryptData = (encrypted) => {
  try {
    return decodeURIComponent(atob(encrypted))
  } catch (e) {
    console.error('Error desencriptando datos:', e)
    return ''
  }
}

/**
 * Validar sesión activa
 * @param {object} user - Objeto usuario
 * @returns {boolean} True si sesión es válida
 */
export const validateSession = (user) => {
  return user && user.account && user.account.length > 0
}

/**
 * Sanitizar URL
 * @param {string} url - URL a sanitizar
 * @returns {string} URL sanitizada
 */
export const sanitizeURL = (url) => {
  try {
    const urlObj = new URL(url)
    // Solo permitir http y https
    if (!['http:', 'https:'].includes(urlObj.protocol)) {
      return ''
    }
    return urlObj.toString()
  } catch (e) {
    return ''
  }
}

/**
 * Validar rango de IP (básico)
 * @param {string} ip - IP a validar
 * @returns {boolean} True si es válida
 */
export const validateIP = (ip) => {
  const ipv4Regex = /^(\d{1,3}\.){3}\d{1,3}$/
  const ipv6Regex = /^([\da-f]{0,4}:){2,7}[\da-f]{0,4}$/i
  
  if (ipv4Regex.test(ip)) {
    const parts = ip.split('.')
    return parts.every(part => parseInt(part) <= 255)
  }
  
  return ipv6Regex.test(ip)
}

/**
 * Rate limiting - Verificar si se excedió el límite
 * @param {string} key - Clave única (ej: userId, IP)
 * @param {number} limit - Límite de intentos
 * @param {number} windowMs - Ventana de tiempo en ms
 * @returns {boolean} True si se excedió el límite
 */
export const checkRateLimit = (key, limit = 5, windowMs = 60000) => {
  const storage = window.localStorage
  const now = Date.now()
  const storageKey = `rateLimit_${key}`
  
  let attempts = JSON.parse(storage.getItem(storageKey) || '[]')
  
  // Limpiar intentos antiguos
  attempts = attempts.filter(timestamp => now - timestamp < windowMs)
  
  if (attempts.length >= limit) {
    return true // Se excedió el límite
  }
  
  attempts.push(now)
  storage.setItem(storageKey, JSON.stringify(attempts))
  
  return false
}

/**
 * Validar contenido de formulario
 * @param {object} formData - Datos del formulario
 * @param {array} requiredFields - Campos requeridos
 * @returns {object} { isValid: boolean, errors: array }
 */
export const validateFormData = (formData, requiredFields = []) => {
  const errors = []
  
  requiredFields.forEach(field => {
    if (!formData[field] || formData[field].toString().trim() === '') {
      errors.push(`El campo ${field} es requerido`)
    }
  })
  
  return {
    isValid: errors.length === 0,
    errors
  }
}

/**
 * Obtener IP del cliente (desde headers)
 * Soporta Cloudflare y proxies
 * @param {object} headers - Headers de la solicitud
 * @returns {string} IP del cliente
 */
export const getClientIP = (headers = {}) => {
  return (
    headers['cf-connecting-ip'] ||
    headers['x-forwarded-for']?.split(',')[0] ||
    headers['x-real-ip'] ||
    'unknown'
  )
}

export default {
  sanitizeInput,
  validateEmail,
  validateLogin,
  validatePassword,
  validateAmount,
  generateCSRFToken,
  validateCSRFToken,
  encryptData,
  decryptData,
  validateSession,
  sanitizeURL,
  validateIP,
  checkRateLimit,
  validateFormData,
  getClientIP
}
