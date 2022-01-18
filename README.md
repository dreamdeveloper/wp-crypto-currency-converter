\=== WP Crypto Currency Converter === Contributors: Alexey Kiriushyn
Tags: api, coinmarketcap, remote, currencies, plugin, wordpress,
conversion Donate link: \# Requires at least: 5.2 Tested up to: 5.8.3
Requires PHP: 7.4 Stable tag: 1.0.0 License: GPL v3 or later License
URI: https://www.gnu.org/licenses/gpl-3.0.txt

Working with the API of a third-party resource - coinmarketcap,
displaying data using a shortcode.

\== Description == Working with the API of a third-party resource -
coinmarketcap, displaying data using a shortcode. You can display the
last conversion data on the page with shortocde "\[ccc-list-data\]", and
you can use the Shortcode for display the Conversion Widget
"\[ccc-conversion\]" or in the php file of your theme you can use it
like this: "echo do\_shortocde("\[ccc-conversion\]");" and "echo
do\_shortocde("\[ccc-list-data\]");". The data for converions is
refreshed every 5 minutes, in order to refresh the data forcibly, click
the Refresh button in the upper right corner.

Also, to force a data refresh, you can do it through the command line
using the command: wp ccc-refresh-data

\== Installation == 1. Upload "wp-crypto-currency-converter" to the
"/wp-content/plugins/" directory. 1. Activate the plugin through the
"Plugins" menu in WordPress. 1. Place
"do\_shortocde("\[ccc-conversion\]");" or
"do\_shortocde("\[ccc-list-data\]");" in your templates.

\== Changelog == = 1.0.0 = \* Initial release.
