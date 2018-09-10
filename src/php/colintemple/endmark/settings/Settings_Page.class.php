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

namespace colintemple\endmark\settings;

class Settings_Page
{

    private $settings;

    function __construct(&$settings)
    {
        if ($settings instanceof Settings) {
            $this->settings =& $settings;
        } else {
            $this->settings = new Settings();
        }
    }

    /**
     * Registers the settings, along with their sections and fields, in the WordPress Settings API.
     */
    public function init()
    {

        \register_setting('endmark_group', Settings::SETTING_TYPE);
        \register_setting('endmark_group', Settings::SETTING_WHERE);
        \register_setting('endmark_group', Settings::SETTING_SYMBOL);
        \register_setting('endmark_group', Settings::SETTING_IMAGE);

        \add_settings_field(
            Settings::SETTING_TYPE,
            'Symbol',
            array($this, 'render_field_type'),
            'endmark',
            [
                'label_for' => Settings::SETTING_TYPE,
            ]
        );

        \add_settings_field(
            Settings::SETTING_WHERE,
            'Where to display Endmark:',
            array($this, 'render_field_where'),
            'endmark',
            [
                'label_for' => Settings::SETTING_WHERE,
            ]
        );

        \add_settings_section(
            'endmark_symbol_section',
            'Endmark Symbol',
            array($this, 'render_section'),
            'endmark'
        );

        \add_settings_field(
            Settings::SETTING_SYMBOL,
            'Symbol',
            array($this, 'render_field_symbol'),
            'endmark',
            'endmark_symbol_section',
            [
                'label_for' => Settings::SETTING_SYMBOL,
            ]
        );

        \add_settings_section(
            'endmark_image_section',
            'Endmark Image',
            array($this, 'render_section'),
            'endmark'
        );

        \add_settings_field(
            Settings::SETTING_IMAGE,
            'Image (URL or path):',
            array($this, 'render_field_image_path'),
            'endmark',
            'endmark_image_section',
            [
                'label_for' => Settings::SETTING_IMAGE,
            ]
        );
    }

    /**
     * Renders the opening of an admin settings section by printing out the header
     *
     * @param $args mixed Details from the section registration.
     */
    public function render_section($args)
    {

        echo '<h3 id="', \esc_attr($args['id']), '">';
        echo \esc_html__($args['title'], 'endmark_trans_domain');
        echo '</h3>' . PHP_EOL;
    }

    /**
     * Renders the Endmark Type field.
     *
     * @param $args mixed Details from the field registration.
     */
    public function render_field_type($args)
    {

        echo '<select id="', \esc_attr($args['label_for']),
        'name="', \esc_attr($args['label_for']), '">' . PHP_EOL;

        echo '<option value="', \esc_attr(Settings::TYPE_SYMBOL), '" ';
        if ($this->settings->getType() != null) {
            \selected($this->settings->getType(), Settings::TYPE_SYMBOL, false);
        }
        echo '>', \esc_html__("Symbol"), '</option>' . PHP_EOL;

        echo '<option value="', \esc_attr(Settings::TYPE_IMAGE), '" ';
        if ($this->settings->getType() != null) {
            \selected($this->settings->getType(), Settings::TYPE_IMAGE, false);
        }
        echo '>', \esc_html__("Image"), '</option>' . PHP_EOL;

        echo '</select>';

    }

    /**
     * Renders the Endmark Location field.
     *
     * @param $args mixed Details from the field registration.
     */
    public function render_field_where($args)
    {

        echo '<select id="', \esc_attr($args['label_for']),
        'name="', \esc_attr($args['label_for']), '">' . PHP_EOL;

        echo '<option value="', \esc_attr(Settings::WHERE_BOTH), '" ';
        if ($this->settings->getWhere() != null) {
            \selected($this->settings->getWhere(), Settings::WHERE_BOTH, false);
        }
        echo '>', \esc_html__("Posts and Pages"), '</option>' . PHP_EOL;

        echo '<option value="', \esc_attr(Settings::WHERE_POSTS), '" ';
        if ($this->settings->getType() != null) {
            \selected($this->settings->getType(), Settings::WHERE_POSTS, false);
        }
        echo '>', \esc_html__("Posts Only"), '</option>' . PHP_EOL;

        echo '<option value="', \esc_attr(Settings::WHERE_PAGES), '" ';
        if ($this->settings->getType() != null) {
            \selected($this->settings->getType(), Settings::WHERE_PAGES, false);
        }
        echo '>', \esc_html__("Pages Only"), '</option>' . PHP_EOL;

        echo '</select>';

    }

    /**
     * Renders the Symbol field.
     *
     * @param $args mixed Details from the field registration.
     */
    public function render_field_symbol($args)
    {
        echo '<input type="text" name="', $args['label_for'], '" value="',
        $this->settings->getSymbol(), '"
            size="5" maxlength="3">';
    }

    /**
     * Renders the Image Path field.
     *
     * @param $args mixed Details from the field registration.
     */
    public function render_field_image_path($args)
    {
        echo '<input type="text" name="', $args['label_for'], '" value="',
        $this->settings->getImage(), '">';
    }

    /**
     * Renders the settings page in the admin.
     */
    public static function render()
    {

        if (!\current_user_can('edit_theme_options')) {
            return;
        }

        echo '<div class="wrap">' . PHP_EOL;

        // header
        echo '<h2>' . __('Endmark Settings', 'endmark_trans_domain') . '</h2>' . PHP_EOL . PHP_EOL;

        echo '<form action="#" id="endmark_settings_form" method="post">' . PHP_EOL;

        \settings_fields('endmark_group');

        \do_settings_sections('endmark');

        \submit_button(__('Save Endmark', 'endmark_trans_domain'));

        echo '</div>' . PHP_EOL . PHP_EOL;

    }

    /**
     * Saves the settings provided from a HTTP post, and echoes them back as JSON.
     */
    public static function save()
    {

        if (!\current_user_can('edit_theme_options')) {
            return;
        }

        $settings = new Settings();
        $settings->setType(strval($_POST[Settings::SETTING_TYPE]));
        $settings->setWhere(strval($_POST[Settings::SETTING_WHERE]));
        $settings->setSymbol(strval($_POST[Settings::SETTING_SYMBOL]));
        $settings->setImage(strval($_POST[Settings::SETTING_IMAGE]));

        \update_option(Settings::SETTING_TYPE, $settings->getType());
        \update_option(Settings::SETTING_WHERE, $settings->getWhere());
        \update_option(Settings::SETTING_SYMBOL, $settings->getSymbol());
        \update_option(Settings::SETTING_IMAGE, $settings->getImage());

        $response = new Settings_Save_Response($settings);
        $response->setMessage(\__("The settings have been saved.", "endmark_trans_domain"));

        \header('Content-Type: application/json');

        echo json_encode($response);

        \wp_die();
    }

    /**
     * Adds the scripts required to render the settings page.
     *
     * @param $hook string The hook for the current admin page.
     * @param $settings Settings The Endmark settings.
     */
    public static function enqueue_scripts($hook, $settings)
    {

        if ('themes.php' != $hook
            || !\current_user_can('edit_theme_options')
            || !$settings instanceof Settings) {

            return;
        }

        \wp_enqueue_script('endmark_admin_script', \plugins_url('/js/save_settings.js', __FILE__),
            array('jquery'));

        \wp_localize_script('endmark_admin_script', 'endmarkAdminSettings',
            [
                'url' => \admin_url('admin-ajax.php'),
                'type' => $settings->getType(),
                'symbol' => $settings->getSymbol(),
                'image' => $settings->getImage(),
                'where' => $settings->getWhere()
            ]);
    }
}
