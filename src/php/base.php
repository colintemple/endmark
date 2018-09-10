<?php
/**
 * Endmark
 * by Colin Temple <https://colintemple.com/>, 2008 - 2018
 * License: GPL 3.0 <http://www.gnu.org/licenses/gpl.html>
 *
 *  Endmark is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  Endmark is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with Endmark.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

use colintemple\endmark\content\Endmark;
use colintemple\endmark\settings\Settings;
use colintemple\endmark\settings\Settings_Page;

spl_autoload_extensions('.class.php');
try {
    spl_autoload_register();
} catch (Exception $e) {
    echo 'Endmark Exception: ', $e->getMessage(), "\n";
}

$GLOBALS['endmark_settings'] = new Settings();

if (!function_exists('endmark_enqueue_admin_scripts')) {
    function endmark_enqueue_admin_scripts($hook)
    {
        Settings_Page::enqueue_scripts($hook, $GLOBALS['endmark_settings']);
    }
}

if (!function_exists('endmark_enqueue_content_scripts')) {
    function endmark_enqueue_content_scripts()
    {
        Endmark::enqueue_scripts($GLOBALS['endmark_settings']);
    }
}

if (!function_exists('endmark_settings_init')) {
    function endmark_settings_init()
    {
        $GLOBALS['endmark_settings']->load();
        $settings_page = new Settings_Page($GLOBALS['endmark_settings']);
        $settings_page->init();
    }
}

if (!function_exists('endmark_settings')) {

    function endmark_settings()
    {
        add_theme_page(
            __('Endmark Settings', 'endmark_trans_domain'),
            'Endmark',
            'edit_theme_options',
            'endmark',
            'endmark_settings_page');
    }
}

if (!function_exists('endmark_settings_page')) {

    function endmark_settings_page()
    {
        $GLOBALS['endmark_settings']->load();
        $GLOBALS['endmark_settings_page']->render();
    }
}

if (!function_exists('endmark_settings_page')) {

    function endmark_settings_page()
    {
        Settings_Page::render();
    }
}

if (!function_exists('add_endmark')) {
    function add_endmark($content)
    {
        $GLOBALS['endmark_settings']->load();
        $endmark = new Endmark($GLOBALS['endmark_settings']);
        return $endmark->render_in_content($content);
    }
}

add_action('admin_enqueue_scripts', 'endmark_enqueue_admin_scripts');
add_action('admin_init', 'endmark_settings_init');
add_action('admin_menu', 'endmark_settings');

add_action('wp_enqueue_scripts', 'endmark_enqueue_scripts');
add_filter('the_content', 'add_endmark', 500);
