# Use an official WordPress runtime as a parent image
FROM wordpress

# Install unzip
# Install libmcrypt
# Install mcrypt-1.0.1 PHP extension
# Enable mcrypt extension
# Create a PHP ini from default production ini
# Update the upload file size maximum so theme can be installed from wordpress.
RUN apt-get update && apt-get install -y unzip libmcrypt-dev \
    && pecl install mcrypt-1.0.1 \
    && docker-php-ext-enable mcrypt \
    && cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini \
    && sed -i "s/upload_max_filesize = 2M/upload_max_filesize = 50M/" $PHP_INI_DIR/php.ini

# Retrieve theme dependent plugins
# Retrieve theme
# Extract plugins to WordPress plugins directory
# Extract theme to WordPress themes directory
# Remove default plugins
# Remove zip files downloaded
# Remove theme repository and zip file
# Set permissions of plugins directories
RUN curl https://downloads.wordpress.org/plugin/bbpress.2.5.14.zip -o /usr/src/wordpress/wp-content/plugins/bbpress.zip \
    && curl https://downloads.wordpress.org/plugin/buddypress.3.2.0.zip -o /usr/src/wordpress/wp-content/plugins/buddypress.zip \
    && curl https://downloads.wordpress.org/plugin/mycred.1.7.9.7.zip -o /usr/src/wordpress/wp-content/plugins/mycred.zip \
    && curl https://downloads.wordpress.org/plugin/gd-bbpress-attachments.zip -o /usr/src/wordpress/wp-content/plugins/gd-bbpress-attachments.zip \
    && unzip -d /usr/src/wordpress/wp-content/plugins/ /usr/src/wordpress/wp-content/plugins/bbpress.zip \
    && unzip -d /usr/src/wordpress/wp-content/plugins/ /usr/src/wordpress/wp-content/plugins/buddypress.zip \
    && unzip -d /usr/src/wordpress/wp-content/plugins/ /usr/src/wordpress/wp-content/plugins/mycred.zip \
    && unzip -d /usr/src/wordpress/wp-content/plugins/ /usr/src/wordpress/wp-content/plugins/gd-bbpress-attachments.zip \
    && rm -r /usr/src/wordpress/wp-content/plugins/akismet \
    && rm /usr/src/wordpress/wp-content/plugins/hello.php \
    /usr/src/wordpress/wp-content/plugins/bbpress.zip \
    /usr/src/wordpress/wp-content/plugins/buddypress.zip \
    /usr/src/wordpress/wp-content/plugins/mycred.zip \
    /usr/src/wordpress/wp-content/plugins/gd-bbpress-attachments.zip \
    && chown -R www-data:www-data /usr/src/wordpress/wp-content/plugins/