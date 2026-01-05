import axios from 'axios'

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL

const api = axios.create({
  baseURL: API_BASE_URL,
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json'
  }
})

// Interceptor para manejar errores
api.interceptors.response.use(
  response => response,
  error => {
    console.error('API Error:', error)
    return Promise.reject(error)
  }
)

// Endpoints de Donaciones
export const donationAPI = {
  addDonation: (data) => api.post('donate/add_donation.php', data),
  getOrders: (page = 1, limit = 10) => 
    api.get('donate/get_orders.php', { params: { page, limit } }),
  getBalance: () => api.get('donate/get_balance.php'),
  transferCoins: (data) => api.post('donate/transfer_coins.php', data),
  convertCoins: (data) => api.post('donate/convert_coins.php', data),
  getHistory: (type = 'all', limit = 50) => 
    api.get('donate/get_history.php', { params: { type, limit } })
}

// Endpoints de Votación
export const voteAPI = {
  claimVote: (topsite_id) => api.post('vote/claim_vote.php', { topsite_id })
}

// Endpoints de Autenticación
export const authAPI = {
  checkAuth: () => api.get('auth/check.php'),
  logout: () => api.post('auth/logout.php')
}

export default api
