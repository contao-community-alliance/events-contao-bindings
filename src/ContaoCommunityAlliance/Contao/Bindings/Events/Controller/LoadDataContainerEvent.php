<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage Controller
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
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
