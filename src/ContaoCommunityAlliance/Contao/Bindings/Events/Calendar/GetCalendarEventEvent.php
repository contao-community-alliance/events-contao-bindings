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
	 * @var int
	 */
	protected $calendarEventId;

	/**
	 * @var \DateTime|null
	 */
	protected $dateTime;

	/**
	 * @var bool
	 */
	protected $teaserOnly;

	/**
	 * @var string
	 */
	protected $template = 'event_full';

	/**
	 * @var string
	 */
	protected $calendarEventHtml;

	/**
	 * @param int       $calendarEventId The calendar event ID.
	 * @param \DateTime $dateTime A concrete event date time.
	 * @param bool      $teaserOnly Generate the teaser only.
	 */
	function __construct($calendarEventId, \DateTime $dateTime = null, $teaserOnly = false, $template = 'event_full')
	{
		$this->calendarEventId = (int) $calendarEventId;
		$this->dateTime        = $dateTime;
		$this->teaserOnly      = (string) $teaserOnly;
		$this->template        = (string) $template;
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
	 * Determine if only the teaser should be generated.
	 *
	 * @return boolean
	 */
	public function getTeaserOnly()
	{
		return $this->teaserOnly;
	}

	/**
	 * @return string
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * @param string $calendarEvent
	 */
	public function setCalendarEventHtml($calendarEvent)
	{
		$this->calendarEventHtml = $calendarEvent;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCalendarEventHtml()
	{
		return $this->calendarEventHtml;
	}
}
