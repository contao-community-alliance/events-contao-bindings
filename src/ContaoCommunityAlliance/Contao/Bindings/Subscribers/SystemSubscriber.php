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
use ContaoCommunityAlliance\Contao\Bindings\Events\System\GetReferrerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LogEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the System class in Contao.
 */
class SystemSubscriber
	extends \System
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
			ContaoEvents::SYSTEM_GET_REFERRER       => 'handleGetReferer',
			ContaoEvents::SYSTEM_LOG                => 'handleLog',
			ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE => 'handleLoadLanguageFile',
		);
	}

	/**
	 * Retrieve the current referrer url.
	 *
	 * @param GetReferrerEvent $event The event.
	 *
	 * @return void
	 */
	public function handleGetReferer(GetReferrerEvent $event)
	{
		$event->setReferrerUrl($this->getReferer($event->isEncodeAmpersands(), $event->getTableName()));
	}

	/**
	 * Handle a log event.
	 *
	 * @param LogEvent $event The event.
	 *
	 * @return void
	 */
	public function handleLog(LogEvent $event)
	{
		$this->log($event->getText(), $event->getFunction(), $event->getCategory());
	}

	/**
	 * Handle a load language file event.
	 *
	 * @param LoadLanguageFileEvent $event The event.
	 *
	 * @return void
	 */
	public function handleLoadLanguageFile(LoadLanguageFileEvent $event)
	{
		$this->loadLanguageFile($event->getFileName(), $event->getLanguage(), $event->isCacheIgnored());
	}
}
