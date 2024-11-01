<?php
/**
 * Plugin Name: WPApps Table Collapser
 * Description: A simple WordPress plugin that makes tables collapsible by clicking on the table header. Add the class <code>"collapsible-table"</code> to your table. Optionally, add a <code>"data-title"</code> attribute to set a custom title for the mobile compact view. The plugin works with or without a <code>thead</code> tag.
 * Version: 1.1.0
 * Author: WPApps
 * Author URI: https://wpapps.net
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

 defined('ABSPATH') or die('No script kiddies please!');

 function wpapps_tc_enqueue_collapsible_scripts() {
     wp_enqueue_script('collapsible-tables', plugins_url('assets/js/collapsible.js', __FILE__), array('jquery'), '1.0', true);
     wp_enqueue_style('collapsible-tables-style', plugins_url('assets/css/style.css', __FILE__), array(), '1.0');
 
     $script_data = array(
         'enable_mobile_view' => get_option('wpapps_tc_enable_mobile_view', 0),
         'default_table_state' => get_option('wpapps_tc_default_table_state', 'collapsed'),
         'allow_no_thead' => get_option('wpapps_tc_allow_no_thead', 0)
     );
 
     wp_add_inline_script(
         'collapsible-tables',
         'var wpapps_tc_table_collapser = ' . wp_json_encode($script_data),
         'before'
     );
 }
 add_action('wp_enqueue_scripts', 'wpapps_tc_enqueue_collapsible_scripts');
 
function wpapps_tc_register_block() {
    $asset_file = include(plugin_dir_path(__FILE__) . 'build/index.asset.php');
    if (is_admin()) {
        wp_enqueue_script(
            'wpapps-tc-table-collapser-block-editor',
            plugins_url('build/index.js', __FILE__),
            $asset_file['dependencies'],
            $asset_file['version'],
            true // Explicitly set to load in the footer
        );

        wp_enqueue_style(
            'wpapps-tc-table-collapser-block-editor',
            plugins_url('build/style-index.css', __FILE__),
            array('wp-edit-blocks'),
            file_exists(plugin_dir_path(__FILE__) . 'build/style-index.css') ? filemtime(plugin_dir_path(__FILE__) . 'build/style-index.css') : '1.0'
        );
    }

    if (!is_admin()) {
        wp_enqueue_style(
            'wpapps-tc-table-collapser-frontend',
            plugins_url('assets/css/style.css', __FILE__),
            array(),
            file_exists(plugin_dir_path(__FILE__) . 'assets/css/style.css') ? filemtime(plugin_dir_path(__FILE__) . 'assets/css/style.css') : '1.0'
        );
    }

    
    register_block_type_from_metadata(__DIR__);
}
add_action('init', 'wpapps_tc_register_block');

 
 function wpapps_tc_table_collapser_menu() {
     add_menu_page(esc_html__('Table Collapser Settings', 'wpapps-table-collapser'), esc_html__('Table Collapser', 'wpapps-table-collapser'), 'manage_options', 'table-collapser-settings', 'wpapps_tc_table_collapser_settings_page', 'dashicons-editor-table', 100);
 }
 add_action('admin_menu', 'wpapps_tc_table_collapser_menu');
 
 function wpapps_tc_table_collapser_settings_page() {
     ?>
     <div class="wrap">
         <h1><?php esc_html_e('Table Collapser Settings', 'wpapps-table-collapser'); ?></h1>
         <form method="post" action="options.php">
             <?php
             settings_fields('wpapps_tc_table_collapser_options_group');
             do_settings_sections('wpapps_tc_table_collapser_options_group');
             ?>
             <table class="form-table">
                 <tr valign="top">
                     <th scope="row"><?php esc_html_e('Enable Mobile Compact View:', 'wpapps-table-collapser'); ?></th>
                     <td>
                         <input type="checkbox" name="wpapps_tc_enable_mobile_view" value="1" <?php checked(1, get_option('wpapps_tc_enable_mobile_view'), true); ?> />
                     </td>
                 </tr>
                 <tr valign="top">
                     <th scope="row"><?php esc_html_e('Default Table State:', 'wpapps-table-collapser'); ?></th>
                     <td>
                         <select name="wpapps_tc_default_table_state">
                             <option value="expanded" <?php selected('expanded', get_option('wpapps_tc_default_table_state')); ?>><?php esc_html_e('Expanded', 'wpapps-table-collapser'); ?></option>
                             <option value="collapsed" <?php selected('collapsed', get_option('wpapps_tc_default_table_state')); ?>><?php esc_html_e('Collapsed', 'wpapps-table-collapser'); ?></option>
                         </select>
                     </td>
                 </tr>
                 <tr valign="top">
                     <th scope="row"><?php esc_html_e('Allow Table Without &lt;thead&gt;:', 'wpapps-table-collapser'); ?></th>
                     <td>
                         <input type="checkbox" name="wpapps_tc_allow_no_thead" value="1" <?php checked(1, get_option('wpapps_tc_allow_no_thead'), true); ?> />
                     </td>
                 </tr>
             </table>
             <?php submit_button(); ?>
         </form>
         <h2><?php esc_html_e('How to Use', 'wpapps-table-collapser'); ?></h2>
         <p><?php esc_html_e('To make your tables collapsible, follow these steps:', 'wpapps-table-collapser'); ?></p>
         <ol>
             <li><?php esc_html_e('Add the class', 'wpapps-table-collapser'); ?> <code>collapsible-table</code> <?php esc_html_e('to your table.', 'wpapps-table-collapser'); ?></li>
             <li><?php esc_html_e('Optionally, add a', 'wpapps-table-collapser'); ?> <code>data-title</code> <?php esc_html_e('attribute to your table to set a custom title for the mobile compact view. If not provided, "Table" will be used as the default title.', 'wpapps-table-collapser'); ?></li>
             <li><?php esc_html_e('If your table has a', 'wpapps-table-collapser'); ?> <code>&lt;thead&gt;</code> <?php esc_html_e('tag, the collapsing functionality relies on the presence of this tag.', 'wpapps-table-collapser'); ?></li>
             <li><?php esc_html_e('If your table does not have a', 'wpapps-table-collapser'); ?> <code>&lt;thead&gt;</code> <?php esc_html_e('tag, ensure the "Allow Table Without', 'wpapps-table-collapser'); ?> <code>&lt;thead&gt;</code> <?php esc_html_e('" option is enabled in the settings.', 'wpapps-table-collapser'); ?></li>
             <li><?php esc_html_e('The last column in the table header will have a toggle indicator', 'wpapps-table-collapser'); ?> (<code>+/-</code>) <?php esc_html_e('added to it. Clicking anywhere in the header will show or hide the table body.', 'wpapps-table-collapser'); ?></li>
         </ol>
         <h2><?php esc_html_e('CSS Customization Guidelines', 'wpapps-table-collapser'); ?></h2>
         <p><?php esc_html_e('You can customize the appearance of your collapsible tables by adding custom CSS to your theme or using a custom CSS plugin. Here are some CSS classes you can target:', 'wpapps-table-collapser'); ?></p>
         <ul>
             <li><code>.collapsible-table</code> - <?php esc_html_e('This class is applied to the entire table. You can use it to style the overall appearance of the table.', 'wpapps-table-collapser'); ?></li>
             <li><code>.collapsible-table thead</code> - <?php esc_html_e('This targets the table header. You can use it to style the header row.', 'wpapps-table-collapser'); ?></li>
             <li><code>.collapsible-table tbody</code> - <?php esc_html_e('This targets the table body. You can use it to style the body rows.', 'wpapps-table-collapser'); ?></li>
             <li><code>.collapsible-table .toggle-indicator</code> - <?php esc_html_e('This class is applied to the toggle indicator in the table header. You can use it to style the toggle indicator.', 'wpapps-table-collapser'); ?></li>
             <li><code>.ml-table</code> - <?php esc_html_e('This class is applied to the mobile view container. You can use it to style the mobile view.', 'wpapps-table-collapser'); ?></li>
             <li><code>.ml-row</code> - <?php esc_html_e('This class is applied to each row in the mobile view. You can use it to style individual rows in the mobile view.', 'wpapps-table-collapser'); ?></li>
             <li><code>.ml-header-cell</code> - <?php esc_html_e('This class is applied to the header cells in the mobile view. You can use it to style the header cells in the mobile view.', 'wpapps-table-collapser'); ?></li>
             <li><code>.toggle-indicator-mobile</code> - <?php esc_html_e('This class is applied to the toggle indicator in the mobile view. You can use it to style the mobile toggle indicator.', 'wpapps-table-collapser'); ?></li>
         </ul>
         <p><?php esc_html_e('Example CSS:', 'wpapps-table-collapser'); ?></p>
         <pre><code>
 .collapsible-table {
     border-collapse: collapse;
     width: 100%;
 }
 
 .collapsible-table th, .collapsible-table td {
     border: 1px solid #ddd;
     padding: 8px;
 }
 
 .collapsible-table th {
     background-color: #f2f2f2;
     cursor: pointer;
 }
 
 .toggle-indicator {
     float: right;
     font-weight: bold;
 }
 
 .ml-table dt {
     font-weight: bold;
 }
 
 .ml-row:nth-child(even) {
     background-color: #f7f7f7;
 }
         </code></pre>
     </div>
     <?php
 }
 
 // Register and define the settings
 function wpapps_tc_table_collapser_settings() {
     register_setting('wpapps_tc_table_collapser_options_group', 'wpapps_tc_enable_mobile_view', 'wpapps_tc_sanitize_checkbox');
     register_setting('wpapps_tc_table_collapser_options_group', 'wpapps_tc_default_table_state', 'wpapps_tc_sanitize_select');
     register_setting('wpapps_tc_table_collapser_options_group', 'wpapps_tc_allow_no_thead', 'wpapps_tc_sanitize_checkbox');
 }
 add_action('admin_init', 'wpapps_tc_table_collapser_settings');
 
 function wpapps_tc_sanitize_checkbox($input) {
     return $input == '1' ? '1' : '0';
 }
 
 function wpapps_tc_sanitize_select($input) {
     $valid = array('expanded', 'collapsed');
     return in_array($input, $valid) ? $input : 'collapsed';
 } 