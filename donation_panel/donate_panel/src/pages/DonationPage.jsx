import { useTranslation } from 'react-i18next'
import { useDonationStore } from '../stores/donationStore'

export default function DonationPage() {
  const { t } = useTranslation()
  const { balance } = useDonationStore()

  return (
    <div className="animate-fade-in">
      <h1 className="text-3xl font-bold mb-8">{t('donation.title')}</h1>
      
      <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div className="bg-white rounded-lg shadow-md p-6">
          <h2 className="text-xl font-semibold mb-4">{t('balance.title')}</h2>
          <p className="text-3xl font-bold text-primary">{balance}</p>
          <p className="text-gray-600 mt-2">{t('balance.online')}</p>
        </div>

        <div className="bg-white rounded-lg shadow-md p-6">
          <h2 className="text-xl font-semibold mb-4">{t('donation.selectAmount')}</h2>
          <div className="grid grid-cols-2 gap-4">
            {[100, 500, 1000, 5000].map(amount => (
              <button
                key={amount}
                className="bg-primary text-white py-3 rounded-lg hover:bg-primary/90 transition font-semibold"
              >
                {amount} Coins
              </button>
            ))}
          </div>
        </div>
      </div>
    </div>
  )
}
