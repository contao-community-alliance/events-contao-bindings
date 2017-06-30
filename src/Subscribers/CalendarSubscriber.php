<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2017 The Contao Community Alliance
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings
 * @subpackage Subscribers
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2017 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Subscribers;

use Contao\Calendar;
use Contao\CalendarEventsModel;
use Contao\CalendarModel;
use Contao\ContentModel;
use Contao\Date;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\PageModel;
use Contao\Validator;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Calendar\GetCalendarEventEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddEnclosureToTemplateEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddImageToTemplateEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetContentElementEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the calendar extension.
 */
class CalendarSubscriber implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            ContaoEvents::CALENDAR_GET_EVENT => 'handleEvent',
        );
    }

    /**
     * Render a calendar event.
     *
     * @param GetCalendarEventEvent    $event           The event.
     *
     * @param string                   $eventName       The event name.
     *
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function handleEvent(GetCalendarEventEvent $event, $eventName, EventDispatcherInterface $eventDispatcher)
    {
        if ($event->getCalendarEventHtml()) {
            return;
        }

        $calendarCollection = CalendarModel::findAll();

        if (!$calendarCollection) {
            return;
        }

        $calendarIds = $calendarCollection->fetchEach('id');
        $eventModel  = CalendarEventsModel::findPublishedByParentAndIdOrAlias(
            $event->getCalendarEventId(),
            $calendarIds
        );

        if (!$eventModel) {
            return;
        }

        $calendarModel = $eventModel->getRelated('pid');
        $objPage       = PageModel::findWithDetails($calendarModel->jumpTo);

        if ($event->getDateTime()) {
            $selectedStartDateTime = clone $event->getDateTime();
            $selectedStartDateTime->setTime(
                date('H', $eventModel->startTime),
                date('i', $eventModel->startTime),
                date('s', $eventModel->startTime)
            );

            $secondsBetweenStartAndEndTime = ($eventModel->endTime - $eventModel->startTime);

            $intStartTime = $selectedStartDateTime->getTimestamp();
            $intEndTime   = ($intStartTime + $secondsBetweenStartAndEndTime);
        } else {
            $intStartTime = $eventModel->startTime;
            $intEndTime   = $eventModel->endTime;
        }

        $span = Calendar::calculateSpan($intStartTime, $intEndTime);

        // Do not show dates in the past if the event is recurring (see #923).
        if ($eventModel->recurring) {
            $arrRange = deserialize($eventModel->repeatEach);

            while ($intStartTime < time() && $intEndTime < $eventModel->repeatEnd) {
                $intStartTime = strtotime('+' . $arrRange['value'] . ' ' . $arrRange['unit'], $intStartTime);
                $intEndTime   = strtotime('+' . $arrRange['value'] . ' ' . $arrRange['unit'], $intEndTime);
            }
        }

        if ($objPage->outputFormat === 'xhtml') {
            $strTimeStart = '';
            $strTimeEnd   = '';
            $strTimeClose = '';
        } else {
            $strTimeStart = '';
            $strTimeEnd   = '';
            $strTimeClose = '';

            // @codingStandardsIgnoreStart
            /*
            TODO $this->date and $this->time is used in the <a> title attribute and cannot contain HTML!
            $strTimeStart = '<time datetime="' . date('Y-m-d\TH:i:sP', $intStartTime) . '">';
            $strTimeEnd   = '<time datetime="' . date('Y-m-d\TH:i:sP', $intEndTime) . '">';
            $strTimeClose = '</time>';
            */
            // @codingStandardsIgnoreEnd
        }

        // Get date.
        if ($span > 0) {
            $date = $strTimeStart .
                Date::parse(($eventModel->addTime ? $objPage->datimFormat : $objPage->dateFormat), $intStartTime) .
                $strTimeClose . ' - ' . $strTimeEnd .
                Date::parse(($eventModel->addTime ? $objPage->datimFormat : $objPage->dateFormat), $intEndTime) .
                $strTimeClose;
        } elseif ($intStartTime == $intEndTime) {
            $date = $strTimeStart .
                Date::parse($objPage->dateFormat, $intStartTime) .
                ($eventModel->addTime ? ' (' . Date::parse($objPage->timeFormat, $intStartTime) . ')' : '') .
                $strTimeClose;
        } else {
            $date = $strTimeStart .
                Date::parse($objPage->dateFormat, $intStartTime) .
                ($eventModel->addTime ? ' (' . Date::parse($objPage->timeFormat, $intStartTime) .
                    $strTimeClose . ' - ' . $strTimeEnd .
                    Date::parse($objPage->timeFormat, $intEndTime) . ')' : ''
                ) . $strTimeClose;
        }

        $until     = '';
        $recurring = '';

        // Recurring event.
        if ($eventModel->recurring) {
            $arrRange  = deserialize($eventModel->repeatEach);
            $strKey    = 'cal_' . $arrRange['unit'];
            $recurring = sprintf($GLOBALS['TL_LANG']['MSC'][$strKey], $arrRange['value']);

            if ($eventModel->recurrences > 0) {
                $until = sprintf(
                    $GLOBALS['TL_LANG']['MSC']['cal_until'],
                    Date::parse($objPage->dateFormat, $eventModel->repeatEnd)
                );
            }
        }

        // Override the default image size.
        // This is always false.
        if (!empty($this->imgSize)) {
            $size = deserialize($this->imgSize);

            if ($size[0] > 0 || $size[1] > 0) {
                $eventModel->size = $this->imgSize;
            }
        }

        $objTemplate = new FrontendTemplate($event->getTemplate());
        $objTemplate->setData($eventModel->row());

        $objTemplate->date          = $date;
        $objTemplate->start         = $intStartTime;
        $objTemplate->end           = $intEndTime;
        $objTemplate->class         = (!empty($eventModel->cssClass)) ? ' ' . $eventModel->cssClass : '';
        $objTemplate->recurring     = $recurring;
        $objTemplate->until         = $until;
        $objTemplate->locationLabel = $GLOBALS['TL_LANG']['MSC']['location'];

        $objTemplate->details = '';

        $objElement = ContentModel::findPublishedByPidAndTable($eventModel->id, 'tl_calendar_events');

        if ($objElement !== null) {
            while ($objElement->next()) {
                $getContentElementEvent = new GetContentElementEvent($objElement->id);

                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GET_CONTENT_ELEMENT, $getContentElementEvent);

                $objTemplate->details .= $getContentElementEvent->getContentElementHtml();
            }

            $objTemplate->hasDetails = true;
        }

        $objTemplate->addImage = false;

        // Add an image.
        if ($eventModel->addImage && !empty($eventModel->singleSRC)) {
            $objModel = FilesModel::findByUuid($eventModel->singleSRC);

            if ($objModel === null) {
                if (!Validator::isUuid($eventModel->singleSRC)) {
                    $objTemplate->text = '<p class="error">' . $GLOBALS['TL_LANG']['ERR']['version2format'] . '</p>';
                }
            } elseif (is_file(TL_ROOT . '/' . $objModel->path)) {
                // Do not override the field now that we have a model registry (see #6303).
                $arrEvent              = $eventModel->row();
                $arrEvent['singleSRC'] = $objModel->path;

                $addImageToTemplateEvent = new AddImageToTemplateEvent($arrEvent, $objTemplate);

                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_ADD_IMAGE_TO_TEMPLATE, $addImageToTemplateEvent);
            }
        }

        $objTemplate->enclosure = array();

        // Add enclosures.
        if ($eventModel->addEnclosure) {
            $addEnclosureToTemplateEvent = new AddEnclosureToTemplateEvent($eventModel->row(), $objTemplate);

            $eventDispatcher->dispatch(
                ContaoEvents::CONTROLLER_ADD_ENCLOSURE_TO_TEMPLATE,
                $addEnclosureToTemplateEvent
            );
        }

        $calendarEvent = $objTemplate->parse();
        $event->setCalendarEventHtml($calendarEvent);
    }
}
