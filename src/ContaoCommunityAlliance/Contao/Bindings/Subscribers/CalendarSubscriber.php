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
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetContentElementEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the calendar extension.
 */
class CalendarSubscriber
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

	// @codingStandardsIgnoreStart - this is currently too complex but not worth the hassle of refactoring.
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

		$calendarCollection = \CalendarModel::findAll();

		if (!$calendarCollection)
		{
			return;
		}

		$calendarIds        = $calendarCollection->fetchEach('id');
		$eventModel         = \CalendarEventsModel::findPublishedByParentAndIdOrAlias(
			$event->getCalendarEventId(),
			$calendarIds
		);

		if (!$eventModel)
		{
			return;
		}

		$calendarModel = $eventModel->getRelated('pid');
		$objPage       = \PageModel::findWithDetails($calendarModel->jumpTo);

		if ($event->getDateTime()) {
			$selectedStartDateTime = clone $event->getDateTime();
			$selectedStartDateTime->setTime(
				date('H', $eventModel->startTime),
				date('i', $eventModel->startTime),
				date('s', $eventModel->startTime)
			);

			$secondsBetweenStartAndEndTime = $eventModel->endTime - $eventModel->startTime;

			$intStartTime = $selectedStartDateTime->getTimestamp();
			$intEndTime   = $intStartTime + $secondsBetweenStartAndEndTime;
		}
		else {
			$intStartTime = $eventModel->startTime;
			$intEndTime   = $eventModel->endTime;
		}

		$span = \Calendar::calculateSpan($intStartTime, $intEndTime);

		// Do not show dates in the past if the event is recurring (see #923).
		if ($eventModel->recurring)
		{
			$arrRange = deserialize($eventModel->repeatEach);

			while ($intStartTime < time() && $intEndTime < $eventModel->repeatEnd)
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
			$strTimeStart = '<time datetime="' . date('Y-m-d\TH:i:sP', $intStartTime) . '">';
			$strTimeEnd   = '<time datetime="' . date('Y-m-d\TH:i:sP', $intEndTime) . '">';
			$strTimeClose = '</time>';
		}

		// Get date.
		if ($span > 0)
		{
			$date = $strTimeStart .
				\Date::parse(($eventModel->addTime ? $objPage->datimFormat : $objPage->dateFormat), $intStartTime) .
				$strTimeClose . ' - ' . $strTimeEnd .
				\Date::parse(($eventModel->addTime ? $objPage->datimFormat : $objPage->dateFormat), $intEndTime) .
				$strTimeClose;
		}
		elseif ($intStartTime == $intEndTime)
		{
			$date = $strTimeStart .
				\Date::parse($objPage->dateFormat, $intStartTime) .
				($eventModel->addTime ? ' (' . \Date::parse($objPage->timeFormat, $intStartTime) . ')' : '') .
				$strTimeClose;
		}
		else
		{
			$date = $strTimeStart .
				\Date::parse($objPage->dateFormat, $intStartTime) .
				($eventModel->addTime ? ' (' . \Date::parse($objPage->timeFormat, $intStartTime) .
					$strTimeClose . ' - ' . $strTimeEnd .
					\Date::parse($objPage->timeFormat, $intEndTime) . ')' : ''
				) . $strTimeClose;
		}

		$until     = '';
		$recurring = '';

		// Recurring event.
		if ($eventModel->recurring)
		{
			$arrRange  = deserialize($eventModel->repeatEach);
			$strKey    = 'cal_' . $arrRange['unit'];
			$recurring = sprintf($GLOBALS['TL_LANG']['MSC'][$strKey], $arrRange['value']);

			if ($eventModel->recurrences > 0)
			{
				$until = sprintf(
					$GLOBALS['TL_LANG']['MSC']['cal_until'],
					\Date::parse($objPage->dateFormat, $eventModel->repeatEnd)
				);
			}
		}

		// Override the default image size.
		// This is always false.
		if ($this->imgSize != '')
		{
			$size = deserialize($this->imgSize);

			if ($size[0] > 0 || $size[1] > 0)
			{
				$eventModel->size = $this->imgSize;
			}
		}

		$objTemplate = new \FrontendTemplate($event->getTemplate());
		$objTemplate->setData($eventModel->row());

		$objTemplate->date          = $date;
		$objTemplate->start         = $intStartTime;
		$objTemplate->end           = $intEndTime;
		$objTemplate->class         = ($eventModel->cssClass != '') ? ' ' . $eventModel->cssClass : '';
		$objTemplate->recurring     = $recurring;
		$objTemplate->until         = $until;
		$objTemplate->locationLabel = $GLOBALS['TL_LANG']['MSC']['location'];

		$objTemplate->details = '';

		$objElement = \ContentModel::findPublishedByPidAndTable($eventModel->id, 'tl_calendar_events');

		if ($objElement !== null)
		{
			while ($objElement->next())
			{
				$getContentElementEvent = new GetContentElementEvent($objElement->id);

				$eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GET_CONTENT_ELEMENT, $getContentElementEvent);

				$objTemplate->details .= $getContentElementEvent->getContentElementHtml();
			}
		}

		$objTemplate->addImage = false;

		// Add an image.
		if ($eventModel->addImage && $eventModel->singleSRC != '')
		{
			$objModel = \FilesModel::findByUuid($eventModel->singleSRC);

			if ($objModel === null)
			{
				if (!\Validator::isUuid($eventModel->singleSRC))
				{
					$objTemplate->text = '<p class="error">'.$GLOBALS['TL_LANG']['ERR']['version2format'].'</p>';
				}
			}
			elseif (is_file(TL_ROOT . '/' . $objModel->path))
			{
				// Do not override the field now that we have a model registry (see #6303).
				$arrEvent              = $eventModel->row();
				$arrEvent['singleSRC'] = $objModel->path;

				$addImageToTemplateEvent = new AddImageToTemplateEvent($arrEvent, $objTemplate);

				$eventDispatcher->dispatch(ContaoEvents::CONTROLLER_ADD_IMAGE_TO_TEMPLATE, $addImageToTemplateEvent);
			}
		}

		$objTemplate->enclosure = array();

		// Add enclosures.
		if ($eventModel->addEnclosure)
		{
			$addEnclosureToTemplateEvent = new AddEnclosureToTemplateEvent($eventModel->row(), $objTemplate);

			$eventDispatcher->dispatch(ContaoEvents::CONTROLLER_ADD_ENCLOSURE_TO_TEMPLATE, $addEnclosureToTemplateEvent);
		}

		$calendarEvent = $objTemplate->parse();
		$event->setCalendarEventHtml($calendarEvent);
	}
	// @codingStandardsIgnoreEnd
}
