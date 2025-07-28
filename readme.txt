=== SmartUsername – Secure Admin Login Renamer & Username Editor Tool ===
Contributors: digitalpritam
Donate link: https://paypal.me/amhikastkar
Tags: username, change username, WordPress username
Requires at least: 5.0
Tested up to: 6.8
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily change WordPress usernames from the admin dashboard with a simple, secure interface.

== Description ==

**SmartUsername – Secure Admin Login Renamer & Username Editor Tool** is the ultimate solution for WordPress administrators who need to modify user login names without the hassle of creating new accounts or complex workarounds.

= Key Features =

* **Simple Interface**: Clean, intuitive admin interface integrated seamlessly into WordPress
* **Secure Process**: Built with WordPress security best practices and proper nonce verification
* **Administrator Control**: Only users with proper permissions can change usernames
* **Instant Updates**: Changes take effect immediately with proper cache clearing
* **User-Friendly**: No technical knowledge required - just enter the old and new username
* **WordPress Standards**: Follows all WordPress coding standards and plugin guidelines

= Perfect For =

* **Site Administrators** who need to update user login names
* **Agencies** managing multiple client websites
* **Membership Sites** requiring username modifications
* **Corporate Websites** with changing employee usernames
* **E-commerce Sites** needing to update customer login names

= How It Works =

1. Navigate to Users → Change Username in your WordPress admin
2. Enter the current username you want to change
3. Enter the new desired username
4. Click "Change Username" - it's that simple!

The plugin handles all the technical details including database updates, cache clearing, and security verification.

= Security First =

SmartUsername – Secure Admin Login Renamer & Username Editor Tool is built with security as a top priority:
* Proper nonce verification for all form submissions
* Capability checks to ensure only authorized users can make changes
* Input sanitization and validation
* Follows WordPress security best practices

= Support & Updates =

This plugin is actively maintained and regularly updated to ensure compatibility with the latest WordPress versions.

== Installation ==
1. Upload the `smartusername` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to Users -> Change Username to update the username.

== Frequently Asked Questions ==

= Who can use this plugin to change usernames? =
Only users with administrator privileges (manage_options capability) can change usernames. This ensures security and prevents unauthorized changes.

= Can I change any username to anything I want? =
Yes, as long as the new username follows WordPress username rules and isn't already taken by another user. The plugin will check for duplicates automatically.

= What happens after I change a username? =
The username is updated immediately in the database, user cache is cleared, and the change takes effect right away. The user will need to log in with their new username.

= Is this plugin safe to use? =
Absolutely! The plugin follows WordPress security best practices including proper nonce verification, input sanitization, and capability checks.

= Will changing a username affect user data? =
No, changing the username only affects the login name. All user data, posts, comments, and other content remain unchanged and properly associated with the user.

= Can I change my own username? =
Yes, administrators can change their own username or any other user's username from the admin interface.

= Does this work with multisite installations? =
The plugin works on individual sites within a multisite network, but you'll need to activate it on each site where you want to use it.

= What if I enter a username that already exists? =
The plugin will display an error message and prevent the change, ensuring no conflicts occur.

== Screenshots ==
1. The Change Username page in the dashboard.

== Changelog ==
= 1.0.0 =
* Initial release.

== Upgrade Notice ==
= 1.0.0 =
Initial release.

== License ==
This plugin is licensed under the GPLv2 or later.
