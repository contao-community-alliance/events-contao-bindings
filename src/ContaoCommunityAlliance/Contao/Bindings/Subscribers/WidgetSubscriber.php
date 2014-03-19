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
use ContaoCommunityAlliance\Contao\Bindings\Events\Widget\GetAttributesFromDcaEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the Widget class in Contao.
 */
class WidgetSubscriber
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
