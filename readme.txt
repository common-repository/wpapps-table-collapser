=== WPApps Table Collapser ===
Contributors: @hefind
Tags: responsive table, table, toggle, block
Requires at least: 4.0
Tested up to: 6.6.2
Stable tag: 1.1.0
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A simple WordPress plugin that makes tables collapsible by clicking on the table header.

== Description ==

WPApps Table Collapser is a lightweight plugin that makes your tables collapsible. By adding a `collapsible-table` class to your tables, you enable users to toggle the visibility of the table body by simply clicking on the table header.

### Features

- **Collapsible Tables:** Make any table collapsible by adding a simple class.
- **Toggle Indicator:** Automatically adds a `+/-` indicator to the last column of the table header.
- **Customizable:** Style the toggle indicator and table appearance with your own CSS.
- **Block Editor Support:** Includes a Gutenberg block for easy table creation and collapsing functionality.
- **Mobile Friendly:** Works seamlessly on mobile devices with responsive design.

**Usage:**

1. Ensure that your table has a `<thead>` tag for the table header, though it's not mandatory if you enable the "Allow Table Without `<thead>`" option in settings.
2. Add the class `collapsible-table` to your table.
3. Optionally, add a `data-title` attribute to your table to set a custom title for the mobile compact view.
4. The last column in the table header will have a toggle indicator (`+/-`) added to it. Clicking anywhere in the header will show or hide the table body.

== Installation ==

1. Upload the `wpapps-table-collapser` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Add the `collapsible-table` class to any table you want to make collapsible.

== Frequently Asked Questions ==

= Does the table require any specific structure? =

For optimal functionality, the table should have a `<thead>` tag defining the header. The collapsing functionality is primarily activated by clicking on this header. However, if you enable the "Allow Table Without `<thead>`" option in the settings, the plugin will also work without a `<thead>`.

= Can I style the toggle indicator? =

Yes, you can style the toggle indicator by targeting the `.toggle-indicator` class in your CSS.

= How do I set a custom title for the mobile view? =

You can add a `data-title` attribute to your table element to set a custom title that will be displayed in the mobile compact view.

= Is the plugin compatible with the Gutenberg block editor? =

Yes, the plugin includes a Gutenberg block for easy creation and management of collapsible tables.

== Screenshots ==

1. **Example of a Collapsible Table:**
2. **Settings Page:**
3. **Detailed Guidelines on CSS Customization:**
4. **Responsive Table View:**
5. **Block Support:**

== Changelog ==

= 1.1.0 =
* Fixed a bug where the block editor css gets loaded in the front-end.

= 1.0.0 =
* Initial release.

== Upgrade Notice ==

= 1.0.0 =
* Initial release.