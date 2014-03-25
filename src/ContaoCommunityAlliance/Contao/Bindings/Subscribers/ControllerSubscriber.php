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
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddEnclosureToTemplateEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddImageToTemplateEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GenerateFrontendUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetArticleEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetContentElementEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetPageDetailsEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetTemplateGroupEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddToUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\ReloadEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Widget\GetAttributesFromDcaEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the Controller class in Contao.
 */
class ControllerSubscriber
	extends \Controller
	implements EventSubscriberInterface
{
	// @codingStandardsIgnoreStart - This override is not useless as the style checker thinks.
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
			ContaoEvents::CONTROLLER_ADD_TO_URL                => 'handleAddToUrl',
			ContaoEvents::CONTROLLER_ADD_ENCLOSURE_TO_TEMPLATE => 'handleAddEnclosureToTemplate',
			ContaoEvents::CONTROLLER_ADD_IMAGE_TO_TEMPLATE     => 'handleAddImageToTemplate',
			ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL     => 'handleGenerateFrontendUrl',
			ContaoEvents::CONTROLLER_GET_ARTICLE               => 'handleGetArticle',
			ContaoEvents::CONTROLLER_GET_CONTENT_ELEMENT       => 'handleGetContentElement',
			ContaoEvents::CONTROLLER_GET_PAGE_DETAILS          => 'handleGetPageDetails',
			ContaoEvents::CONTROLLER_GET_TEMPLATE_GROUP        => 'handleGetTemplateGroup',
			ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER       => 'handleLoadDataContainer',
			ContaoEvents::CONTROLLER_REDIRECT                  => 'handleRedirect',
			ContaoEvents::CONTROLLER_RELOAD                    => 'handleReload',
			// Was moved from Controller to Widget class in Contao 3.2.
			ContaoEvents::WIDGET_GET_ATTRIBUTES_FROM_DCA       => 'handleGetAttributesFromDca'
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
	 * Add an enclosure to a template.
	 *
	 * @param AddEnclosureToTemplateEvent $event The event.
	 *
	 * @return void
	 */
	public function handleAddEnclosureToTemplate(AddEnclosureToTemplateEvent $event)
	{
		$dummy = new \stdClass();

		$this->addEnclosuresToTemplate(
			$dummy,
			$event->getEnclosureData()
		);

		$event->getTemplate()->{$event->getKey()} = $dummy->enclosure;
	}

	/**
	 * Add an image to a template.
	 *
	 * @param AddImageToTemplateEvent $event The event.
	 *
	 * @return void
	 */
	public function handleAddImageToTemplate(AddImageToTemplateEvent $event)
	{
		$this->addImageToTemplate(
			$event->getTemplate(),
			$event->getImageData(),
			$event->getMaxWidth(),
			$event->getLightboxId()
		);
	}

	/**
	 * Generate a frontend url.
	 *
	 * @param GenerateFrontendUrlEvent $event The event.
	 *
	 * @return void
	 */
	public function handleGenerateFrontendUrl(GenerateFrontendUrlEvent $event)
	{
		$url = $this->generateFrontendUrl(
			$event->getPageData(),
			$event->getParameters(),
			$event->getLanguage()
		);

		$event->setUrl($url);
	}

	/**
	 * Render an article.
	 *
	 * @param GetArticleEvent $event The event.
	 *
	 * @return void
	 */
	public function handleGetArticle(GetArticleEvent $event)
	{
		$article = $this->getArticle(
			$event->getArticleId(),
			$event->getTeaserOnly(),
			true,
			$event->getColumn()
		);

		$event->setArticle($article);
	}

	/**
	 * Render an content element.
	 *
	 * @param GetContentElementEvent $event The event.
	 *
	 * @return void
	 */
	public function handleGetContentElement(GetContentElementEvent $event)
	{
		$contentElement = $this->getContentElement(
			$event->getContentElementId(),
			$event->getColumn()
		);

		$event->setContentElementHtml($contentElement);
	}

	/**
	 * Collect details for a page.
	 *
	 * @param GetPageDetailsEvents $event The event.
	 *
	 * @return void
	 */
	public function handleGetPageDetails(GetPageDetailsEvents $event)
	{
		$page = $this->getPageDetails($event->getPageId());

		if ($page)
		{
			$event->setPageDetails($page->row());
		}
	}

	/**
	 * Collect a template group.
	 *
	 * @param GetTemplateGroupEvent $event The event.
	 *
	 * @return void
	 */
	public function handleGetTemplateGroup(GetTemplateGroupEvent $event)
	{
		$templatesArray = $this->getTemplateGroup($event->getPrefix());
		$templates      = $event->getTemplates();

		foreach ($templatesArray as $templateName => $templateLabel)
		{
			$templates[$templateName] = $templateLabel;
		}
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
	public function handleRedirect(RedirectEvent $event)
	{
		$this->redirect($event->getNewLocation(), $event->getStatusCode());
	}

	/**
	 * Reload the current page.
	 *
	 * @param ReloadEvent $event The event.
	 *
	 * @return void
	 */
	public function handleReload(ReloadEvent $event)
	{
		$this->reload();
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
			$this->prepareForWidget(
				$event->getFieldConfiguration(),
				$event->getWidgetName(),
				$event->getValue(),
				$event->getWidgetId(),
				$event->getTable()
			)
		);
	}
}
