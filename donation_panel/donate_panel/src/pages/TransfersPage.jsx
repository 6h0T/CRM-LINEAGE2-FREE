import { useTranslation } from 'react-i18next'

export default function TransfersPage() {
  const { t } = useTranslation()

  return (
    <div className="animate-fade-in">
      <h1 className="text-3xl font-bold mb-8">{t('transfer.title')}</h1>

      <div className="bg-white rounded-lg shadow-md p-8 max-w-md">
        <form className="space-y-4">
          <div>
            <label className="block text-sm font-semibold mb-2">{t('transfer.destination')}</label>
            <input
              type="text"
              placeholder="Nombre de cuenta"
              className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
            />
          </div>

          <div>
            <label className="block text-sm font-semibold mb-2">{t('transfer.enterAmount')}</label>
            <input
              type="number"
              placeholder="Cantidad"
              className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary"
            />
          </div>

          <button
            type="submit"
            className="w-full bg-primary text-white py-2 rounded-lg hover:bg-primary/90 transition font-semibold"
          >
            {t('transfer.confirm')}
          </button>
        </form>
      </div>
    </div>
  )
}
