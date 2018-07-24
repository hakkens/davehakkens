#!/bin/bash
function wp-cli() {
  docker run -it --rm --volumes-from $CONTAINER --network container:$CONTAINER --user=$WWW_UID wordpress:cli ${@:1}
}

# pull down any existing instances, remove volumes, and force a recreate of the images
docker-compose down
docker volume rm -f davehakkens_wp_data
docker volume rm -f davehakkens_db_data
docker-compose build
docker-compose up -d --force-recreate

CONTAINER=$(docker ps --filter "name=davehakkens_wordpress" --format="{{.Names}}")
WWW_UID=$(docker run --rm davehakkens_wordpress cat /etc/group | grep www-data | cut -d ":" -f 3)

#wait for the instance to boot up
sleep 10

#setup default options
wp-cli core install --url=localhost:8001 --title=DaveHakkens --admin_user=wordpress --admin_password=wordpress --admin_email=davehakkens@example.com
wp-cli rewrite structure '/%category%/%postname%/'

#install plugins
wp-cli plugin install bbpress --activate
wp-cli plugin install buddypress --activate
sleep 5

#create pages that we need for later
P_COMMUNITY=$(wp-cli post create --post_type=page --post_title="Community" --post_status=publish --porcelain)
echo "Community $P_COMMUNITY"
sleep 1
P_LOGIN=$(wp-cli post create --post_type=page --post_title="Login" --post_status=publish --post_parent=$P_COMMUNITY --porcelain)
echo "Login $P_LOGIN"
P_REGISTER=$(wp-cli post create --post_type=page --post_title="Register" --post_status=publish --post_parent=$P_COMMUNITY --porcelain)
echo "Register $P_REGISTER"
P_MEMBERS=$(wp-cli post create --post_type=page --post_title="Members" --post_status=publish --post_parent=$P_COMMUNITY --porcelain)
echo "Members $P_MEMBERS"
P_ACTIVATE=$(wp-cli post create --post_type=page --post_title="Activate" --post_status=publish --post_parent=$P_COMMUNITY --porcelain)
echo "Activate $P_ACTIVATE"
P_ACTIVITY=$(wp-cli post create --post_type=page --post_title="Activity" --post_status=publish --post_parent=$P_COMMUNITY --porcelain)
echo "Activity $P_ACTIVITY"

# wait for buddypress to initialise before we overwrite their settings
sleep 5
wp-cli option update users_can_register 1
wp-cli option update bp-active-components --format=json "{\"xprofile\":1,\"settings\":1,\"friends\":1,\"messages\":1,\"activity\":1,\"notifications\":1,\"blogs\":1,\"members\":1}"
wp-cli option update bp-pages --format=json "{\"activity\":$P_ACTIVITY,\"members\":$P_MEMBERS,\"register\":$P_REGISTER,\"activate\":$P_ACTIVATE}"

sleep 1
wp-cli theme activate davehakkens

sleep 5
wp-cli post meta update $P_LOGIN _wp_page_template login-page.php
wp-cli post meta update $P_COMMUNITY _wp_page_template page-redirect.php
