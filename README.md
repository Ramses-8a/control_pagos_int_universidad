# Control de Pagos - Aplicación Laravel

Este repositorio contiene una aplicación de control de pagos desarrollada con Laravel. A continuación, se detallan los pasos para configurar y ejecutar el proyecto en tu entorno local.

## Clonar el Repositorio

Para clonar este repositorio a tu PC, sigue estos pasos:

1. Abre una terminal o línea de comandos.
2. Navega hasta la carpeta donde deseas clonar el proyecto.
3. Ejecuta el siguiente comando:

```bash
git clone https://github.com/tu-usuario/control_pagos.git
```

4. Ingresa a la carpeta del proyecto:

```bash
cd control_pagos
```

## Configuración del Entorno Laravel

### Requisitos Previos

- PHP 8.1 o superior
- Composer
- Node.js y npm
- WSL (Windows Subsystem for Linux)
- Docker y Docker Compose

### Instalación de Dependencias

1. Copia el archivo de entorno:

```bash
cp .env.example .env
```

2. Instala las dependencias de PHP con Composer:

```bash
composer install
```

3. Genera la clave de la aplicación:

```bash
php artisan key:generate
```

4. Instala las dependencias de JavaScript:

```bash
npm install
```

## Configuración de WSL y Docker

### Configurar WSL

1. Habilita WSL en Windows:
   - Abre PowerShell como administrador y ejecuta:
   ```powershell
   wsl --install
   ```
   - Reinicia tu computadora después de la instalación.

2. Instala una distribución de Linux (recomendado Ubuntu):
   - Abre Microsoft Store
   - Busca "Ubuntu" e instala la versión más reciente

3. Configura tu usuario y contraseña en Ubuntu cuando se inicie por primera vez.

### Configurar Docker

1. Instala Docker Desktop para Windows desde [la página oficial de Docker](https://www.docker.com/products/docker-desktop/).

2. Durante la instalación, asegúrate de seleccionar la opción para usar WSL 2.

3. Después de instalar Docker Desktop, abre la aplicación y ve a Configuración > Resources > WSL Integration.
   - Habilita la integración con tu distribución de Linux instalada.

### Ejecutar el Proyecto con Docker

1. Desde WSL, navega a la carpeta del proyecto:

```bash
cd /mnt/c/Users/PC/Desktop/Laravel/control_pagos
```

2. Inicia los contenedores de Docker:

```bash
./vendor/bin/sail up -d
```

Si es la primera vez que ejecutas Sail, puedes crear un alias para facilitar su uso:

```bash
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

3. Ejecuta las migraciones para crear las tablas en la base de datos:

```bash
sail artisan migrate
```

## Compilar Assets y Estilos de Tailwind

Para compilar los assets y cargar correctamente los estilos de Tailwind CSS, ejecuta:

```bash
npm run dev
```

Este comando compilará los archivos CSS y JavaScript, incluyendo los estilos de Tailwind, y se mantendrá en ejecución para detectar cambios en tiempo real.

## Acceder a la Aplicación

Una vez que los contenedores estén en ejecución y hayas compilado los assets, puedes acceder a la aplicación en tu navegador:

```
http://localhost
```

## Detener los Contenedores

Cuando hayas terminado de trabajar con la aplicación, puedes detener los contenedores de Docker:

```bash
sail down
```

---

## Solución de Problemas Comunes

- Si encuentras problemas con los permisos en WSL, asegúrate de que estás ejecutando los comandos con los permisos adecuados.
- Si los estilos de Tailwind no se cargan correctamente, verifica que estás ejecutando `npm run dev` y que no hay errores en la consola.
- Para problemas con Docker, verifica que Docker Desktop está en ejecución y que WSL 2 está configurado correctamente.
