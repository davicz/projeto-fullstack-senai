# /meu-projeto-fullstack/laravel-api/Dockerfile

# Usar uma imagem oficial do PHP 8.2 com FPM
FROM php:8.2-fpm

# Definir o diretório de trabalho padrão
WORKDIR /var/www/html

# Instalar dependências do sistema e extensões PHP necessárias para o Laravel
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    postgresql-client\
    zip \
&& docker-php-ext-install pdo pdo_pgsql mbstring exif pcntl bcmath gd xml

# Instalar o Composer (gerenciador de pacotes do PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar os arquivos existentes do projeto para dentro do contêiner
COPY . .

# Instalar as dependências do Laravel via Composer
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist

# Expor a porta 9000, onde o PHP-FPM escuta por padrão
EXPOSE 9000

# ... outras linhas do Dockerfile ...

# Copia os arquivos existentes do projeto para dentro do contêiner
COPY . .

# Instala as dependências do Laravel via Composer
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist

# --- INÍCIO DA CORREÇÃO DE PERMISSÕES ---
# Muda o dono de todas as pastas para o usuário do servidor web
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Dá as permissões corretas de escrita para o grupo
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
# --- FIM DA CORREÇÃO DE PERMISSÕES ---

# O comando para iniciar o servidor PHP-FPM quando o contêiner subir
CMD ["php-fpm"]