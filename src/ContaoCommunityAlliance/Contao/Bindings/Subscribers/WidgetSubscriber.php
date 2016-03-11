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
 * @subpackage Subscribers
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @copyright  2014 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Subscribers;

use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Widget\GetAttributesFromDcaEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the Widget class in Contao.
 */
class WidgetSubscriber implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            ContaoEvents::WIDGET_GET_ATTRIBUTES_FROM_DCA => 'handleGetAttributesFromDca'
        );
    }

    /**
     * Handle the widget preparation.
     *
     * @param GetAttributesFromDcaEvent $event The event.
     *
     * @return void
     */
    public function handleGetAttributesFromDca(GetAttributesFromDcaEvent $event)
    {
        $event->setResult(
            \Widget::getAttributesFromDca(
                $event->getFieldConfiguration(),
                $event->getWidgetName(),
                $event->getValue(),
                $event->getWidgetId(),
                $event->getTable(),
                $event->getDataContainer()
            )
        );
    }
}
