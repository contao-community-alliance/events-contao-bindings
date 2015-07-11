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
 * This Event is emitted when the client shall get redirected to another url.
 */
class RedirectEvent extends ContaoApiEvent
{
    /**
     * The target URL.
     *
     * @var string
     */
    protected $newLocation;

    /**
     * The HTTP status code (one of 301, 302, 303, 307, defaults to 303).
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Create a new instance.
     *
     * @param string $newLocation The target URL.
     *
     * @param int    $statusCode  The HTTP status code (301, 302, 303, 307, defaults to 303).
     */
    public function __construct($newLocation, $statusCode = 303)
    {
        $this->newLocation = $newLocation;
        $this->statusCode  = $statusCode;
    }

    /**
     * Get the target URL.
     *
     * @return string
     */
    public function getNewLocation()
    {
        return $this->newLocation;
    }

    /**
     * Get the HTTP status code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
}
