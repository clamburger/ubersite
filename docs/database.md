# ÜberSite Database Information

## For end users

In general, you shouldn't have to worry about the database. The setup process will create everything for you and you shouldn't have to touch it after that.

The only instance in which you might have to worry about things is if you decide to upgrade ÜberSite while keeping existing data. You should keep in mind that ÜberSite isn't really designed for that, and there's no user-friendly way of doing so. If you still want to, follow the first two sections below.

## For developers

### After you first clone the repo

The first thing you should do is run the setup process for ÜberSite, which will populate the MySQL tables.

After that, you'll need to do is set up the config for [dbv](http://dbv.vizuina.com). Go into `libraries/dbv`, copy `config.php.sample` to `config.php` and edit the `DBV_USERNAME` and `DBV_PASSWORD` constants to stop unauthorized people from mucking about in the database.

You can navigate to `http://[ubersite domain]/libraries/dbv` to test that the configuration is working. You should see a list of tablets with YES beside each one.

### Synching your database with remote changes

Let's say that you've done a `git pull` and there are some new database changes. Updating your local schema is easy; navigate to dbv in your browser and you should see one or more revisions in blue. Make sure all the new revisions are checked (they should be checked by default) and hit the "**Run selected revisions**" button. You're done!

*See also: [Official Documentation](http://dbv.vizuina.com/documentation/#usage-revisions-receive)*

### Updating your database locally and pushing the changes

What you need to do depends on what you've done to the database.

#### I've created a new table 

Open dbv and have a look at the list of tables on the left; your new table should have a red **NO** in the "On disk" column. Select the checkbox next to it, and press 'the "Export to disk" button at the bottom. Once you've done that, hit the black "Push all table schemas" button. You **do not** need to write an incremental SQL query for table creation.

#### I've altered an existing table

Open dbv and hit the "Push all table schemas" button. It should go through successfully and pop up a message with the new revision number. Take note of where it tells you to put the incremental SQL queries. Writing these queries is important; if you don't write them, database upgrades will fail. The official documentation explains this [quite well](http://dbv.vizuina.com/documentation/#usage-revisions-create) if you aren't familiar with manually altering tables.

If you can't remember exactly what you changed, a quick `git diff setup/database.sql` after pressing the black button should show you the difference.

#### I've done both of the above

Just follow the steps in both sections (do the "created a new table" section first). You only have to "push all table schemas" **once**.

#### I've dropped a table

Follow the "altered an existing table" section. When writing your incremental queries, make sure you use `DROP IF EXISTS`. Once that's done, delete the dropped database from `/libraries/dbv/data/schema`.