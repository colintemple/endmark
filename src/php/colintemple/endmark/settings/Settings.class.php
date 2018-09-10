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

class Settings implements \JsonSerializable
{
    const SETTING_ENABLED = true;

    const SETTING_TYPE = "endmark_type";

    const SETTING_WHERE = "endmark_where";

    const SETTING_SYMBOL = "endmark_symbol";

    const SETTING_IMAGE = "endmark_image";

    const TYPE_SYMBOL = "symbol";

    const TYPE_IMAGE = "image";

    const WHERE_BOTH = "both";

    const WHERE_POSTS = "post";

    const WHERE_PAGES = "page";

    const DEFAULT_SYMBOL = "#";

    const DEFAULT_TYPE = self::TYPE_SYMBOL;

    const DEFAULT_WHERE = self::WHERE_BOTH;

    private $loaded = false;

    private $type = self::DEFAULT_TYPE;

    private $symbol = self::DEFAULT_SYMBOL;

    private $image = NULL;

    private $where = self::DEFAULT_WHERE;

    function __construct()
    {
    }

    /**
     * Obtains the WordPress options for this plugin.
     *
     * @param bool $reload Whether or not to load the options again if they've already been loaded.
     * @return $this
     */
    function load($reload = false)
    {
        if (!$this->loaded || $reload) {

            if (false === \get_option(self::SETTING_TYPE)) {
                \add_option(self::SETTING_TYPE, self::DEFAULT_TYPE);
            } else {
                $this->type = \get_option(self::SETTING_TYPE);
            }
            if (false === \get_option(self::SETTING_SYMBOL)) {
                \add_option(self::SETTING_SYMBOL, self::DEFAULT_SYMBOL);
            } else {
                $this->symbol = \get_option(self::SETTING_SYMBOL);
            }
            if (false === \get_option(self::SETTING_IMAGE)) {
                \add_option(self::SETTING_IMAGE);
            } else {
                $this->image = \get_option(self::SETTING_IMAGE);
            }
            if (false === \get_option(self::SETTING_WHERE)) {
                \add_option(self::SETTING_WHERE, self::WHERE_BOTH);
            } else {
                $this->where = \get_option(self::SETTING_WHERE);
            }

            $this->loaded = true;
        }

        return $this;
    }

    /**
     * Defines how this class should be serialized in JSON output.
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'symbol' => $this->symbol,
            'image' => $this->image,
            'where' => $this->where
        ];
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getSymbol()
    {
        return $this->symbol;
    }

    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getWhere()
    {
        return $this->where;
    }

    public function setWhere($where)
    {
        $this->where = $where;
    }
}
