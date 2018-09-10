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

class Settings_Save_Response implements \JsonSerializable
{

    private $error = NULL;

    private $message = NULL;

    private $settings;

    function __construct($settings)
    {
        if ($settings instanceof Settings) {
            $this->settings =& $settings;
        } else {
            $this->settings = new Settings();
        }
    }

    public function getError()
    {
        return $this->error;
    }

    function setError($error)
    {
        $this->error = $error;
    }

    public function getMessage()
    {
        return $this->message;
    }

    function setMessage($message)
    {
        $this->message = $message;
    }

    public function getSettings()
    {
        return $this->settings;
    }

    function setSettings($settings)
    {
        $this->settings = $settings;
    }

    /**
     * Defines how this class should be serialized in JSON output.
     *
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'error' => $this->error,
            'message' => $this->message,
            'settings' => [
                'type' => $this->settings->getType(),
                'symbol' => $this->settings->getSymbol(),
                'image' => $this->settings->getImage(),
                'where' => $this->settings->getWhere()
            ]
        ];
    }

}