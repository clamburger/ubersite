# ÜberSite Changelog-

## ÜberSite Fenix `current development version`

### v2.1.0:
* Full tidy URL support for all pages
* Added a proper readme!
* Replaced "Trac" link in developer mode with "Github" link
* Converted changelog to markdown format and reversed the chronological order (newest changes are now at the top)
* Fixed case-sensitivity issue which caused thumbnails in the photo processor to sometimes not show up
* Introduced Twig support to replace the extremely-outdated bTemplate
* Introduced DBV for better database versioning
* #19: The new PHP password hashing API is now used throughout the code instead of the old method
* #16: Added a CLI script to reset a user's password
* Added a basic User class
* Major changes to the way alert/success messages are displayed, some some visual adjustments
* Replaced version.json with a static Software class
* Added Code Challenge functionality

## ÜberSite Executor (All 2012 camps)

### v2.0.0 (Changes made on Crütech):
* **Moved files to a public Github repository**
* Changed the internal directory structure around into more logical components
* Added tidy URLs for some parts of the website
* Reduced the number of row-spans the timetable uses
* Added questionnaire writer and changed how the questionnaire gets its questions
* Basic support for incremental database upgrades
* Added an über button
* Photo processing improvements

### v2.0.0 (Changes made up to Spring):
* Partial code restructure in an attempt to conform to some sort of consistant standard
* Added options for multiple choice polls and polls with hidden results
* Minor changes to the setup process
* Questionnaire feedback changes that made it easier to view on non-screen mediums

## ÜberSite Daggoth (Spring 2011)

### v1.3.0:
* Minor bug fixes only

## ÜberSite Confederacy (Winter and Nanobyte 2011)

### v1.2.2 (Changes made on Nanobyte):
* Added a textbox for Awards
* Removed Trosnoth Achievement and Website Suggestions for Nanobyte

### v1.2.1 (Changes made on Winter):
* Implemented very basic support for LDAP and SSH authentication
* Added support for auto log-ons
* trosnoth-check.php now uses the proper authentication wrappers
* Removed campers from Pegasaurus page
* Fixed poll moderation not showing up when there weren't any polls already
* Questionnaire feedback can now be shown within Study Groups
* Added a "Bug Report" option for Trosnoth suggestions for Josh to use
* You can now see the rarest achievement that each user has on the Trosnoth page

### v1.2.0:

#### NEW FEATURES FOR CAMPERS:
* New profile page
* New tagging system
* Improved quotes page
* Award nominations
* Pegasaurus

#### NEW FEATURES FOR LEADERS:
* New file upload system

#### OTHER CHANGES:
* #20: Users are now logged out after 15 minutes of inactivity.
* Suggestions are no longer permanently deleted.
* Changelog removed from System Information
* phpThumb is now used as an object.
* Updated filenames and database tables to be somewhat consistant.
* #43: All user input is now properly sanitized before output.
* MySpace completely removed from contacts page, replaced with Facebook.
* #10: Fixed bug that would cause AJAX box to break
* #47: bTemplate if-statement bug finally fixed.
 * Introduced !if statement
* #52: "Standalone mode" implemented which allows direct saving of pages that
  require no external resources.
* #56: Create a Page View Analysis page which shows which pages are most popular.
* Database errors now have a proper error page.
* #34: Elective questions on the Questionnaire (Stage 3) are now pulled from
  the database. A utility page was added to ensure that the tables stay in sync.
* #57: Added support for alternate authentication methods (by using wrappers for
  account-related functions).
* It is now impossible to delete your own account from the accounts page.
* Post-processing process for templates are now standardised.
* Number of MySQL queries used by pages is now significantly smaller.
* voting.php is now accessible by new users.
* Navigation menu is now generated at runtime.
 * #56: Small resolutions now use drop-down menus.
* #60: Profile pages have now been completely revamped with a better look and a
  number of new features.
* #58: Questionnaire questions are now colour-coded in order to remove any
  possible ambiguity in the responses.
* #53: Added navigation buttons when viewing individual photos.
* #18: Users can now be tagged in photos.
* #46: Campers are now able to submit polls for moderation.
* Any quotes, captions or polls that leaders submit are now automatically
  approved.
* #62: photo_view.php will now display correctly at 1024x768.
* #31: Quote page code is now significantly neater.
* #4: A dropdown list is now provided when creating a new quote to allow users
  to select the person they are quoting: this can then be linked back to their
  profile.
* #30: Leaders can now upload photos directly to the website.
* Created the Photo Processing Lab which allows admins to sort and categorise
  uploaded photos. (Currently disabled)
* #64: Profile pages have been completely redesigned
* #65: Added a constant for Nanobyte which removes some links from the navbar
  as well as changing a few other things
* #21: Layout-based CSS and colour-based CSS are now in separate files to allow
  for easier theming
* #74: Added a page for Awards for Nanobyte
* #76: Added a page for Pegasaurus for Winter

## ÜberSite Braxis (Spring 2010):

### v1.1.1:
* Resetting passwords actually works now.
* Added (very basic) support for activity groups.
* Updated Questionnaire and feedback page.
* Added Trosnoth Achievements page and related helper pages for importing data.
* Fixed thumbnail generation on Linux.
* addCampers.php now provides an input form for data.
* Fixed bug in photos.php that wouldn't show the "no photos" message when there
  weren't any photos.
* Usernames are now automatically converted to lowercase on login.
* Recent Changes link no longer shows up in wget mode.
* Removed MySpace input box from contact information. Not yet removed from
  anywhere else.
* The "What's on" box is now updated using AJAX (still slightly broken).
* Reduced min-width from 1024px to 995px in order to fit a 1024 x 768 screen.
* An incorrect login will now repopulate the username field with the last username
  you tried.
* Removed dates from announcements in wget mode.
* Fixed deleted multiple person quotes in debug mode having the wrong colour.
* Added trosnoth-check.php for Trosnoth authserver authentication.

### v1.1.0:
* Users now have to change their password the first time they log in.
* Caching mechanisms implemented so that thumbnails aren't generated on every
  load of photos.php.
* Users can now delete their votes on polls, effectively allowing them to change
  their selection.
* User categories separated into "leaders", "campers", "directors" and "cooks".
* Duplicate captions will now be automatically rejected.
* The static version of the website now has a special index announcement.
* #24: Questionnaire input is now properly sanitized.
* #13: Declining quotes and captions no longer deletes them from the database.
	* Quotes can still be deleted permanently by admins.
* Approved or declined quotes can now be reverted to being "unapproved".
* #29: All "actions" (primarily form submissions) are now recorded.
* #7: The Trosnoth Feature Request page is now the Suggestion Box and now
  contains multiple categories.
* #12: The "unapproved photos" message now stays with you on view_photo.php.
* Changed misleading message on photos.php when an empty category was selected.
* #9: Announcements now have the date/time they were created next to them.
* #25: Database errors are now stored in the database.
* #16: "Recent Changes" page now shows recent activity on the website.
* Each page access is now recorded in the database.
* #26: Campers are no longer able to resubmit the questionnaire.
* #2: Leaders can now create polls without having to edit the database.
* #28: Admins can now manage accounts without having to edit the database.
* #23: The photos page will now only generate a maximum of 5 thumbnails per
  page load. Leaders have the option to generate more.
* #27: Questionnaire code has had a major clean-up and now looks and performs
  a lot better.
* The "feedback" link on the menu bar will now only appear if there is feedback.

## ÜberSite Aldaris (Winter 2010):

### v1.0.1:
* wget can now be used to fetch a static copy of the website.

### v1.0.0:
* **Moved files into a local mercurial repository**
* Complete code cleanup.
* Created System Information page.
* Navbar improvements (including logout button).
* Photo captioning page redesigned.