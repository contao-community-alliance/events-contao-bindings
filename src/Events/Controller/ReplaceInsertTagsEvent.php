<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2018 The Contao Community Alliance
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage Controller
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Controller;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when the insert tags shall get replaced in some text.
 */
class ReplaceInsertTagsEvent extends ContaoApiEvent
{
    /**
     * The suffix to add.
     *
     * @var string
     */
    protected $buffer;

    /**
     * The resulting URL.
     *
     * @var bool
     */
    protected $allowCache;

    /**
     * Create a new instance.
     *
     * @param string $buffer     The string in which insert tags shall be replaced.
     *
     * @param bool   $allowCache True if caching is allowed, false otherwise (default: true).
     */
    public function __construct($buffer, $allowCache = true)
    {
        $this->buffer     = $buffer;
        $this->allowCache = $allowCache;
    }

    /**
     * Retrieve the suffix.
     *
     * @return string
     */
    public function getBuffer()
    {
        return $this->buffer;
    }

    /**
     * Set the resulting URL.
     *
     * @param string $buffer The new URL.
     *
     * @return ReplaceInsertTagsEvent
     */
    public function setBuffer($buffer)
    {
        $this->buffer = $buffer;

        return $this;
    }

    /**
     * Check if caching is allowed.
     *
     * @return bool
     */
    public function isCachingAllowed()
    {
        return $this->allowCache;
    }
}
