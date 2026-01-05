# Panel de Donaciones - Vite + React

Panel moderno de donaciones construido con Vite, React y TailwindCSS.

## 🚀 Inicio Rápido

### Requisitos
- Node.js 16+
- npm o yarn

### Instalación

```bash
# Instalar dependencias
npm install

# Copiar variables de entorno
cp .env.example .env

# Iniciar servidor de desarrollo
npm run dev
```

El panel estará disponible en `http://localhost:3000`

## 📦 Estructura del Proyecto

```
src/
├── components/      # Componentes reutilizables
├── pages/          # Páginas principales
├── services/       # Servicios API
├── stores/         # Estado global (Zustand)
├── config/         # Configuración (i18n, etc)
├── locales/        # Traducciones (ES, EN, PT)
├── styles/         # Estilos globales
└── App.jsx         # Componente raíz
```

## 🔧 Configuración

### Variables de Entorno (.env)

```env
VITE_API_BASE_URL=http://localhost/backend/engine/
VITE_DEFAULT_LANGUAGE=es
```

### Métodos de Pago Soportados

- PayPal
- MercadoPago
- PagSeguro
- Banking
- PicPay
- WebMoney
- Payza
- Skrill
- Ualabis
- Weear
- G2APay

## 📚 Documentación

- **INTEGRATION_GUIDE.md** - Guía de integración con backend
- **DONATE_PANEL_VITE_PROMPT.md** - Prompt maestro del proyecto
- **VOTE_REWARD_README.md** - Sistema de votación
- **SECURITY_DOCUMENTATION.md** - Métodos de seguridad implementados

### Seguridad Integrada

El panel incluye todos los métodos de seguridad del proyecto original:
- ✅ Sanitización de entrada (vCode)
- ✅ Validación de datos (email, login, contraseña, cantidad)
- ✅ Protección CSRF
- ✅ Autenticación y sesiones
- ✅ Rate limiting
- ✅ Encriptación de datos
- ✅ Logging y auditoría

Ver `SECURITY_DOCUMENTATION.md` para detalles completos.

## 🌐 Idiomas Soportados

- Español (es)
- Inglés (en)
- Portugués (pt)

## 🏗️ Build para Producción

```bash
npm run build
npm run preview
```

## 📝 Licencia

Proyecto privado - Todos los derechos reservados
