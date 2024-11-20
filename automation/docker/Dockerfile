FROM php:8.3.7-apache

# Télécharger et configurer l'installateur d'extensions PHP
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions

# Installer des dépendances système
RUN apt-get update && apt-get install -y \
    acl \
    libicu-dev \
    nano \
    wget \
    zip \
    git \
    curl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Activer les modules Apache
RUN a2enmod \
    rewrite \
    expires \
    headers

# Installer les extensions PHP nécessaires
RUN install-php-extensions \
    pdo_mysql \
    intl \
    apcu \
    xdebug \
    opcache

ADD opcache.ini $PHP_INI_DIR/conf.d/

# Configurer Xdebug
RUN echo 'xdebug.mode=develop,debug,coverage' >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo 'xdebug.client_host=host.docker.internal' >> /usr/local/etc/php/conf.d/xdebug.ini

# Copier et activer php.ini (ajustez si nécessaire)
RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini \
    && echo 'short_open_tag = Off' >> /usr/local/etc/php/php.ini

# Installer Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get install -y curl \
    && curl -fsSL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs



# Installer les dépendances front-end
WORKDIR /var/www/html

# RUN npm install


# Copier uniquement les fichiers nécessaires à l'environnement
# ATTENTION : Ne copiez pas tout le contexte local si vous avez une installation existante.
COPY ./automation/docker/default-vhost.conf /etc/apache2/sites-available/default-vhost.conf

# Activer la configuration Apache personnalisée
RUN a2dissite 000-default.conf \
    && a2ensite default-vhost.conf

# Ajouter un script d'entrée Docker
COPY ./automation/scripts/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Exposer le port HTTP
EXPOSE 80

# Commande par défaut
CMD ["/usr/local/bin/docker-entrypoint.sh"]
