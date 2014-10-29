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

namespace ContaoCommunityAlliance\Contao\Bindings\Api\Backend;

use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\AddToUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\GetThemeEvent;
use Netzmacht\Contao\Events;

/**
 * Add suffix to the url.
 *
 * @param string $suffix Url suffix.
 *
 * @return string
 */
function addToUrl($suffix)
{
    $event = new AddToUrlEvent($suffix);

    Events\dispatch(ContaoEvents::BACKEND_ADD_TO_URL, $event);

    return $event->getUrl();
}

/**
 * Get current active backend theme.
 *
 * @return string
 */
function getTheme()
{
    $event = new GetThemeEvent();

    Events\dispatch(ContaoEvents::BACKEND_GET_THEME);

    return $event->getTheme();
}
