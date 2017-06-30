<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2016 The Contao Community Alliance
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings
 * @subpackage System
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @copyright  2014 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\BackendSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\CalendarSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\ControllerSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\DateSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\FrontendSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\ImageSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\MessageSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\NewsSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\SystemSubscriber';
$GLOBALS['TL_EVENT_SUBSCRIBERS'][] = 'ContaoCommunityAlliance\Contao\Bindings\Subscribers\WidgetSubscriber';
