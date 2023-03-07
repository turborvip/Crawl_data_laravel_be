FROM php8.2:fpm 

RUN docker-php-ext-install mysqli pdo pdo_mysql

EXPOSE 9000

CMD ["php-fpm"]
