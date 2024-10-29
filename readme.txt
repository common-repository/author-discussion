=== Author Discussion ===
Contributors: aschx
Tags: authors, admin, discussion, communicate
Requires at least: 3.0.1
Tested up to: 3.8
Stable tag: 0.2.2
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=UH5GRR63NWCDL&lc=US&item_name=Brandon%20White%20%2d%20WordPress%20Plugin%20Dev&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will create a page where authors, editors and administrators can communicate with their team.

== Description ==

This plugin allows authors, editors and administrators to communicate within the dashboard. Administrators will be able to grant which of those roles are granted access in the settings page.

The users will be allowed to communicate with each other using the built in WYSIWYG editor (TinyMCE). Users will be able to communicate with their team without needing any external services.

== Screenshots ==
1. The "Admin Page" or the page that is viewed on the discussion tab.
2. Global Plugin settings

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `all files` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 0.2.3 =
* Updated Icon for users using WordPress 3.8 and higher

= 0.2.2 =
* Fixed a bug with the fade notification
* Added a Quick Message option to the Widget
* Minor bug fixes
* Added a purge user view count

= 0.2.1 =
* Fixed a bug with the widget including all other widgets below it
* Fixed a bug with the toolbar link

= 0.2.0 =
* Added a widget for the dashboard (displays recent messages) for capability allowed to view
* Moved settings page for administrators to "Discussion" tab
* Added fade to notification menus
* Cleaned up some code
* Minor bug fixes

= 0.1.5 =
* Upon deleting a user, the user's messages will be deleted as well
* Added a statistic to the footer of the author discussion page

= 0.1.4 =
* Fixed an exploit that existed with message posting
* Added the ability to delete messages from front-end (if you are the author of said message)
* More modular back-end, bringing better performance/cleaner source code to follow

== Upgrade Notice ==

= 0.1.0 =
First Public Build, Allows administrators to access a settings page. Granted users can access the discussion page.