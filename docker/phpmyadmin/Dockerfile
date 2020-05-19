FROM phpmyadmin/phpmyadmin

# Add volume for sessions to allow session persistence
VOLUME /sessions

COPY uploads.ini /usr/local/etc/php/conf.d/phpmyadmin-upload-limit.ini

# We expose phpMyAdmin on port 80
EXPOSE 80
