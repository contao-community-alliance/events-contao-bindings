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

namespace ContaoCommunityAlliance\Contao\Bindings\Api\Date;

use ContaoCommunityAlliance\Contao\Bindings\Api;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Date\ParseDateEvent;

/**
 * Render a timestamp into a string representation.
 *
 * @param int    $timestamp The timestamp.
 * @param string $format    The format string.
 *
 * @return string
 */
function parseDate($timestamp = null, $format = null)
{
    $event = new ParseDateEvent($timestamp, $format);

    Api\dispatch(ContaoEvents::DATE_PARSE, $event);

    return $event->getResult();
}
