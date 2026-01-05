import { useEffect } from 'react'
import { useNavigate } from 'react-router-dom'
import { useAuthStore } from '../stores/authStore'
import Navigation from './Navigation'
import LoadingSpinner from './LoadingSpinner'

export default function Layout({ children }) {
  const { isAuthenticated, loading, checkAuth } = useAuthStore()
  const navigate = useNavigate()

  useEffect(() => {
    checkAuth()
  }, [checkAuth])

  if (loading) {
    return <LoadingSpinner />
  }

  if (!isAuthenticated) {
    return (
      <div className="flex items-center justify-center min-h-screen bg-gradient-to-br from-primary to-secondary">
        <div className="text-center text-white">
          <h1 className="text-4xl font-bold mb-4">Panel de Donaciones</h1>
          <p className="text-lg mb-8">Por favor, inicia sesión para continuar</p>
          <button
            onClick={() => navigate('/login')}
            className="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition"
          >
            Ir a Login
          </button>
        </div>
      </div>
    )
  }

  return (
    <div className="min-h-screen bg-gray-50">
      <Navigation />
      <main className="container mx-auto px-4 py-8">
        {children}
      </main>
    </div>
  )
}
