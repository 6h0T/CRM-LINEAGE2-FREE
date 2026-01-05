# 🎯 Sistema de Donaciones Directas - Documentación Completa

## ✅ Implementación Completada

Se ha implementado un sistema de donaciones **sin necesidad de login** que acredita automáticamente los Donate Coins directamente al personaje especificado.

---

## 🚀 Características Principales

### ✨ Ventajas del Nuevo Sistema

1. **Sin Login Requerido**: Los usuarios pueden donar sin necesidad de crear cuenta en el UCP
2. **Acreditación Automática**: Los coins se entregan directamente al inventario del personaje
3. **Sin Transferencias Manuales**: No es necesario transferir coins desde el balance
4. **Validación de Personaje**: El sistema verifica que el personaje exista antes de procesar
5. **Diseño Moderno**: Interfaz React consistente con el diseño del sitio
6. **Backend Robusto**: Usa toda la lógica existente del UCP (pagos, webhooks, logs)

---

## 📋 Flujo de Usuario

### Paso a Paso

1. **Usuario accede a "Donaciones"**
   - No requiere estar logueado
   - Ve el formulario moderno

2. **Completa el formulario**
   - Nombre del Personaje
   - Cantidad de Donate Coins (mínimo 100)
   - Método de Pago (MercadoPago, PayPal, PagSeguro)

3. **Sistema valida el personaje**
   - Verifica que el personaje exista en la BD
   - Obtiene la cuenta asociada automáticamente

4. **Cálculo automático**
   - Muestra el precio según el método de pago
   - Aplica bonos si están configurados

5. **Procesamiento del pago**
   - Redirige a la pasarela de pago
   - Usuario completa el pago

6. **Webhook confirma el pago**
   - MercadoPago envía notificación
   - Sistema verifica el pago

7. **Acreditación automática**
   - Los coins se agregan directamente al inventario del personaje
   - No requiere acción adicional del usuario

---

## 🔧 Archivos Modificados/Creados

### Frontend

**`components/DonationPanel.tsx`**
- Agregado campo `charName` para nombre del personaje
- Actualizado para usar endpoint `create_direct_order`
- Validaciones de formulario completas
- Diseño consistente con el sitio

### Backend

**`donation_panel/ucp/engine/donate/create_direct_order.php`** ✨ NUEVO
- Endpoint que NO requiere login
- Valida que el personaje exista
- Obtiene la cuenta asociada automáticamente
- Crea la orden con flag `auto_credit = 1`
- Redirige a la pasarela de pago

**`donation_panel/ucp/ipn/mercadopago_webhook.php`** 🔄 MODIFICADO
- Detecta el flag `auto_credit`
- Si está activo, acredita directamente al personaje
- Inserta los coins en la tabla `items` del personaje
- Registra en el log de conversiones
- Mantiene compatibilidad con el sistema tradicional

---

## 💾 Estructura de Base de Datos

### Tabla: `site_donations`

Se agregó un nuevo campo:

```sql
ALTER TABLE site_donations
ADD auto_credit INT DEFAULT 0;
```

**Valores**:
- `0`: Acreditación tradicional (al balance de la cuenta)
- `1`: Acreditación directa al personaje

### Tabla: `items` (WORLD)

Los coins se insertan aquí cuando `auto_credit = 1`:

```sql
INSERT INTO items (owner_id, item_id, count, loc, loc_data)
VALUES (char_id, 4037, cantidad_coins, 'INVENTORY', '0')
```

**Campos importantes**:
- `owner_id`: ID del personaje
- `item_id`: 4037 (Donate Coin - ajustar según tu servidor)
- `count`: Cantidad de coins
- `loc`: 'INVENTORY'

---

## ⚙️ Configuración

### 1. Agregar Campo a la Base de Datos

Ejecuta en SQL Server Management Studio:

```sql
USE lin2site;
GO

-- Agregar campo auto_credit si no existe
IF NOT EXISTS (
    SELECT * FROM sys.columns 
    WHERE object_id = OBJECT_ID('site_donations') 
    AND name = 'auto_credit'
)
BEGIN
    ALTER TABLE site_donations
    ADD auto_credit INT DEFAULT 0;
    
    PRINT 'Campo auto_credit agregado exitosamente';
END
ELSE
BEGIN
    PRINT 'El campo auto_credit ya existe';
END
GO
```

### 2. Configurar Item ID del Donate Coin

Edita `donation_panel/ucp/ipn/mercadopago_webhook.php` línea 377:

```php
$donateItemId = 4037; // Cambiar por el ID de tu Donate Coin
```

Para encontrar el ID correcto:

```sql
SELECT item_id, name FROM etcitem WHERE name LIKE '%donate%' OR name LIKE '%coin%';
```

### 3. Verificar Configuración de MercadoPago

En `donation_panel/ucp/private/configs.php`:

```php
$MercadoPago['actived'] = 1;
$MercadoPago['access_token'] = 'TU_ACCESS_TOKEN';
$MercadoPago['webhook_secret'] = 'TU_WEBHOOK_SECRET';
$MercadoPago['testando'] = 1; // 1 = Sandbox, 0 = Producción
$MercadoPago['coin_price'] = 30; // 30 ARS = 1 coin
$MercadoPago['currency'] = 'ARS';
$MercadoPago['symbol'] = '$';

$autoDelivery = 1; // IMPORTANTE: Debe estar en 1
```

---

## 🧪 Pruebas

### Prueba Completa del Flujo

1. **Preparación**
   ```sql
   -- Verificar que el personaje existe
   SELECT char_id, char_name, account_name 
   FROM user_data 
   WHERE char_name = 'TuPersonaje';
   ```

2. **Realizar Donación**
   - Ir a http://localhost:3000
   - Click en "Donaciones"
   - Ingresar nombre del personaje
   - Seleccionar cantidad de coins (ej: 100)
   - Seleccionar MercadoPago
   - Click en "Continuar al Pago"

3. **Simular Pago (Sandbox)**
   - Completar el pago en MercadoPago Sandbox
   - Usar tarjeta de prueba: 5031 7557 3453 0604

4. **Verificar Acreditación**
   ```sql
   -- Ver la donación creada
   SELECT * FROM site_donations 
   WHERE account = 'TuCuenta' 
   ORDER BY data DESC;
   
   -- Verificar que auto_credit = 1
   -- Verificar que personagem = char_id correcto
   
   -- Ver los coins en el inventario
   SELECT * FROM items 
   WHERE owner_id = (SELECT char_id FROM user_data WHERE char_name = 'TuPersonaje')
   AND item_id = 4037;
   ```

5. **Revisar Logs**
   ```
   donation_panel/ucp/ipn/logs/mercadopago_YYYY-MM.txt
   ```

   Buscar:
   ```
   Modo auto-crédito activado
   ÉXITO: Coins acreditados directamente al personaje
   ```

---

## 🔍 Debugging

### Problema: "El personaje no existe"

**Causa**: El nombre del personaje no se encuentra en la BD

**Solución**:
```sql
-- Verificar el personaje
SELECT char_id, char_name, account_name 
FROM user_data 
WHERE char_name = 'NombreExacto';

-- Nota: El nombre es case-sensitive
```

### Problema: Los coins no se acreditan

**Causas posibles**:
1. `auto_credit` no está en 1
2. `autoDelivery` está desactivado
3. El `item_id` es incorrecto
4. El webhook no se ejecutó

**Solución**:
```sql
-- Verificar la donación
SELECT auto_credit, personagem, status, coins_entregues 
FROM site_donations 
WHERE protocolo = 'PROTOCOLO_AQUI';

-- auto_credit debe ser 1
-- personagem debe tener el char_id correcto
-- status debe ser 4 (entregado)
-- coins_entregues debe ser > 0
```

Revisar logs del webhook:
```
donation_panel/ucp/ipn/logs/mercadopago_YYYY-MM.txt
```

### Problema: Error al insertar en tabla items

**Causa**: Estructura de la tabla `items` diferente

**Solución**: Ajustar el INSERT en `mercadopago_webhook.php` línea 380-383 según tu estructura:

```php
// Verificar estructura de tu tabla items
SELECT TOP 1 * FROM items;

// Ajustar el INSERT según los campos que tengas
```

---

## 📊 Comparación: Sistema Tradicional vs Directo

| Característica | Sistema Tradicional | Sistema Directo |
|----------------|---------------------|-----------------|
| **Login requerido** | ✅ Sí | ❌ No |
| **Pasos del usuario** | 5-6 pasos | 3 pasos |
| **Transferencia manual** | ✅ Sí | ❌ No |
| **Acreditación** | Balance → Personaje | Directo al personaje |
| **Tiempo de entrega** | Manual | Automático |
| **Complejidad** | Alta | Baja |
| **UX** | Regular | Excelente |

---

## 🔐 Seguridad

### Validaciones Implementadas

1. **Validación de Personaje**
   - Verifica que el personaje exista en `user_data`
   - Obtiene la cuenta asociada automáticamente

2. **Validación de Pago**
   - Webhook valida firma de MercadoPago
   - Verifica monto y moneda
   - Previene duplicados

3. **Protección contra Fraude**
   - Solo acredita si el pago está `approved`
   - Registra todo en logs
   - Mantiene historial completo

4. **SQL Injection**
   - Usa `vCode()` para sanitizar inputs
   - Prepared statements en consultas

---

## 🎨 Personalización

### Cambiar Tasas de Conversión

Edita `components/DonationPanel.tsx` líneas 19-22:

```typescript
const conversionRates = {
  MercadoPago: { rate: 30, currency: 'ARS', symbol: '$' },
  PayPal_USD: { rate: 0.033, currency: 'USD', symbol: '$' },
  PagSeguro: { rate: 0.5, currency: 'BRL', symbol: 'R$' }
};
```

### Cambiar Cantidad Mínima

Edita `components/DonationPanel.tsx` línea 146:

```tsx
min="100"  // Cambiar a la cantidad mínima deseada
```

Y en `create_direct_order.php` línea 21:

```php
if($qtdCoins < 100) {  // Cambiar a la cantidad mínima deseada
```

### Agregar Más Métodos de Pago

1. Agregar en `DonationPanel.tsx`:
```tsx
<option value="NuevoMetodo">Nuevo Método (MONEDA)</option>
```

2. Agregar tasa de conversión:
```typescript
NuevoMetodo: { rate: X, currency: 'XXX', symbol: '$' }
```

3. Configurar en `configs.php` del UCP

---

## 📝 Notas Importantes

### ⚠️ Importante

1. **Item ID**: Asegúrate de configurar el `item_id` correcto del Donate Coin
2. **Auto Delivery**: Debe estar activado (`$autoDelivery = 1`)
3. **Webhook**: Debe estar configurado en MercadoPago
4. **Campo auto_credit**: Debe existir en la tabla `site_donations`

### 💡 Recomendaciones

1. **Prueba en Sandbox** antes de producción
2. **Monitorea los logs** regularmente
3. **Haz backup** de la BD antes de implementar
4. **Verifica las tasas** de conversión periódicamente
5. **Documenta** cualquier personalización

---

## 🆘 Soporte

### Logs Importantes

```
donation_panel/ucp/ipn/logs/mercadopago_YYYY-MM.txt
```

Contiene:
- Notificaciones recibidas
- Validaciones realizadas
- Acreditaciones exitosas
- Errores detallados

### Consultas SQL Útiles

```sql
-- Ver últimas donaciones
SELECT TOP 10 * FROM site_donations 
ORDER BY data DESC;

-- Ver donaciones pendientes
SELECT * FROM site_donations 
WHERE status = 0 OR status = 1;

-- Ver coins en inventario de un personaje
SELECT i.*, e.name 
FROM items i
JOIN etcitem e ON i.item_id = e.item_id
WHERE i.owner_id = (SELECT char_id FROM user_data WHERE char_name = 'NombrePersonaje')
AND i.item_id = 4037;

-- Ver log de conversiones
SELECT * FROM site_log_convertcoins 
ORDER BY cdata DESC;
```

---

## ✅ Checklist de Implementación

- [ ] Campo `auto_credit` agregado a `site_donations`
- [ ] Item ID del Donate Coin configurado correctamente
- [ ] `autoDelivery = 1` en configs.php
- [ ] MercadoPago configurado (access_token, webhook_secret)
- [ ] Webhook URL configurada en MercadoPago
- [ ] Tasas de conversión ajustadas
- [ ] Prueba en sandbox realizada exitosamente
- [ ] Logs monitoreados y funcionando
- [ ] Backup de BD realizado

---

## 🎉 Resultado Final

El sistema está completamente funcional y permite:

✅ Donaciones sin login  
✅ Acreditación automática directa al personaje  
✅ Experiencia de usuario simplificada  
✅ Backend robusto y seguro  
✅ Diseño moderno y consistente  
✅ Logs completos para debugging  
✅ Compatible con el sistema tradicional  

**El usuario solo necesita:**
1. Ingresar nombre del personaje
2. Seleccionar cantidad y método
3. Pagar

**Los coins llegan automáticamente al inventario del personaje.**
