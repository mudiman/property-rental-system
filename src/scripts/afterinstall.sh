#!/bin/bash
sudo find /home/ubuntu/smoor/bootstrap/cache -type d -exec chmod 777 {} \;
sudo find /home/ubuntu/smoor/storage -type d -exec chmod 777 -R {} \;
sudo chown www-data:www-data /home/ubuntu/smoor/storage/oauth-private.key;
sudo chown www-data:www-data /home/ubuntu/smoor/storage/oauth-public.key;
sudo su ubuntu -c 'composer install -d /home/ubuntu/smoor';
sudo su ubuntu -c 'php /home/ubuntu/smoor/artisan view:clear';
sudo su ubuntu -c 'php /home/ubuntu/smoor/artisan cache:clear';
sudo su ubuntu -c 'php /home/ubuntu/smoor/artisan config:cache';
sudo npm install --prefix /home/ubuntu/smoor/nodejs
sudo service nginx restart && sudo service php7.0-fpm restart && sudo service supervisor restart;
