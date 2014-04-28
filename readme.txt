=== F2 Tag Cloud Widget ===
Contributors: fsquared
Tags: tags widget
Requires at least: 2.8
Tested up to: 3.9
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A tag cloud widget which exposes more of the internal Wordpress tagcloud 
options.

== Description ==

F2 Tag Cloud Widegt is an enhanced tag cloud widget that provides some 
additional options compared to the default widget provided with WordPress. 
The additional options are:

* minimum tag text size
* maximum tag text size
* maximum tag count
* tag cloud format
* tag ordering

These are all options provided by the tag cloud functions within WordPress, 
but for some reason are not exposed by their own widget. The full details 
of these options are explained in the main WordPress documentation.

In addition, there are additional options now available to control the
typography of tags with greater precision.

* tag cloud alignment - left, center, right or theme default (default)
* tag padding - defaults to 0

== Installation ==

1. Upload the `f2-tagcloud` folder to the `/wp-content/plugins` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Add the 'F2 Tag Cloud' widget through the 'Appearance / Widgets' menu

== Frequently Asked Questions ==

None

== Screenshots ==

1. Widget admin screen

== Changelog ==

= 0.1.0 =
Initial release

= 0.2.0 =
Added the ability to select different taxonomies.

= 0.3.0 =
Added new options, to set padding between individual tags, and also to set
the horizontal alignment of the tags within the cloud.

= 0.3.1 =
Minor bug fix for a mis-applied filter - many thanks to Luca for spotting it!

== Upgrade Notice ==

= 0.2.0 =
Adds the ability to render clouds for different taxonomies.

= 0.3.0 =
Adds the ability to set padding and alignment of tags.

= 0.3.1 =
Minor bug fix for a mis-applied filter.
