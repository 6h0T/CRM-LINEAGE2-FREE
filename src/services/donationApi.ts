/**
 * API Service para conectar con el backend PHP del donation_panel
 */

const API_BASE = '/donation_panel';

interface ApiResponse<T = any> {
  success: boolean;
  message?: string;
  data?: T;
  error?: string;
}

interface RegisterData {
  login: string;
  email: string;
  email2: string;
  pass: string;
  pass2: string;
  captcha?: string;
  suffix?: string;
  nosuffix?: number;
  key: string;
}

interface RecoverData {
  email: string;
  captcha?: string;
  key: string;
}

interface LoginData {
  login: string;
  pass: string;
  key: string;
}

/**
 * Obtiene la session key del servidor
 */
export async function getSessionKey(): Promise<string> {
  try {
    const response = await fetch(`${API_BASE}/`, {
      credentials: 'include'
    });
    const html = await response.text();
    
    // Extraer la key de la sesión del HTML
    const match = html.match(/name=['"]key['"] value=['"]([^'"]+)['"]/);
    return match ? match[1] : '';
  } catch (error) {
    console.error('Error getting session key:', error);
    return '';
  }
}

/**
 * Registrar nueva cuenta
 */
export async function registerAccount(data: Omit<RegisterData, 'key'>): Promise<ApiResponse> {
  try {
    const key = await getSessionKey();
    
    const formData = new FormData();
    Object.entries({ ...data, key }).forEach(([k, v]) => {
      formData.append(k, String(v));
    });

    const response = await fetch(`${API_BASE}/engine/create_account.php`, {
      method: 'POST',
      body: formData,
      credentials: 'include'
    });

    const text = await response.text();
    
    // El backend PHP devuelve HTML con mensajes
    // Buscar indicadores de éxito o error
    if (text.includes('success') || text.includes('OK')) {
      return { success: true, message: 'Cuenta creada exitosamente' };
    } else {
      const errorMatch = text.match(/<[^>]*>([^<]+)<\/[^>]*>/);
      return { 
        success: false, 
        error: errorMatch ? errorMatch[1] : 'Error al crear cuenta'
      };
    }
  } catch (error) {
    return { 
      success: false, 
      error: error instanceof Error ? error.message : 'Error de conexión' 
    };
  }
}

/**
 * Recuperar contraseña
 */
export async function recoverPassword(data: Omit<RecoverData, 'key'>): Promise<ApiResponse> {
  try {
    const key = await getSessionKey();
    
    const formData = new FormData();
    Object.entries({ ...data, key }).forEach(([k, v]) => {
      formData.append(k, String(v));
    });

    const response = await fetch(`${API_BASE}/engine/recover.php`, {
      method: 'POST',
      body: formData,
      credentials: 'include'
    });

    const text = await response.text();
    
    if (text.includes('success') || text.includes('OK')) {
      return { success: true, message: 'Email de recuperación enviado' };
    } else {
      const errorMatch = text.match(/<[^>]*>([^<]+)<\/[^>]*>/);
      return { 
        success: false, 
        error: errorMatch ? errorMatch[1] : 'Error al recuperar contraseña'
      };
    }
  } catch (error) {
    return { 
      success: false, 
      error: error instanceof Error ? error.message : 'Error de conexión' 
    };
  }
}

/**
 * Iniciar sesión
 */
export async function login(data: Omit<LoginData, 'key'>): Promise<ApiResponse> {
  try {
    const key = await getSessionKey();
    
    const formData = new FormData();
    Object.entries({ ...data, key }).forEach(([k, v]) => {
      formData.append(k, String(v));
    });

    const response = await fetch(`${API_BASE}/engine/login.php`, {
      method: 'POST',
      body: formData,
      credentials: 'include'
    });

    const text = await response.text();
    
    if (text.includes('success') || text.includes('OK')) {
      return { success: true, message: 'Sesión iniciada' };
    } else {
      return { success: false, error: 'Credenciales inválidas' };
    }
  } catch (error) {
    return { 
      success: false, 
      error: error instanceof Error ? error.message : 'Error de conexión' 
    };
  }
}

/**
 * Cerrar sesión
 */
export async function logout(): Promise<ApiResponse> {
  try {
    const response = await fetch(`${API_BASE}/engine/logout.php`, {
      method: 'POST',
      credentials: 'include'
    });

    return { success: response.ok, message: 'Sesión cerrada' };
  } catch (error) {
    return { 
      success: false, 
      error: error instanceof Error ? error.message : 'Error de conexión' 
    };
  }
}

/**
 * Obtener información de donaciones (requiere UCP)
 */
export async function getDonationInfo(): Promise<ApiResponse> {
  try {
    const response = await fetch(`${API_BASE}/ucp/`, {
      credentials: 'include'
    });

    if (response.ok) {
      return { success: true, data: await response.text() };
    } else {
      return { success: false, error: 'No autenticado' };
    }
  } catch (error) {
    return { 
      success: false, 
      error: error instanceof Error ? error.message : 'Error de conexión' 
    };
  }
}

export default {
  registerAccount,
  recoverPassword,
  login,
  logout,
  getDonationInfo,
  getSessionKey
};
