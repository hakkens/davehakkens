#!/bin/sh

container_name=`docker ps --format "{{.Image}} {{.Names}}" | grep davehakkens_wordpress | cut -d " " -f 2`

if [ -z $container_name ]; then
  echo "Need to be running an instance of davehakkens_wordpress docker image to continue"
  exit 1
fi

alias wp="docker run -it --rm --volumes-from $container_name --network container:$container_name wordpress:cli"
wp core install --url="localhost:8000" --title="(DEV)Dave Hakkens" --admin_user=wordpress --admin_password=wordpress --admin_email=test@example.com --skip-email
wp rewrite structure '/%category%/%postname%/'
wp plugin install bbpress --activate
wp plugin install buddypress --activate
wp plugin install mycred --activate
wp plugin install give --activate
wp plugin install gd-bbpress-attachments --activate
wp plugin install bp-profile-search --activate
wp plugin install admin-menu-editor --activate
wp plugin install advanced-custom-fields --activate
