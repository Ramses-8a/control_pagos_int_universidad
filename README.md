name: Despliegue FTP Laravel

on:
  push:
    branches:
      - main  # Se ejecuta cuando haces push a la rama main

jobs:
  web-deploy:
    name: 🎉 Deploy
    runs-on: ubuntu-latest
    steps:
    - name: 🚚 Obtener el código más reciente
      uses: actions/checkout@v4
    - name: 🔨 Instalar PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2' # Asegúrate que coincida con tu servidor
    - name: 📦 Instalar Dependencias de Composer
      run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist
    # 2. Instalar Node y compilar estilos (Frontend/Tailwind)
    # Esto genera la carpeta 'public/build' con tus estilos bonitos
    - name: ⚡ Instalar Node y Compilar Assets
      run: |
        npm install
        npm run build
    # 3. Subir todo por FTP
    - name: 📂 Subir archivos por FTP
      uses: SamKirkland/FTP-Deploy-Action@v4.3.5
      with:
        server: ${{ secrets.FTP_SERVER }}
        username: ${{ secrets.FTP_USERNAME }}
        password: ${{ secrets.FTP_PASSWORD }}
        port: 21
        # Excluimos cosas que no queremos subir al servidor
        exclude: |
          **/.git*
          **/.git*/**
          **/node_modules/**
          **/tests/**
          .env.example
          .editorconfig
