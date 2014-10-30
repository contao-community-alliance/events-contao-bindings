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

namespace ContaoCommunityAlliance\Contao\Bindings\Api\Calendar;

use ContaoCommunityAlliance\Contao\Bindings\Api;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Calendar\GetCalendarEventEvent;

/**
 * Get a calendar event rendered as html.
 *
 * Dispatches ContaoEvents::CALENDAR_GET_EVENT event.
 *
 * @param int       $calendarEventId The calendar event id.
 * @param \DateTime $dateTime        A concrete event date time.
 * @param string    $template        The template name.
 *
 * @return string
 */
function getCalendarEvent($calendarEventId, \DateTime $dateTime = null, $template = 'event_full')
{
	$event = new GetCalendarEventEvent($calendarEventId, $dateTime, $template);

	Api\dispatch(ContaoEvents::CALENDAR_GET_EVENT, $event);

	return $event->getCalendarEventHtml();
}
