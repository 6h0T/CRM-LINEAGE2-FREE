# 🧪 Guía de Prueba del Sistema de Donaciones

## Pasos para Probar

### 1. Verificar que el servidor PHP esté corriendo

Abre el navegador y ve a:
```
http://localhost/donation_panel/ucp/
```

Deberías ver la página del UCP. Si no carga, el servidor PHP no está corriendo.

### 2. Abrir la Consola del Navegador

1. Presiona `F12` en el navegador
2. Ve a la pestaña "Console"
3. Deja la consola abierta

### 3. Ir a la Página de Donaciones

1. Abre `http://localhost:3000` (tu sitio React)
2. Click en "Donaciones"

### 4. Completar el Formulario

1. **Nombre del Personaje**: Ingresa un personaje que EXISTA en tu base de datos
   - Para verificar: 
   ```sql
   SELECT char_name FROM user_data WHERE char_name = 'TuPersonaje';
   ```

2. **Método de Pago**: Selecciona "MercadoPago (ARS)"

3. **Cantidad de Coins**: Ingresa mínimo 100

### 5. Presionar "Continuar al Pago"

Observa la consola del navegador. Deberías ver:

```
=== INICIO DONACIÓN ===
Datos del formulario: {charName: "...", qtdCoins: "...", metodo_pgto: "..."}
Creando formulario para enviar al UCP...
URL destino: /donation_panel/ucp/?module=donate&engine=create_direct_order
Formulario creado con campos: {...}
Enviando formulario...
```

### 6. ¿Qué debería pasar?

**Si todo funciona correctamente:**
- La página se redirige a `/donation_panel/ucp/?module=donate&engine=create_direct_order`
- El sistema valida el personaje
- Te redirige a MercadoPago para pagar

**Si hay un error:**
- Verás un mensaje de error en la página del UCP
- O la página se quedará en blanco

---

## Problemas Comunes

### Problema 1: "No sucede nada al hacer click"

**Posibles causas:**
1. JavaScript tiene un error
2. El formulario no se está enviando

**Solución:**
- Mira la consola del navegador (F12)
- Busca errores en rojo
- Comparte el error aquí

### Problema 2: "Página en blanco después de hacer click"

**Causa:** El servidor PHP no está corriendo o la ruta es incorrecta

**Solución:**
```bash
# Verifica que XAMPP/WAMP esté corriendo
# Verifica que puedas acceder a:
http://localhost/donation_panel/ucp/
```

### Problema 3: "Error: El personaje no existe"

**Causa:** El personaje ingresado no está en la base de datos

**Solución:**
```sql
-- Verifica que el personaje exista
SELECT char_id, char_name, account_name 
FROM user_data 
WHERE char_name = 'NombreExacto';

-- Nota: El nombre es case-sensitive
```

### Problema 4: "Error: Access denied"

**Causa:** El archivo `create_direct_order.php` requiere login

**Solución:** Verifica que la línea 10 del archivo esté comentada:
```php
// if($logged != 1) { fim('Access denied!', 'RELOAD'); }
```

### Problema 5: "404 Not Found"

**Causa:** El archivo no existe o la ruta es incorrecta

**Solución:** Verifica que el archivo exista en:
```
donation_panel/ucp/engine/donate/create_direct_order.php
```

---

## Debugging Avanzado

### Ver qué está recibiendo el servidor PHP

Agrega esto al inicio de `create_direct_order.php` (después de la línea 7):

```php
// DEBUG: Ver qué datos llegan
file_put_contents(__DIR__ . '/../../debug_donation.txt', 
    date('Y-m-d H:i:s') . "\n" . 
    "POST: " . print_r($_POST, true) . "\n" .
    "GET: " . print_r($_GET, true) . "\n" .
    "---\n",
    FILE_APPEND
);
```

Luego intenta hacer una donación y revisa el archivo:
```
donation_panel/ucp/debug_donation.txt
```

### Probar el endpoint directamente

Crea un archivo HTML de prueba:

```html
<!DOCTYPE html>
<html>
<head>
    <title>Test Donación</title>
</head>
<body>
    <h1>Test de Donación Directa</h1>
    <form method="POST" action="http://localhost/donation_panel/ucp/?module=donate&engine=create_direct_order">
        <label>Nombre del Personaje:</label>
        <input type="text" name="charName" value="TestChar" required><br><br>
        
        <label>Cantidad de Coins:</label>
        <input type="number" name="qtdCoins" value="100" required><br><br>
        
        <label>Método de Pago:</label>
        <select name="metodo_pgto">
            <option value="MercadoPago">MercadoPago</option>
        </select><br><br>
        
        <button type="submit">Enviar</button>
    </form>
</body>
</html>
```

Guarda como `test_donation.html` y ábrelo en el navegador.

---

## Checklist de Verificación

Antes de probar, verifica:

- [ ] XAMPP/WAMP está corriendo
- [ ] Puedes acceder a `http://localhost/donation_panel/ucp/`
- [ ] El archivo `create_direct_order.php` existe en la carpeta correcta
- [ ] La línea de validación de login está comentada
- [ ] El personaje que vas a usar existe en la BD
- [ ] El servidor React está corriendo (`npm run dev`)
- [ ] La consola del navegador está abierta (F12)

---

## Información para Reportar

Si sigue sin funcionar, comparte:

1. **Consola del navegador**: Copia todos los mensajes que aparecen
2. **URL actual**: ¿A qué URL te redirige?
3. **Mensaje de error**: Si hay algún mensaje visible
4. **Servidor PHP**: ¿Está corriendo? ¿Puedes acceder al UCP?
5. **Archivo debug**: Si agregaste el código de debugging, comparte el contenido

---

## Siguiente Paso

Prueba ahora con estos pasos y comparte:
- Los mensajes de la consola del navegador
- Cualquier error que veas
- A qué URL te redirige (si es que redirige)
