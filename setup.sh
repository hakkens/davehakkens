#!/bin/bash
function wp-cli() {
  docker run -it --rm --volumes-from $CONTAINER --network container:$CONTAINER --user=$WWW_UID wordpress:cli ${@:1}
}

docker-compose down
docker volume rm -f davehakkens_wp_data
docker volume rm -f davehakkens_db_data
docker-compose build
docker-compose up -d --force-recreate

CONTAINER=$(docker ps --filter "name=davehakkens_wordpress" --format="{{.Names}}")
WWW_UID=$(docker run --rm davehakkens_wordpress cat /etc/group | grep www-data | cut -d ":" -f 3)
sleep 10
wp-cli core install --url=localhost:8001 --title=DaveHakkens --admin_user=wordpress --admin_password=wordpress --admin_email=davehakkens@example.com
wp-cli rewrite structure '/%category%/%postname%/'
wp-cli plugin install bbpress --activate
wp-cli plugin install buddypress --activate
