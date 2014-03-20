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
use ContaoCommunityAlliance\Contao\Bindings\Events\Message\AddMessageEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the news extension.
 */
class MessageSubscriber
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
			ContaoEvents::MESSAGE_ADD => 'addMessage',
		);
	}

	public function addMessage(AddMessageEvent $event) {
		\Message::add($event->getContent(), 'TL_' . strtoupper($event->getType()));
	}
}
