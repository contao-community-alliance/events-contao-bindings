<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings\Api
 * @subpackage Controller
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Api;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Dispatch an event.
 *
 * @param string                   $name        Event name.
 * @param Event                    $event       Event class.
 * @param EventDispatcherInterface $dispatcher  Optional pass a custom event dispatcher.
 *
 * @return void
 *
 * @SuppressWarnings(PHPMD.Superglobals)
 */
function dispatch($name, Event $event = null, EventDispatcherInterface $dispatcher = null)
{
    if (!$dispatcher) {
        $dispatcher = $GLOBALS['container']['event-dispatcher'];
    }

    $dispatcher->dispatch($name, $event);
}
