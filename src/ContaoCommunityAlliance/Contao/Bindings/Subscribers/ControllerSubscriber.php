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
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddToUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\ReloadEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the Controller class in Contao.
 */
class ControllerSubscriber
	extends \Controller
	implements EventSubscriberInterface
{
	/**
	 * Kill parent constructor.
	 */
	public function __construct()
	{
	}

	/**
	 * Returns an array of event names this subscriber wants to listen to.
	 *
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return array(
			ContaoEvents::CONTROLLER_ADD_TO_URL          => 'handleAddToUrl',
			ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER => 'handleLoadDataContainer',
			ContaoEvents::CONTROLLER_REDIRECT            => 'handleRedirect',
			ContaoEvents::CONTROLLER_RELOAD              => 'handleReload',
		);
	}

	/**
	 * Add some suffix to the current URL.
	 *
	 * @param AddToUrlEvent $event The event.
	 *
	 * @return void
	 */
	public static function handleAddToUrl(AddToUrlEvent $event)
	{
		$event->setUrl(\Controller::addToUrl($event->getSuffix()));
	}

	/**
	 * Load a data container.
	 *
	 * @param LoadDataContainerEvent $event The event.
	 *
	 * @return void
	 */
	public function handleLoadDataContainer(LoadDataContainerEvent $event)
	{
		parent::loadDataContainer($event->getName(), $event->isCacheIgnored());
	}

	/**
	 * Handle a redirect event.
	 *
	 * @param RedirectEvent $event The event.
	 *
	 * @return void
	 */
	public static function handleRedirect(RedirectEvent $event)
	{
		\Controller::redirect($event->getNewLocation(), $event->getStatusCode());
	}

	/**
	 * Reload the current page.
	 *
	 * @param ReloadEvent $event The event.
	 *
	 * @return void
	 */
	public static function handleReload(ReloadEvent $event)
	{
		\Controller::reload();
	}
}
