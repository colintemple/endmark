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

namespace colintemple\endmark\content;

use colintemple\endmark\settings\Settings;

class Endmark
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
     * Takes the content of a page or post and adds the endmark to the end of it, based on the
     * current endmark settings.
     *
     * @param $content string The page or post content to add the endmark to.
     * @return mixed The amended content.
     */
    public function render_in_content($content)
    {

        if ($this->is_displayed()) {

            $pos = \strripos($content, '</p>');
            $content = \substr_replace($content, $this->endmark_content(), $pos, 0);
        }

        return $content;
    }

    /**
     * Adds the scripts and styles required to render an Endmark to the page
     *
     * @param $settings Settings The Endmark settings
     */
    public static function enqueue_scripts($settings)
    {
        if (!$settings instanceof Settings) {
            return;
        }
    }

    /**
     * Determines whether or not the Endmark should be appended to content in the current context.
     *
     * @return bool True if the Endmark is to be displayed, false otherwise.
     */
    private function is_displayed()
    {

        if (!\is_404() && !\is_attachment()) {

            switch ($this->settings->getWhere()) {

                case Settings::WHERE_BOTH:

                    return true;

                case Settings::WHERE_POSTS:

                    if (\is_single() || \is_home() || \is_category() || \is_tag() || \is_archive()
                        || \is_search()) {

                        return true;
                    }
                    break;

                case Settings::WHERE_PAGES:

                    if (\is_page()) {

                        return true;
                    }
                    break;

                default:
                    return false;
            }
        }
        return false;
    }

    /**
     * Generates the HTML content to display the Endmark.
     *
     * @return string The Endmark HTML
     */
    private function endmark_content()
    {

        switch ($this->settings->getType()) {

            case Settings::TYPE_IMAGE:

                return '<img src="' . $this->settings->getImage() . '" class="endmark" alt="" />';

            case Settings::TYPE_SYMBOL:

                return '&nbsp;' . \htmlentities($this->settings->getSymbol());

            default:
                return '';
        }
    }
}
