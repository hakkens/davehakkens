# davehakkens
WordPress theme for the Dave Hakkens Community website

We wanted to have a place online which is truly ours, we are in control. No
ads, open-source, build together in the way we want it to be. We started of
building this thing, but invite you to help out and share feedback. It's build
on Wordpress, we know. Not the best software to build a community on. But we
like that it is a language many developers from all over the world speak. Which
makes it easy to work on together. And we are always looking for passionate
developers that want to help out improving this place. If you are one of those,
reach out to us in the forums at http://davehakkens.nl/community

It's running on Wordpress.
Couple of key plugins we use to make this community happen
- BBpress for the forums
- Buddypress for the community parts
- Mycred for gamification
- GD bbPress Attachments for forum attachments

## Getting started using Docker

The theme has some dependencies that need to be installed before you will be
able to test anything. A script has been created which automates some of this
setup for you.

*NOTE*: The setup script does a destructive change to the current docker
containers that you have already built. This means you lose all the data and
start from scratch again.

To run the setup script, should just need to run

    ./setup.sh

Currently only works on Mac/Linux (sorry windows users)

Once everything has completed (might take a minute or so), then you should be
safe to browse to http://localhost:8001/

If you change something in the congif of the docker image, you might need to
restart all the containers. You can do this by running:

    docker-compose down
    docker-compose up -d

After install, you will probably need to do the following:

 - WP admin -> Plugins -> Buddypress -> Settings -> Enable Private Messaging
 - Enable the dave hakkens theme

### Things to help you debug

#### Pulling up a bash prompt

Sometimes being able to get root access to a machine is nice. You can do this
on the wordpress image by running:

    docker exec -it davehakkens_wordpress /bin/bash

#### Accessing the database

You can do this a couple of ways. If you're happy to run commands directly on
mysql server, you will need to log into the container and run mysql. This can
be done by:

    docker exec -it davehakkens_database /bin/bash
    mysql -p wordpress
    # this prompts for a password. Default is "somewordpress"

This image also includes a php myadmin install. To get to the phpmyadmin page,
make sure the image(s) are running and goto http://localhost:8002/. Put `db` in
the Database field, `root` as user, and the default database password in the
password (currently `somewordpress`)

### TODO

 - Automate enable private messaging. Can't find a bp function, so might need
   to edit the option\_value under wp\_options table
 - Automate the activation of the theme (setup.sh)
 - Automate creation of pages and buddypress mapping (login + community,
   parented to community so static links don't break)
