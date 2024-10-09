<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2018 The Contao Community Alliance
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
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Contao\Bindings\Subscribers;

use Contao\Controller;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\PageModel;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddEnclosureToTemplateEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddImageToTemplateEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddToUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GenerateFrontendUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetArticleEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetContentElementEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetPageDetailsEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetTemplateGroupEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\ReplaceInsertTagsEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the Controller class in Contao.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ControllerSubscriber implements EventSubscriberInterface
{
    /**
     * The contao framework.
     *
     * @var ContaoFramework
     */
    protected ContaoFramework $framework;

    /**
     * ControllerSubscriber constructor.
     *
     * @param ContaoFramework $framework The contao framework.
     */
    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }

    public static function getSubscribedEvents(): array
    {
        return [
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
            ContaoEvents::CONTROLLER_REPLACE_INSERT_TAGS       => 'handleReplaceInsertTags',
        ];
    }

    /**
     * Add some suffix to the current URL.
     *
     * @param AddToUrlEvent $event The event.
     *
     * @return void
     */
    public function handleAddToUrl(AddToUrlEvent $event): void
    {
        /**
         * @var Controller $controllerAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $controllerAdapter = $this->framework->getAdapter(Controller::class);

        $event->setUrl($controllerAdapter->addToUrl($event->getSuffix()));
    }

    /**
     * Add an enclosure to a template.
     *
     * @param AddEnclosureToTemplateEvent $event The event.
     *
     * @return void
     */
    public function handleAddEnclosureToTemplate(AddEnclosureToTemplateEvent $event): void
    {
        /**
         * @var Controller $controllerAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $controllerAdapter = $this->framework->getAdapter(Controller::class);

        $controllerAdapter->addEnclosuresToTemplate(
            $event->getTemplate(),
            $event->getEnclosureData(),
            $event->getKey()
        );
    }

    /**
     * Add an image to a template.
     *
     * @param AddImageToTemplateEvent $event The event.
     *
     * @return void
     *
     * @deprecated The event has been deprecated will get removed in version 5.
     *
     * @psalm-suppress DeprecatedClass
     */
    public function handleAddImageToTemplate(AddImageToTemplateEvent $event): void
    {
        /**
         * @var Controller $controllerAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $controllerAdapter = $this->framework->getAdapter(Controller::class);

        /** @psalm-suppress DeprecatedMethod */
        $controllerAdapter->addImageToTemplate(
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
    public function handleGenerateFrontendUrl(GenerateFrontendUrlEvent $event): void
    {
        /**
         * @var Controller $controllerAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $controllerAdapter = $this->framework->getAdapter(Controller::class);

        /** @psalm-suppress DeprecatedMethod */
        $url = $controllerAdapter->generateFrontendUrl(
            $event->getPageData(),
            $event->getParameters(),
            $event->getLanguage(),
            $event->getFixDomain()
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
    public function handleGetArticle(GetArticleEvent $event): void
    {
        /**
         * @var Controller $controllerAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $controllerAdapter = $this->framework->getAdapter(Controller::class);

        $article = $controllerAdapter->getArticle(
            $event->getArticleId(),
            $event->getTeaserOnly(),
            true,
            $event->getColumn()
        );
        if (!is_string($article)) {
            return;
        }

        $event->setArticle($article);
    }

    /**
     * Render an content element.
     *
     * @param GetContentElementEvent $event The event.
     *
     * @return void
     */
    public function handleGetContentElement(GetContentElementEvent $event): void
    {
        /**
         * @var Controller $controllerAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $controllerAdapter = $this->framework->getAdapter(Controller::class);

        $contentElement = $controllerAdapter->getContentElement(
            $event->getContentElementId(),
            $event->getColumn()
        );

        $event->setContentElementHtml($contentElement);
    }

    /**
     * Collect details for a page.
     *
     * @param GetPageDetailsEvent $event The event.
     *
     * @return void
     */
    public function handleGetPageDetails(GetPageDetailsEvent $event): void
    {
        /**
         * @var PageModel $pageModelAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $pageModelAdapter = $this->framework->getAdapter(PageModel::class);

        $page = $pageModelAdapter->findWithDetails($event->getPageId());

        if ($page) {
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
    public function handleGetTemplateGroup(GetTemplateGroupEvent $event): void
    {
        /**
         * @var Controller $controllerAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $controllerAdapter = $this->framework->getAdapter(Controller::class);

        $templatesArray = $controllerAdapter->getTemplateGroup($event->getPrefix());
        $templates      = $event->getTemplates();

        /**
         * @var string $templateName
         * @var string $templateLabel
         */
        foreach ($templatesArray as $templateName => $templateLabel) {
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
    public function handleLoadDataContainer(LoadDataContainerEvent $event): void
    {
        /**
         * @var Controller $controllerAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $controllerAdapter = $this->framework->getAdapter(Controller::class);

        $controllerAdapter->loadDataContainer($event->getName(), $event->isCacheIgnored());
    }

    /**
     * Handle a redirect event.
     *
     * @param RedirectEvent $event The event.
     *
     * @return void
     */
    public function handleRedirect(RedirectEvent $event): void
    {
        /**
         * @var Controller $controllerAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $controllerAdapter = $this->framework->getAdapter(Controller::class);

        $controllerAdapter->redirect($event->getNewLocation(), $event->getStatusCode());
    }

    /**
     * Reload the current page.
     *
     * @return void
     */
    public function handleReload(): void
    {
        /**
         * @var Controller $controllerAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $controllerAdapter = $this->framework->getAdapter(Controller::class);

        $controllerAdapter->reload();
    }

    /**
     * Replace insert tags.
     *
     * @param ReplaceInsertTagsEvent $event The event.
     *
     * @return void
     */
    public function handleReplaceInsertTags(ReplaceInsertTagsEvent $event): void
    {
        /**
         * @var Controller $controllerAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $controllerAdapter = $this->framework->getAdapter(Controller::class);

        /** @psalm-suppress DeprecatedMethod */
        $result = $controllerAdapter->replaceInsertTags($event->getBuffer(), $event->isCachingAllowed());

        $event->setBuffer($result);
    }
}
