# davehakkens
WordPress theme for the Dave Hakkens Community website

We wanted to have a place online which is truly ours, we are in control. No adds, open-source, build together in the way we want it to be. We started of building this thing, but invite you to help out and share feedback. It's build on Wordpress, we know. Not the best software to build a community on. But we like that it is a language many developers from all over the world speak. WHich makes it easy to work on together. And we are always looking for passionate developers that want to help out improving this place. If you are on of those, reach out to us in the forums at http://davehakkens.nl/community


It's running on Wordpress.
Couple of key plugins we use to make this community happen
- BBpress for the forums
- Buddypress for the community parts
- Mycred for gamification
- GD bbPress Attachments for forum attachments

## Getting started using Docker

Make sure you have docker installed with docker compose

To Run:

    docker-compose up -d

Then browse to http://localhost:8000/

To bring offline

    docker-compose down

When you first install, you will need to install plugins and select the Dave Hakkens theme. Plugins and theme selection should persist

Since the pages are part of the database, you will need to create the community page, and a login page (parented to the community page to not break links). This will enable you to get back in once you restart the container
