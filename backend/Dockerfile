FROM php:8.2-apache

# Enable PHP PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Copy PHP API to Apache web root
COPY api.php /var/www/html/

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
