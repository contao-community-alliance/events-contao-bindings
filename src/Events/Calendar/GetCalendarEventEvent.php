<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2018 The Contao Community Alliance
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage Calendar
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Calendar;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when an event should be rendered.
 */
class GetCalendarEventEvent extends ContaoApiEvent
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
        $this->calendarEventId = (int) $calendarEventId;
        $this->dateTime        = $dateTime;
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
