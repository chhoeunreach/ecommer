#!/bin/sh
set -e

php artisan config:cache
# Not route:cache: this app's shipped routes/admin.php (and others) register
# the same route name twice in many places -- Route::resource(...) followed
# by a manual override of edit/destroy with a different URI but the same
# name. Harmless without caching (the later registration just shadows the
# earlier one for name-based lookups), but route:cache's serialization step
# rejects any duplicate name outright. Production's k8s deployment command
# already only runs config:cache for the same reason -- kept in sync here.
php-fpm -D
nginx -g 'daemon off;'
