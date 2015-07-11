<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage Backend
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Backend;

use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddToUrlEvent as ControllerAddToUrlEvent;

/**
 * This Event is emitted when the client shall append some value to the current URL.
 */
class AddToUrlEvent extends ControllerAddToUrlEvent
{
}
