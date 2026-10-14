# DIVM Educación Especial – Instalación (Laravel)

## 1. Copiar archivos
Copia estas carpetas dentro de `C:\laragon\www\dvim_educacion` y acepta **reemplazar**:
`app/`, `config/`, `public/`, `resources/`, `routes/`
(No borres `routes/console.php` ni `app/Http/Controllers/Controller.php`.)

Borra los controladores viejos vacíos de `app/Http/Controllers/` si existen:
SesionController, RegistroController, RecomendacionesController, InformacionController,
DistribucionController, AvancesController.

## 2. Archivo `.env`
Abre `.env.example.divm` (viene en el zip) y copia sus líneas a tu `.env`.
Lo mínimo:
```
APP_TIMEZONE=America/Mexico_City
DB_DATABASE=divm
SESSION_DRIVER=file
CACHE_STORE=file
```

## 3. Limpiar y arrancar
```
php artisan optimize:clear
php artisan serve
```
Abre http://127.0.0.1:8000 (recarga con Ctrl+F5).

## PDF
Botón rojo con ícono PDF en: Informes, Valoración, Avances, Planes y Recomendaciones.
Se abre en otra pestaña. Usa FPDF (ya incluida en `app/Libraries/fpdf`); no hay que instalar nada.
Diseño: membrete CENTRO DIVM + barra rosa con el título, igual que tu proyecto anterior.

## Verificación en dos pasos (código por correo)
1. El usuario escribe correo y contraseña.
2. Si tiene activada la verificación (columna `dos_factores_activado` = 1), se le manda un código de 6 dígitos.
3. Escribe el código (vence en 10 min, 5 intentos, reenvío cada 60 s) y entra.

- **Probar sin correo real:** deja `MAIL_MAILER=log`. El código aparece en `storage/logs/laravel.log`.
- **Correo real con Gmail:** usa las líneas MAIL_* de `.env.example.divm` con una contraseña de aplicación NUEVA.
- **Apagarla temporalmente para todos:** `DOS_PASOS=false` en el `.env`.
- **Por usuario:** Usuarios → Editar → interruptor "Verificación en dos pasos".

## Asistente del Informe
Pon tu clave NUEVA en `.env`: `GROQ_API_KEY=...` (la clave nunca llega al navegador).

## Estructura (MVC)
- Modelos:       app/Models/
- Controladores: app/Http/Controllers/ (+ PdfController, VerificacionController, ChatbotController)
- Vistas:        resources/views/ (*.blade.php) y layouts/ (app, menu, sidebar, tab)
- Rutas:         routes/web.php
- PDF:           app/Libraries/ (DivmPdf, Reportes, fpdf/)
- Correo:        app/Mail/CodigoVerificacion.php + resources/views/emails/codigo.blade.php
- Estilos:       public/css/divm.css (hoja única)
- Imágenes:      public/img/
