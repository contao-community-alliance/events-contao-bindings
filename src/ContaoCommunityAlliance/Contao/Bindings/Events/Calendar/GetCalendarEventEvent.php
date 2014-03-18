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

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Calendar;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when an event should be rendered.
 */
class GetCalendarEventEvent
	extends ContaoApiEvent
{
	/**
	 * The calendar event ID.
	 *
	 * @var int
	 */
	protected $calendarEventId;

	/**
	 * A concrete calendar event date time.
	 *
	 * @var \DateTime|null
	 */
	protected $dateTime;

	/**
	 * The template name.
	 *
	 * @var string
	 */
	protected $template = 'event_full';

	/**
	 * The rendered calendar event html.
	 *
	 * @var string
	 */
	protected $calendarEventHtml;

	/**
	 * Create the event.
	 *
	 * @param int       $calendarEventId The calendar event ID.
	 * @param \DateTime $dateTime        A concrete event date time.
	 * @param string    $template        The template name.
	 */
	public function __construct($calendarEventId, \DateTime $dateTime = null, $template = 'event_full')
	{
		$this->calendarEventId = (int)$calendarEventId;
		$this->dateTime        = $dateTime;
		$this->template        = (string)$template;
	}

	/**
	 * Return the calendar event ID.
	 *
	 * @return int
	 */
	public function getCalendarEventId()
	{
		return $this->calendarEventId;
	}

	/**
	 * Return concrete event date time.
	 *
	 * @return \DateTime|null
	 */
	public function getDateTime()
	{
		return $this->dateTime;
	}

	/**
	 * Retur the template name.
	 * 
	 * @return string
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * Set the rendered calendar event html.
	 *
	 * @param string $calendarEventHtml The rendered html.
	 *
	 * @return GetCalendarEventEvent
	 */
	public function setCalendarEventHtml($calendarEventHtml)
	{
		$this->calendarEventHtml = $calendarEventHtml;
		return $this;
	}

	/**
	 * Return the rendered calendar event html.
	 *
	 * @return string
	 */
	public function getCalendarEventHtml()
	{
		return $this->calendarEventHtml;
	}
}
