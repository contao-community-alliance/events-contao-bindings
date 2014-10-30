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

use ContaoCommunityAlliance\Contao\Bindings\Api;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\AddToUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\GetThemeEvent;

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

	Api\dispatch(ContaoEvents::BACKEND_ADD_TO_URL, $event);

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

	Api\dispatch(ContaoEvents::BACKEND_GET_THEME, $event);

	return $event->getTheme();
}
