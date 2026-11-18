# 1. Imagem oficial com PHP 8.2 e servidor Apache
FROM php:8.2-apache

# 2. Instala as extensões para o conexao.php funcionar com MySQL/MariaDB
RUN docker-php-ext-install pdo pdo_mysql mysqli

# 3. Define a pasta padrão do Apache como diretório de trabalho
WORKDIR /var/www/html

# 4. Copia o front-end e a pasta backend/Api/ mantendo a estrutura exata
COPY . /var/www/html/

# 5. Garante que o Apache tenha permissão para ler os arquivos e gerenciar sessões
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# 6. Expõe a porta padrão HTTP
EXPOSE 80