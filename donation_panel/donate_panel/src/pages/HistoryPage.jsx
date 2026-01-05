import { useEffect } from 'react'
import { useTranslation } from 'react-i18next'
import { useDonationStore } from '../stores/donationStore'

export default function HistoryPage() {
  const { t } = useTranslation()
  const { conversions, loading, fetchHistory } = useDonationStore()

  useEffect(() => {
    fetchHistory()
  }, [fetchHistory])

  return (
    <div className="animate-fade-in">
      <h1 className="text-3xl font-bold mb-8">{t('navigation.history')}</h1>

      {loading ? (
        <div className="text-center py-8">
          <p>{t('common.loading')}</p>
        </div>
      ) : conversions.length === 0 ? (
        <div className="bg-white rounded-lg shadow-md p-8 text-center">
          <p className="text-gray-600">No hay historial disponible</p>
        </div>
      ) : (
        <div className="bg-white rounded-lg shadow-md overflow-hidden">
          <table className="w-full">
            <thead className="bg-gray-100">
              <tr>
                <th className="px-6 py-3 text-left">ID</th>
                <th className="px-6 py-3 text-left">Tipo</th>
                <th className="px-6 py-3 text-left">Monto</th>
                <th className="px-6 py-3 text-left">Fecha</th>
              </tr>
            </thead>
            <tbody>
              {conversions.map(item => (
                <tr key={item.id} className="border-t hover:bg-gray-50">
                  <td className="px-6 py-4">{item.id}</td>
                  <td className="px-6 py-4">{item.type}</td>
                  <td className="px-6 py-4">{item.amount}</td>
                  <td className="px-6 py-4">{item.date}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      )}
    </div>
  )
}
