#!/bin/sh

# Cache
php artisan config:clear --quiet
php artisan cache:clear --quiet
php artisan route:cache --quiet
php artisan route:clear --quiet
php artisan view:clear --quiet

# 
#php artisan key:generate --quiet
#php artisan jwt:secret --quiet

# Permissions
# On Alpine Apache's user and groups are apache
#chgrp -R apache /var/www/bootstrap/cache
#chmod -R ug+rwx /var/www/bootstrap/cache
#chgrp -R apache /var/www/storage
#chmod -R ug+rwx /var/www/storage

# Migrations
php artisan migrate --force

php artisan vendor:publish --tag=log-viewer-assets --force

# Queue worker
php artisan queue:work --daemon &

# Launch the httpd in foreground
rm -rf /run/apache2/* || true && /usr/local/bin/apache2-foreground

