import { useEffect } from 'react'
import { useTranslation } from 'react-i18next'
import { useDonationStore } from '../stores/donationStore'

export default function OrdersPage() {
  const { t } = useTranslation()
  const { orders, loading, fetchOrders } = useDonationStore()

  useEffect(() => {
    fetchOrders()
  }, [fetchOrders])

  return (
    <div className="animate-fade-in">
      <h1 className="text-3xl font-bold mb-8">{t('navigation.orders')}</h1>

      {loading ? (
        <div className="text-center py-8">
          <p>{t('common.loading')}</p>
        </div>
      ) : orders.length === 0 ? (
        <div className="bg-white rounded-lg shadow-md p-8 text-center">
          <p className="text-gray-600">{t('common.loading')}</p>
        </div>
      ) : (
        <div className="bg-white rounded-lg shadow-md overflow-hidden">
          <table className="w-full">
            <thead className="bg-gray-100">
              <tr>
                <th className="px-6 py-3 text-left">{t('donation.title')}</th>
                <th className="px-6 py-3 text-left">Fecha</th>
                <th className="px-6 py-3 text-left">Monto</th>
                <th className="px-6 py-3 text-left">Estado</th>
              </tr>
            </thead>
            <tbody>
              {orders.map(order => (
                <tr key={order.order_id} className="border-t hover:bg-gray-50">
                  <td className="px-6 py-4">{order.order_id}</td>
                  <td className="px-6 py-4">{order.date}</td>
                  <td className="px-6 py-4">{order.amount}</td>
                  <td className="px-6 py-4">
                    <span className={`px-3 py-1 rounded-full text-sm font-semibold ${
                      order.status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                    }`}>
                      {order.status}
                    </span>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  )
}
