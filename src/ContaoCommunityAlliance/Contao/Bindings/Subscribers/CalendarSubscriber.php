<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 * @package    ContaoCommunityAlliance\Contao\Bindings
 * @subpackage Subscribers
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Subscribers;

use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Calendar\GetCalendarEventEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddEnclosureToTemplateEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddImageToTemplateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the calendar extension.
 */
class CalendarSubscriber
	extends \Events
	implements EventSubscriberInterface
{
	/**
	 * Returns an array of event names this subscriber wants to listen to.
	 *
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return array(
			ContaoEvents::CALENDAR_GET_EVENT   => 'handleEvent',
		);
	}

	/**
	 * Constructor - this one does NOT call parent constructor to have overhead minimal.
	 */
	public function __construct()
	{
		// Do not call parent constructor.
		$this->import('Config');
		$this->import('Input');
		$this->import('Environment');
		$this->import('Session');
		$this->import('Database');
	}

	/**
	 * Empty override to make class non abstract.
	 *
	 * @return string
	 */
	public function compile()
	{
		return '';
	}

	/**
	 * Render a calendar event.
	 *
	 * @param GetCalendarEventEvent $event The event.
	 *
	 * @return void
	 */
	public function handleEvent(GetCalendarEventEvent $event)
	{
		if ($event->getCalendarEventHtml())
		{
			return;
		}

		$eventDispatcher = $event->getDispatcher();

		$time = time();

		// Get the current event.
		$objEvent = $this->Database
			->prepare('SELECT
				*,
				author AS authorId,
				(SELECT title FROM tl_calendar WHERE tl_calendar.id=tl_calendar_events.pid) AS calendar,
				(SELECT jumpTo FROM tl_calendar WHERE tl_calendar.id=tl_calendar_events.pid) AS jumpTo,
				(SELECT name FROM tl_user WHERE id=author) author
				FROM tl_calendar_events
				WHERE
					pid IN(' . implode(',', array_map('intval', $this->cal_calendar)) . ')
					AND (id=? OR alias=?)' .
					(!BE_USER_LOGGED_IN
						? 'AND (start=\'\' OR start<?) AND (stop=\'\' OR stop>?) AND published=1'
						: ''
					)
			)
			->limit(1)
			->execute($event->getCalendarEventId(), $event->getCalendarEventId(), $time, $time);

		if ($objEvent->numRows < 1)
		{
			return;
		}

		$objPage = $this->getPageDetails($objEvent->jumpTo);

		$intStartTime = $objEvent->startTime;
		$intEndTime   = $objEvent->endTime;
		$span         = \Calendar::calculateSpan($intStartTime, $intEndTime);

		// Do not show dates in the past if the event is recurring (see #923).
		if ($objEvent->recurring)
		{
			$arrRange = deserialize($objEvent->repeatEach);

			while ($intStartTime < time() && $intEndTime < $objEvent->repeatEnd)
			{
				$intStartTime = strtotime('+' . $arrRange['value'] . ' ' . $arrRange['unit'], $intStartTime);
				$intEndTime   = strtotime('+' . $arrRange['value'] . ' ' . $arrRange['unit'], $intEndTime);
			}
		}

		if ($objPage->outputFormat == 'xhtml')
		{
			$strTimeStart = '';
			$strTimeEnd   = '';
			$strTimeClose = '';
		}
		else
		{
			$strTimeStart = '<time datetime="' . date('Y-m-d\TH:i:sP', $objEvent->startTime) . '">';
			$strTimeEnd   = '<time datetime="' . date('Y-m-d\TH:i:sP', $objEvent->endTime) . '">';
			$strTimeClose = '</time>';
		}

		// Get date.
		if ($span > 0)
		{
			$date = $strTimeStart .
				$this->parseDate(($objEvent->addTime ? $objPage->datimFormat : $objPage->dateFormat), $intStartTime) .
				$strTimeClose . ' - ' . $strTimeEnd .
				$this->parseDate(($objEvent->addTime ? $objPage->datimFormat : $objPage->dateFormat), $intEndTime) .
				$strTimeClose;
		}
		elseif ($objEvent->startTime == $objEvent->endTime)
		{
			$date = $strTimeStart .
				$this->parseDate($objPage->dateFormat, $intStartTime) .
				($objEvent->addTime ? ' (' . $this->parseDate($objPage->timeFormat, $intStartTime) . ')' : '') .
				$strTimeClose;
		}
		else
		{
			$date = $strTimeStart .
				$this->parseDate($objPage->dateFormat, $intStartTime) .
				($objEvent->addTime ? ' (' . $this->parseDate($objPage->timeFormat, $intStartTime) .
					$strTimeClose . ' - ' . $strTimeEnd .
					$this->parseDate($objPage->timeFormat, $intEndTime) . ')' : ''
				) . $strTimeClose;
		}

		$until     = '';
		$recurring = '';

		// Recurring event.
		if ($objEvent->recurring)
		{
			$arrRange  = deserialize($objEvent->repeatEach);
			$strKey    = 'cal_' . $arrRange['unit'];
			$recurring = sprintf($GLOBALS['TL_LANG']['MSC'][$strKey], $arrRange['value']);

			if ($objEvent->recurrences > 0)
			{
				$until = sprintf(
					$GLOBALS['TL_LANG']['MSC']['cal_until'],
					$this->parseDate($objPage->dateFormat, $objEvent->repeatEnd)
				);
			}
		}

		// Override the default image size.
		// FIXME: This is always false.
		if ($this->imgSize != '')
		{
			$size = deserialize($this->imgSize);

			if ($size[0] > 0 || $size[1] > 0)
			{
				$objEvent->size = $this->imgSize;
			}
		}

		$objTemplate = new \FrontendTemplate($this->cal_template);
		$objTemplate->setData($objEvent->row());

		$objTemplate->date      = $date;
		$objTemplate->start     = $objEvent->startTime;
		$objTemplate->end       = $objEvent->endTime;
		$objTemplate->class     = ($objEvent->cssClass != '') ? ' ' . $objEvent->cssClass : '';
		$objTemplate->recurring = $recurring;
		$objTemplate->until     = $until;

		$this->import('String');

		// Clean the RTE output.
		if ($objPage->outputFormat == 'xhtml')
		{
			$objEvent->details = $this->String->toXhtml($objEvent->details);
		}
		else
		{
			$objEvent->details = $this->String->toHtml5($objEvent->details);
		}

		$objTemplate->details  = $this->String->encodeEmail($objEvent->details);
		$objTemplate->addImage = false;

		// Add image.
		if ($objEvent->addImage && is_file(TL_ROOT . '/' . $objEvent->singleSRC))
		{
			// Do not override the field now that we have a model registry (see #6303).
			$arrEvent              = $objEvent->row();
			$arrEvent['singleSRC'] = $objEvent->singleSRC;

			$addImageToTemplateEvent = new AddImageToTemplateEvent($arrEvent, $objTemplate);

			$eventDispatcher->dispatch(ContaoEvents::CONTROLLER_ADD_IMAGE_TO_TEMPLATE, $addImageToTemplateEvent);
		}

		$objTemplate->enclosure = array();

		// Add enclosures.
		if ($objEvent->addEnclosure)
		{
			$addEnclosureToTemplateEvent = new AddEnclosureToTemplateEvent($objEvent->row(), $objTemplate);

			$eventDispatcher->dispatch(ContaoEvents::CONTROLLER_ADD_ENCLOSURE_TO_TEMPLATE, $addEnclosureToTemplateEvent);
		}

		$calendarEvent = $objTemplate->parse();
		$event->setCalendarEventHtml($calendarEvent);
	}
}
