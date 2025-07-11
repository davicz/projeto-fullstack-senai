#!/bin/sh

# Garante que o Laravel tenha permissão para escrever nos logs e outros arquivos.
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Inicia o processo do PHP-FPM em segundo plano para que ele possa receber requisições.
php-fpm &

# Cria o arquivo de log se ele não existir (para o comando 'tail' não falhar).
touch /var/www/html/storage/logs/laravel.log

# "Segue" o arquivo de log em tempo real e joga qualquer novo conteúdo diretamente
# na saída do contêiner, que nós veremos no nosso terminal.
# Este comando fica rodando e mantém o contêiner vivo.
tail -f /var/www/html/storage/logs/laravel.log