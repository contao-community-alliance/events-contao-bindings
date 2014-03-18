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
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\AddToUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\GetThemeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the Backend class in Contao.
 */
class BackendSubscriber
	extends \Backend
	implements EventSubscriberInterface
{
	// @codingStandardsIgnoreStart
	/**
	 * Create a new instance.
	 */
	public function __construct()
	{
		parent::__construct();
	}
	// @codingStandardsIgnoreEnd

	/**
	 * Returns an array of event names this subscriber wants to listen to.
	 *
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return array(
			ContaoEvents::BACKEND_ADD_TO_URL => 'handleAddToUrl',
			ContaoEvents::BACKEND_GET_THEME  => 'handleGetTheme',
		);
	}

	/**
	 * Add some suffix to the current URL.
	 *
	 * @param AddToUrlEvent $event The event.
	 *
	 * @return void
	 */
	public function handleAddToUrl(AddToUrlEvent $event)
	{
		$event->setUrl($this->addToUrl($event->getSuffix()));
	}

	/**
	 * Add some suffix to the current URL.
	 *
	 * @param GetThemeEvent $event The event.
	 *
	 * @return void
	 */
	public function handleGetTheme(GetThemeEvent $event)
	{
		$event->setTheme($this->getTheme());
	}
}
