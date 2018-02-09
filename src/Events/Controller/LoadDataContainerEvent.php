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
 * This Event is emitted when some data container shall get loaded.
 */
class LoadDataContainerEvent extends ContaoApiEvent
{
    /**
     * The name of the data container.
     *
     * @var string
     */
    protected $name;

    /**
     * Flag if the internal cache shall get bypassed.
     *
     * @var bool
     */
    protected $ignoreCache;

    /**
     * Create a new instance.
     *
     * @param string $name        The name of the data container to load.
     *
     * @param bool   $ignoreCache Flag if the cache shall get bypassed.
     */
    public function __construct($name, $ignoreCache = false)
    {
        $this->name        = $name;
        $this->ignoreCache = $ignoreCache;
    }

    /**
     * Check if the cache shall get bypassed.
     *
     * @return boolean
     */
    public function isCacheIgnored()
    {
        return $this->ignoreCache;
    }

    /**
     * Retrieve the name of the data container.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
