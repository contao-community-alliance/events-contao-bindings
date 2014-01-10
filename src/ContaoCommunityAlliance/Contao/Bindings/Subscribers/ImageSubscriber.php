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
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\GenerateHtmlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\ResizeImageEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the System class in Contao.
 */
class ImageSubscriber
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
			ContaoEvents::IMAGE_RESIZE   => 'handleResize',
			ContaoEvents::IMAGE_GET_HTML => 'handleGenerateHtml',
		);
	}

	/**
	 * Handle a resize image event.
	 *
	 * @param ResizeImageEvent $event The event.
	 *
	 * @return void
	 */
	public function handleResize(ResizeImageEvent $event)
	{
		$event->setResultImage(\Image::get(
			$event->getImage(),
			$event->getWidth(),
			$event->getHeight(),
			$event->getMode(),
			$event->getTarget(),
			$event->isForced()
		));
	}

	/**
	 * Handle a get html for image event.
	 *
	 * @param GenerateHtmlEvent $event The event.
	 *
	 * @return void
	 */
	public function handleGenerateHtml(GenerateHtmlEvent $event)
	{
		$event->setHtml(\Image::getHtml(
			$event->getSrc(),
			$event->getAlt(),
			$event->getAttributes()
		));
	}
}
