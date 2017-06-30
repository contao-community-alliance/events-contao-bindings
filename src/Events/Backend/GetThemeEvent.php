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
 * @subpackage Backend
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @copyright  2014 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Backend;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when the client need the current active backend theme.
 */
class GetThemeEvent extends ContaoApiEvent
{
    /**
     * The theme name.
     *
     * @var string
     */
    protected $theme;

    /**
     * Set the theme name.
     *
     * @param string $theme The theme name.
     *
     * @return GetThemeEvent
     */
    public function setTheme($theme)
    {
        $this->theme = (string) $theme;

        return $this;
    }

    /**
     * Return the theme name.
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }
}
