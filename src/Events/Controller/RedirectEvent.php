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
