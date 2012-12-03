# &Uuml;bersite

An internal website designed for use on [&Uuml;bertweak](http://ubertweak.org.au) and [related camps](http://crutech.org.au). 

## Requirements

* A web server with `mod_rewrite` (or compatible extension) enabled. Apache 2.2 or above is highly recommended.
* A MySQL server.
* PHP 5.4 (may work on 5.3, but I wouldn't count on it).
* The [MySQLi](http://php.net/mysqli) extension for PHP must be enabled.

#### Non-core requirements

* [GD](http://php.net/manual/en/book.image.php) or [ImageMagick](http://php.net/imagick) is required for thumbnail generation.
* [Exif](http://php.net/exif) is required for photo uploads.
* [SSH2](http://php.net/ssh2) is required if you want to use SSH authentication.
* [LDAP](http://php.net/ldap) is required if you want to use LDAP authentication.

## Installation

Very little setup is required; just drag the files into a directory of your choice and go. Note that at this time, from the web browser perspective, the files must be in the domain root. Whether you accomplish this by setting by a vhost or simply placing the files in the webserver root is up to you.

Once you've copied the files, you should be able to access the website in your browser. You'll have to go through a brief setup process which isn't overly complicated.

By default, &Uuml;berSite has developer mode enabled. You will want to disable this at some point by going into `camp-data/config/config.json` and setting `developerMode` to `false`.

Chances are you'll want to change what items show up in the menu bar. You can do this by going into `camp-data/config/menu.json` and changing the `*requirements` field for the relevant menu items to `false`.

### That's not very intuitive

Welcome to &Uuml;berSite, enjoy your stay. We're working on making things easier, but it's a slow process.

## Things to be aware of

### Authentication types
SSH authentication barely works, and LDAP authentication doesn't really work at all. I really wouldn't recommend using them. Just stick with MySQL. Really.

### Timetable data

By default, there is no timetable data entered into the database. There's an example timetable located in `setup/crutech_timetable.sql` that you can import to see what the data should look like. There's no built in editor for the timetable yet, so you'll have to hop into the database and edit the data yourself if you want to change things.

### Trosnoth achievements

The process for importing Trosnoth achievements is incredibly complicated. One day we'll document the process, but until then, it's recommended not to bother unless you know what you're doing.

### Questionnaire

The questionnaire is a fickle beast, more prone to breaking than not. It's also in the middle of a large refactor, so it's neither here nor there at this point. My advice: wait for the documentation.

### Documentation

In general, things aren't very well documented (have you noticed?), so if something fails to "just work", that you may be in a spot of bother. Thankfully, the simpler things such as quotes, photos and polls have been pretty well tested, so you shouldn't have too many issues.