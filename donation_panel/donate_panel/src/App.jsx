import { BrowserRouter as Router, Routes, Route } from 'react-router-dom'
import { Toaster } from 'react-hot-toast'
import Layout from './components/Layout'
import DonationPage from './pages/DonationPage'
import OrdersPage from './pages/OrdersPage'
import TransfersPage from './pages/TransfersPage'
import HistoryPage from './pages/HistoryPage'

export default function App() {
  return (
    <Router>
      <Toaster position="top-right" />
      <Layout>
        <Routes>
          <Route path="/" element={<DonationPage />} />
          <Route path="/orders" element={<OrdersPage />} />
          <Route path="/transfers" element={<TransfersPage />} />
          <Route path="/history" element={<HistoryPage />} />
        </Routes>
      </Layout>
    </Router>
  )
}
