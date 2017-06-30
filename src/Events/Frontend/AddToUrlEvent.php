<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2016 The Contao Community Alliance
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage Frontend
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @copyright  2014 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Frontend;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when the client shall append some value to the current URL.
 */
class AddToUrlEvent extends ContaoApiEvent
{
    /**
     * The suffix to add.
     *
     * @var string
     */
    protected $suffix;

    /**
     * The resulting URL.
     *
     * @var string
     */
    protected $newUrl;

    /**
     * The resulting URL.
     *
     * @var boolean
     */
    protected $ignore = false;

    /**
     * Create a new instance.
     *
     * @param string $suffix The string to add to the URL.
     */
    public function __construct($suffix)
    {
        $this->suffix = $suffix;
    }

    /**
     * Retrieve the suffix.
     *
     * @return string
     */
    public function getSuffix()
    {
        return $this->suffix;
    }

    /**
     * Set the resulting URL.
     *
     * @param string $newUrl The new URL.
     *
     * @return AddToUrlEvent
     */
    public function setUrl($newUrl)
    {
        $this->newUrl = $newUrl;

        return $this;
    }

    /**
     * Retrieve the new URL.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->newUrl;
    }

    /**
     * Set the flag for ignoring the GET parameters.
     *
     * @param boolean $ignore True if the GET parameters shall be ignored, false otherwise.
     *
     * @return AddToUrlEvent
     */
    public function setIgnoreParams($ignore)
    {
        $this->ignore = $ignore;

        return $this;
    }

    /**
     * Retrieve the flag for ignoring the GET parameters.
     *
     * @return string
     */
    public function isIgnoreParams()
    {
        return $this->ignore;
    }
}