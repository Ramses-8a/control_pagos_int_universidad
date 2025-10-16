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

### Requisitos

Para ejecutar este proyecto, necesitas tener instalados:
- WSL (Windows Subsystem for Linux)
- Docker Desktop para Windows

### Configuración de WSL

Si necesitas instalar Ubuntu en WSL:

```bash
wsl --install -d Ubuntu
```

(Te pedirá que crees un usuario y contraseña. Nota: al escribir la contraseña en terminal, no se mostrará ningún carácter, pero sí se está registrando)

Una vez instalado, la terminal de WSL mostrará algo como:
```
usuario@PC:/mnt/wsl/docker-desktop-bind-mounts/Ubuntu/65f2e78d7ed5c0ed17294566c79acd50cabf20d37f4721f73138d5694561eb9d$
```

Para verificar si ya está instalado:

```bash
wsl -l -v
```

### Configuración de Laravel Sail

1. Instala Laravel Sail (desde la carpeta del proyecto):

```bash
composer require laravel/sail --dev
php artisan sail:install
```

2. Abre Docker Desktop

3. Otorga permisos para Docker:

```bash
sudo usermod -aG docker $USER
```

5. Inicia los contenedores de Docker:

```bash
./vendor/bin/sail up -d
```

6. Ejecuta las migraciones para crear las tablas en la base de datos:

```bash
./vendor/bin/sail artisan migrate
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

Para acceder a la base de datos, utiliza:

```
http://localhost:8081
```

## Ejecutar Comandos de Laravel con Sail

Para ejecutar comandos de Laravel, sustituye `php` por `./vendor/bin/sail`. Por ejemplo:

```bash
./vendor/bin/sail artisan migrate
./vendor/bin/sail artisan make:controller NuevoController
./vendor/bin/sail composer require paquete/nombre
```

## Compartir el Proyecto

Para compartir el proyecto, puedes usar Git como lo harías normalmente. Cuando alguien clone el repositorio, solo necesitará ejecutar:

```bash
./vendor/bin/sail up -d
```

para levantar los contenedores y tener el entorno funcionando.

## Detener los Contenedores

Cuando hayas terminado de trabajar con la aplicación, puedes detener los contenedores de Docker:

```bash
./vendor/bin/sail down
```

---

## Solución de Problemas Comunes

- Si encuentras problemas con los permisos en WSL, asegúrate de que estás ejecutando los comandos con los permisos adecuados.
- Si los estilos de Tailwind no se cargan correctamente, verifica que estás ejecutando `npm run dev` y que no hay errores en la consola.
- Para problemas con Docker, verifica que Docker Desktop está en ejecución y que WSL 2 está configurado correctamente.
