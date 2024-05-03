<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2024 The Contao Community Alliance
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
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2014-2024 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Contao\Bindings\Subscribers;

use Contao\Calendar;
use Contao\CalendarEventsModel;
use Contao\CalendarModel;
use Contao\ContentModel;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Date;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\PageModel;
use Contao\StringUtil;
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
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CalendarSubscriber implements EventSubscriberInterface
{
    /**
     * The contao framework.
     *
     * @var ContaoFramework
     */
    protected ContaoFramework $framework;

    /**
     * CalendarSubscriber constructor.
     *
     * @param ContaoFramework $framework The contao framework.
     */
    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContaoEvents::CALENDAR_GET_EVENT => 'handleEvent',
        ];
    }

    /**
     * Render a calendar event.
     *
     * @param GetCalendarEventEvent    $event           The event.
     * @param string                   $eventName       The event name.
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
     *
     * @psalm-suppress MixedArrayAccess - The global access can not be typed.
     * @psalm-suppress UndefinedMagicPropertyAssignment
     * @psalm-suppress UndefinedMagicPropertyFetch
     * @psalm-suppress UndefinedConstant
     */
    public function handleEvent(
        GetCalendarEventEvent $event,
        string $eventName,
        EventDispatcherInterface $eventDispatcher
    ): void {
        if (null === $event->getCalendarEventHtml()) {
            return;
        }

        /**
         * @var CalendarModel $modelAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $modelAdapter = $this->framework->getAdapter(CalendarModel::class);
        $calendarCollection   = $modelAdapter->findAll();

        if (!$calendarCollection) {
            return;
        }

        /**
         * @var CalendarEventsModel $eventsModelAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $eventsModelAdapter = $this->framework->getAdapter(CalendarEventsModel::class);

        $calendarIds = $calendarCollection->fetchEach('id');
        $eventModel  = $eventsModelAdapter->findPublishedByParentAndIdOrAlias(
            $event->getCalendarEventId(),
            $calendarIds
        );

        if (!$eventModel) {
            return;
        }

        /**
         * @var PageModel $pageModelAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $pageModelAdapter = $this->framework->getAdapter(PageModel::class);

        /**
         * @var CalendarModel|null $calendarModel
         * @psalm-suppress DocblockTypeContradiction - the parent is not denoted in Contao code.
         */
        $calendarModel = $eventModel->getRelated('pid');
        assert($calendarModel instanceof CalendarModel);
        $objPage = $pageModelAdapter->findWithDetails((int) $calendarModel->jumpTo);
        assert($objPage instanceof PageModel);

        $intStartTime = (int) $eventModel->startTime;
        $intEndTime   = (int) $eventModel->endTime;
        if ($date = $event->getDateTime()) {
            $startDateTime = clone $date;
            $startDateTime->setTime(
                (int) date('H', $intStartTime),
                (int) date('i', $intStartTime),
                (int) date('s', $intStartTime)
            );

            $durationInSeconds = ($intEndTime - $intStartTime);

            $intStartTime = $startDateTime->getTimestamp();
            $intEndTime   = ($intStartTime + $durationInSeconds);
        }

        /**
         * @var Calendar $calendarAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $calendarAdapter = $this->framework->getAdapter(Calendar::class);

        $span = $calendarAdapter->calculateSpan($intStartTime, $intEndTime);

        // Do not show dates in the past if the event is recurring (see #923).
        if ((bool) $eventModel->recurring) {
            /**
             * @var StringUtil $stringUtilAdapter
             * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
             */
            $stringUtilAdapter = $this->framework->getAdapter(StringUtil::class);
            /** @var array{value: string, unit: string} $arrRange */
            $arrRange = $stringUtilAdapter->deserialize($eventModel->repeatEach);

            while ($intStartTime < time() && $intEndTime < $eventModel->repeatEnd) {
                $intStartTime = strtotime('+' . $arrRange['value'] . ' ' . $arrRange['unit'], $intStartTime);
                $intEndTime   = strtotime('+' . $arrRange['value'] . ' ' . $arrRange['unit'], $intEndTime);
            }
        }

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

        /**
         * @var Date $dateAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $dateAdapter = $this->framework->getAdapter(Date::class);

        // Get date.
        $addTime = (bool) $eventModel->addTime;
        if ($span > 0) {
            $date = $strTimeStart .
                $dateAdapter->parse(
                    ($addTime ? $objPage->datimFormat : $objPage->dateFormat),
                    $intStartTime
                ) .
                $strTimeClose . ' - ' . $strTimeEnd .
                    $dateAdapter->parse(
                        ($addTime ? $objPage->datimFormat : $objPage->dateFormat),
                        $intEndTime
                    ) .
                $strTimeClose;
        } elseif ($intStartTime == $intEndTime) {
            $date = $strTimeStart .
                    $dateAdapter->parse($objPage->dateFormat, $intStartTime) .
                ($addTime ? ' (' . $dateAdapter->parse($objPage->timeFormat, $intStartTime) . ')' : '') .
                $strTimeClose;
        } else {
            $date = $strTimeStart .
                $dateAdapter->parse($objPage->dateFormat, $intStartTime) .
                (
                $addTime ? ' (' . $dateAdapter->parse($objPage->timeFormat, $intStartTime) .
                    $strTimeClose . ' - ' . $strTimeEnd .
                    $dateAdapter->parse($objPage->timeFormat, $intEndTime) . ')' : ''
                ) .
                $strTimeClose;
        }

        $until     = '';
        $recurring = '';

        // Recurring event.
        if ((bool) $eventModel->recurring) {
            /**
             * @var StringUtil $stringUtilAdapter
             * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
             */
            $stringUtilAdapter = $this->framework->getAdapter(StringUtil::class);
            /** @var array{unit: string, value: string} */
            $arrRange  = $stringUtilAdapter->deserialize($eventModel->repeatEach);
            $strKey    = 'cal_' . $arrRange['unit'];
            $recurring = sprintf((string) $GLOBALS['TL_LANG']['MSC'][$strKey], $arrRange['value']);

            if ($eventModel->recurrences > 0) {
                $until = sprintf(
                    (string) $GLOBALS['TL_LANG']['MSC']['cal_until'],
                    $dateAdapter->parse($objPage->dateFormat, (int) $eventModel->repeatEnd)
                );
            }
        }

        // Override the default image size.
        // FIXME: This is always false!
        if (!empty($imgSize = (string) $this->imgSize)) {
            /**
             * @var StringUtil $stringUtilAdapter
             * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
             */
            $stringUtilAdapter = $this->framework->getAdapter(StringUtil::class);
            /** @var list<string> $size */
            $size = $stringUtilAdapter->deserialize($imgSize);

            if ($size[0] > 0 || $size[1] > 0) {
                $eventModel->size = $imgSize;
            }
        }

        /** @psalm-suppress InternalMethod */
        $objTemplate = $this->framework->createInstance(FrontendTemplate::class, [$event->getTemplate()]);
        $objTemplate->setData($eventModel->row());

        $objTemplate->date          = $date;
        $objTemplate->start         = $intStartTime;
        $objTemplate->end           = $intEndTime;
        $objTemplate->class         = (!empty($eventModel->cssClass)) ? ' ' . $eventModel->cssClass : '';
        $objTemplate->recurring     = $recurring;
        $objTemplate->until         = $until;
        $objTemplate->locationLabel = $GLOBALS['TL_LANG']['MSC']['location'];

        $objTemplate->details = '';

        /**
         * @var ContentModel $contentModelAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $contentModelAdapter = $this->framework->getAdapter(ContentModel::class);

        /** @var \Contao\Model\Collection|null $objElement */
        $objElement = $contentModelAdapter->findPublishedByPidAndTable((int) $eventModel->id, 'tl_calendar_events');

        if ($objElement !== null) {
            while ($objElement->next()) {
                $contentElementEvent = new GetContentElementEvent((int) $objElement->id);

                $eventDispatcher->dispatch($contentElementEvent, ContaoEvents::CONTROLLER_GET_CONTENT_ELEMENT);

                /** @psalm-suppress MixedOperand */
                $objTemplate->details .= (string) $contentElementEvent->getContentElementHtml();
            }

            $objTemplate->hasDetails = true;
        }

        $objTemplate->addImage = false;

        /**
         * @var FilesModel $filesModelAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $filesModelAdapter = $this->framework->getAdapter(FilesModel::class);

        // Add an image.
        if ((bool) $eventModel->addImage && null !== $eventModel->singleSRC) {
            $objModel = $filesModelAdapter->findByUuid($eventModel->singleSRC);

            if ($objModel === null) {
                /**
                 * @var Validator $validatorAdapter
                 * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
                 */
                $validatorAdapter = $this->framework->getAdapter(Validator::class);

                if (!$validatorAdapter->isUuid($eventModel->singleSRC)) {
                    $objTemplate->text = sprintf(
                        '<p class="error">%1$s</p>',
                        (string) $GLOBALS['TL_LANG']['ERR']['version2format']
                    );
                }
            } elseif (is_file(TL_ROOT . '/' . $objModel->path)) {
                // Do not override the field now that we have a model registry (see #6303).
                $arrEvent              = $eventModel->row();
                $arrEvent['singleSRC'] = $objModel->path;

                $addImageEvent = new AddImageToTemplateEvent($arrEvent, $objTemplate);

                $eventDispatcher->dispatch($addImageEvent, ContaoEvents::CONTROLLER_ADD_IMAGE_TO_TEMPLATE);
            }
        }

        $objTemplate->enclosure = [];

        // Add enclosures.
        if ((bool) $eventModel->addEnclosure) {
            $enclosureEvent = new AddEnclosureToTemplateEvent($eventModel->row(), $objTemplate);

            $eventDispatcher->dispatch($enclosureEvent, ContaoEvents::CONTROLLER_ADD_ENCLOSURE_TO_TEMPLATE);
        }

        $calendarEvent = $objTemplate->parse();
        $event->setCalendarEventHtml($calendarEvent);
    }
}
