<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 * @package    ContaoCommunityAlliance\Contao\Bindings
 * @subpackage System
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\BackendSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\CalendarSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\ControllerSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\DateSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\ImageSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\NewsSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\MessageSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\SystemSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\WidgetSubscriber';
