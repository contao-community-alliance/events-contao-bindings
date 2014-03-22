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

	/**
	 * Add a message to the contao message array in the session.
	 *
	 * @param AddMessageEvent $event The event.
	 *
	 * @return void
	 *
	 * @throws \Exception When an invalid message type is encountered.
	 */
	public function addMessage(AddMessageEvent $event)
	{
		if ($event->getContent() == '')
		{
			return;
		}

		$type = 'TL_' . strtoupper($event->getType());

		if (!in_array($type, array('TL_ERROR', 'TL_CONFIRM', 'TL_NEW', 'TL_INFO', 'TL_RAW')))
		{
			throw new \Exception('Invalid message type ' . $type);
		}

		// @codingStandardsIgnoreStart - Access to $_SESSION is ok in this circumstance.
		if (!is_array($_SESSION[$type]))
		{
			$_SESSION[$type] = array();
		}

		$_SESSION[$type][] = $event->getContent();
		// @codingStandardsIgnoreEnd
	}
}
