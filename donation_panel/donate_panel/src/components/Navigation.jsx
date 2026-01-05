import { Link } from 'react-router-dom'
import { useTranslation } from 'react-i18next'
import { useAuthStore } from '../stores/authStore'
import { LogOut, Home, ShoppingCart, Send, History } from 'lucide-react'

export default function Navigation() {
  const { t } = useTranslation()
  const { user, logout } = useAuthStore()

  return (
    <nav className="bg-white shadow-md">
      <div className="container mx-auto px-4 py-4">
        <div className="flex justify-between items-center">
          <Link to="/" className="text-2xl font-bold text-primary">
            {t('app.title')}
          </Link>

          <div className="flex gap-6 items-center">
            <Link to="/" className="flex items-center gap-2 text-gray-700 hover:text-primary transition">
              <Home size={20} />
              {t('navigation.donations')}
            </Link>
            <Link to="/orders" className="flex items-center gap-2 text-gray-700 hover:text-primary transition">
              <ShoppingCart size={20} />
              {t('navigation.orders')}
            </Link>
            <Link to="/transfers" className="flex items-center gap-2 text-gray-700 hover:text-primary transition">
              <Send size={20} />
              {t('navigation.transfers')}
            </Link>
            <Link to="/history" className="flex items-center gap-2 text-gray-700 hover:text-primary transition">
              <History size={20} />
              {t('navigation.history')}
            </Link>

            <div className="border-l pl-6">
              <span className="text-sm text-gray-600 mr-4">{user?.account}</span>
              <button
                onClick={logout}
                className="flex items-center gap-2 text-red-600 hover:text-red-700 transition"
              >
                <LogOut size={20} />
                {t('navigation.logout')}
              </button>
            </div>
          </div>
        </div>
      </div>
    </nav>
  )
}
