<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2024 The Contao Community Alliance
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
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2014-2024 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Contao\Bindings\Subscribers;

use Contao\ArticleModel;
use Contao\CommentsModel;
use Contao\ContentModel;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Date;
use Contao\Environment;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\Input;
use Contao\NewsArchiveModel;
use Contao\NewsModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\Validator;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddEnclosureToTemplateEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddImageToTemplateEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GenerateFrontendUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetContentElementEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\News\GetNewsEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the news extension.
 *
 * @deprecated The event has been deprecated will get removed in version 5.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class NewsSubscriber implements EventSubscriberInterface
{
    /**
     * The contao framework.
     *
     * @var ContaoFramework
     */
    protected ContaoFramework $framework;

    /**
     * NewsSubscriber constructor.
     *
     * @param ContaoFramework $framework The contao framework.
     */
    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }

    public static function getSubscribedEvents(): array
    {
        /** @psalm-suppress DeprecatedConstant */
        return [
            ContaoEvents::NEWS_GET_NEWS => 'handleNews',
        ];
    }

    /**
     * Render a news.
     *
     * @param GetNewsEvent             $event           The event.
     * @param string                   $eventName       The event name.
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
     *
     * @return void
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @psalm-suppress MixedArrayAccess - The global access can not be typed.
     * @psalm-suppress UndefinedMagicPropertyAssignment
     * @psalm-suppress UndefinedMagicPropertyFetch
     * @psalm-suppress UndefinedConstant
     * @psalm-suppress DeprecatedClass
     */
    public function handleNews(GetNewsEvent $event, string $eventName, EventDispatcherInterface $eventDispatcher): void
    {
        if (null === $event->getNewsHtml()) {
            return;
        }

        /**
         * @var NewsArchiveModel $archiveModelAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $archiveModelAdapter = $this->framework->getAdapter(NewsArchiveModel::class);
        /**
         * @var NewsModel $newsModelAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $newsModelAdapter  = $this->framework->getAdapter(NewsModel::class);
        $archiveCollection = $archiveModelAdapter->findAll();
        $newsArchiveIds    = $archiveCollection ? $archiveCollection->fetchEach('id') : [];
        $newsModel         = $newsModelAdapter->findPublishedByParentAndIdOrAlias(
            $event->getNewsId(),
            $newsArchiveIds
        );

        if (!$newsModel) {
            return;
        }

        $newsModel = $newsModel->current();

        /**  @psalm-suppress InternalMethod */
        $objTemplate = $this->framework->createInstance(FrontendTemplate::class, [$event->getTemplate()]);
        $objTemplate->setData($newsModel->row());

        $objTemplate->class          = (!empty($newsModel->cssClass) ? ' ' . $newsModel->cssClass : '');
        $objTemplate->newsHeadline   = $newsModel->headline;
        $objTemplate->subHeadline    = $newsModel->subheadline;
        $objTemplate->hasSubHeadline = $newsModel->subheadline ? true : false;
        $objTemplate->linkHeadline   = $this->generateLink($eventDispatcher, $newsModel->headline, $newsModel);
        $objTemplate->more           = $this->generateLink(
            $eventDispatcher,
            (string) $GLOBALS['TL_LANG']['MSC']['more'],
            $newsModel,
            false,
            true
        );
        $objTemplate->link           = $this->generateNewsUrl($eventDispatcher, $newsModel);
        $objTemplate->archive        = $newsModel->getRelated('pid');
        $objTemplate->count          = 0;
        $objTemplate->text           = '';


        if (null !== $newsModel->teaser) {
            // Clean the RTE output.
            /**
             * @var StringUtil $stringUtilAdapter
             * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
             */
            $stringUtilAdapter = $this->framework->getAdapter(StringUtil::class);

            /** @psalm-suppress DeprecatedMethod */
            $objTemplate->teaser = $stringUtilAdapter->encodeEmail($stringUtilAdapter->toHtml5($newsModel->teaser));
        }

        // Display the "read more" button for external/article links.
        if ($newsModel->source !== 'default') {
            $objTemplate->text = true;
        } else {
            /**
             * @var ContentModel $contentModelAdapter
             * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
             */
            $contentModelAdapter = $this->framework->getAdapter(ContentModel::class);

            // Compile the news text.
            /** @var \Contao\Model\Collection|null $objElement */
            $objElement = $contentModelAdapter->findPublishedByPidAndTable((int) $newsModel->id, 'tl_news');

            if ($objElement !== null) {
                while ($objElement->next()) {
                    $contentElementEvent = new GetContentElementEvent((int) $objElement->id);

                    $eventDispatcher->dispatch($contentElementEvent, ContaoEvents::CONTROLLER_GET_CONTENT_ELEMENT);
                    /** @psalm-suppress MixedOperand */
                    $objTemplate->text .= (string) $contentElementEvent->getContentElementHtml();
                }
            }
        }

        $arrMeta = $this->getMetaFields($newsModel);

        // Add the meta information.
        $objTemplate->date             = $arrMeta['date'];
        $objTemplate->hasMetaFields    = !empty($arrMeta);
        $objTemplate->numberOfComments = $arrMeta['ccount'];
        $objTemplate->commentCount     = $arrMeta['comments'];
        $objTemplate->timestamp        = $newsModel->date;
        $objTemplate->author           = $arrMeta['author'];
        $objTemplate->datetime         = date('Y-m-d\TH:i:sP', (int) $newsModel->date);

        $objTemplate->addImage = false;

        // Add an image.
        if ((bool) $newsModel->addImage && null !== $newsModel->singleSRC) {
            /**
             * @var FilesModel $filesModelAdapter
             * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
             */
            $filesModelAdapter = $this->framework->getAdapter(FilesModel::class);

            $objModel = $filesModelAdapter->findByUuid($newsModel->singleSRC);

            if ($objModel === null) {
                /**
                 * @var Validator $validatorAdapter
                 * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
                 */
                $validatorAdapter = $this->framework->getAdapter(Validator::class);

                if (!$validatorAdapter->isUuid($newsModel->singleSRC)) {
                    $objTemplate->text = sprintf(
                        '<p class="error">%1$s</p>',
                        (string) $GLOBALS['TL_LANG']['ERR']['version2format']
                    );
                }
            } elseif (is_file(TL_ROOT . '/' . $objModel->path)) {
                // Do not override the field now that we have a model registry (see #6303).
                $arrArticle = $newsModel->row();

                // Override the default image size.
                // FIXME: This is always false!
                if (!empty($imgSize = (string) $this->imgSize)) {
                    /**
                     * @var StringUtil $stringUtilAdapter
                     * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
                     */
                    $stringUtilAdapter = $this->framework->getAdapter(StringUtil::class);

                    /** @var list<string> $size */
                    $size = $stringUtilAdapter->deserialize($imgSize);

                    if ($size[0] > 0 || $size[1] > 0) {
                        $arrArticle['size'] = $imgSize;
                    }
                }

                $arrArticle['singleSRC'] = $objModel->path;

                /** @psalm-suppress DeprecatedClass */
                $imageEvent = new AddImageToTemplateEvent($arrArticle, $objTemplate);

                $eventDispatcher->dispatch($imageEvent, ContaoEvents::CONTROLLER_ADD_IMAGE_TO_TEMPLATE);
            }
        }

        $objTemplate->enclosure = [];

        // Add enclosures.
        if ((bool) $newsModel->addEnclosure) {
            $enclosureEvent = new AddEnclosureToTemplateEvent($newsModel->row(), $objTemplate);

            $eventDispatcher->dispatch($enclosureEvent, ContaoEvents::CONTROLLER_ADD_ENCLOSURE_TO_TEMPLATE);
        }

        $news = $objTemplate->parse();
        $event->setNewsHtml($news);
    }

    /**
     * Return the meta fields of a news article as array.
     *
     * @param NewsModel $objArticle The model.
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     *
     * @psalm-suppress MixedArrayAccess - The global access can not be typed.
     */
    protected function getMetaFields(NewsModel $objArticle): array
    {
        /**
         * @var StringUtil $stringUtilAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $stringUtilAdapter = $this->framework->getAdapter(StringUtil::class);

        /** @var list<string>|null $meta */
        // FIXME: news_metaFields is in tl_module - can not reach from here.
        $meta = $stringUtilAdapter->deserialize(/*$this->news_metaFields*/'');

        if (!is_array($meta)) {
            return [];
        }

        $return = [];

        foreach ($meta as $field) {
            switch ($field) {
                case 'date':
                    /**
                     * @var Date $dateAdapter
                     * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
                     */
                    $dateAdapter = $this->framework->getAdapter(Date::class);
                    /** @var PageModel $page */
                    $page = $GLOBALS['objPage'];
                    $return['date'] = $dateAdapter->parse($page->datimFormat, (int) $objArticle->date);
                    break;

                case 'author':
                    /** @var \Contao\BackendUser|null $objAuthor */
                    $objAuthor = $objArticle->getRelated('author');
                    if ($objAuthor !== null) {
                        if (!empty($objAuthor->google)) {
                            $return['author'] = (string) $GLOBALS['TL_LANG']['MSC']['by'] .
                                ' <a href="https://plus.google.com/' . (string) $objAuthor->google .
                                '" rel="author" target="_blank">' . $objAuthor->name . '</a>';
                        } else {
                            $return['author'] = (string) $GLOBALS['TL_LANG']['MSC']['by'] . ' ' . $objAuthor->name;
                        }
                    }
                    break;

                case 'comments':
                    if ((bool) $objArticle->noComments || $objArticle->source !== 'default') {
                        break;
                    }

                    /**
                     * @var CommentsModel $commentsModelAdapter
                     * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
                     */
                    $commentsModelAdapter = $this->framework->getAdapter(CommentsModel::class);
                    $intTotal             = $commentsModelAdapter->countPublishedBySourceAndParent(
                        'tl_news',
                        (int) $objArticle->id
                    );
                    $return['ccount']   = $intTotal;
                    $return['comments'] = sprintf((string) $GLOBALS['TL_LANG']['MSC']['commentCount'], $intTotal);
                    break;
                default:
            }
        }

        return $return;
    }


    // @codingStandardsIgnoreStart - this is currently too complex but not worth the hassle of refactoring.
    /**
     * Generate a URL and return it as string.
     *
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
     * @param NewsModel                $objItem         The news model.
     * @param boolean                  $blnAddArchive   Add the current archive parameter (news archive) (default: false).
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     *
     * @psalm-suppress MixedArrayAccess - The global access can not be typed.
     */
    protected function generateNewsUrl(
        EventDispatcherInterface $eventDispatcher,
        NewsModel $objItem,
        bool $blnAddArchive = false
    ): string {
        /**
         * @var StringUtil $stringUtilAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $stringUtilAdapter = $this->framework->getAdapter(StringUtil::class);

        $url = null;

        switch ($objItem->source) {
            // Link to an external page.
            case 'external':
                if (substr($objItem->url, 0, 7) === 'mailto:') {
                    $url = $stringUtilAdapter->encodeEmail($objItem->url);
                } else {
                    $url = $stringUtilAdapter->ampersand($objItem->url);
                }
                break;

            // Link to an internal page.
            case 'internal':
                if (($objTarget = $objItem->getRelated('jumpTo')) !== null) {
                    $event = new GenerateFrontendUrlEvent($objTarget->row());
                    $eventDispatcher->dispatch($event, ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL);
                    $url = $event->getUrl();
                }
                break;

            // Link to an article.
            case 'article':
                /**
                 * @var ArticleModel $articleModelAdapter
                 * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
                 */
                $articleModelAdapter = $this->framework->getAdapter(ArticleModel::class);
                /** @var ArticleModel|null $objArticle */
                $objArticle = $articleModelAdapter->findByPk($objItem->articleId, ['eager' => true]);
                if (($objArticle !== null)
                    && ($objPid = $objArticle->getRelated('pid')) !== null
                ) {
                    $event = new GenerateFrontendUrlEvent(
                        $objPid->row(),
                        '/articles/' .
                        ((!$GLOBALS['TL_CONFIG']['disableAlias'] && !empty($objArticle->alias)) ? $objArticle->alias : $objArticle->id)
                    );

                    $eventDispatcher->dispatch($event, ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL);

                    $url = $event->getUrl();
                }
                break;

            default:
        }

        // Link to the default page.
        if ($url === null) {
            /**
             * @var PageModel $pageModelAdapter
             * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
             */
            $pageModelAdapter = $this->framework->getAdapter(PageModel::class);
            /** @var NewsArchiveModel $related */
            $related = $objItem->getRelated('pid');
            /** @var PageModel|null $objPage */
            $objPage = $pageModelAdapter->findByPk($related->jumpTo);

            if ($objPage === null) {
                $url = $stringUtilAdapter->ampersand((string) Environment::get('request'), true);
            } else {
                $event = new GenerateFrontendUrlEvent(
                    $objPage->row(),
                    (($GLOBALS['TL_CONFIG']['useAutoItem'] && !$GLOBALS['TL_CONFIG']['disableAlias']) ? '/' : '/items/') .
                    ((!$GLOBALS['TL_CONFIG']['disableAlias'] && !empty($objItem->alias)) ? $objItem->alias : $objItem->id)
                );

                $eventDispatcher->dispatch($event, ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL);

                $url = $event->getUrl();
            }

            /**
             * @var Input $inputAdapter
             * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
             */
            $inputAdapter = $this->framework->getAdapter(Input::class);

            // Add the current archive parameter (news archive).
            if ($blnAddArchive && !empty($inputAdapter->get('month'))) {
                $url .= ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?')
                        . 'month=' . (string) $inputAdapter->get('month');
            }
        }

        return $url;
    }
    // @codingStandardsIgnoreEnd

    /**
     * Generate a link and return it as string.
     *
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
     * @param string                   $strLink         The link text.
     * @param NewsModel                $objArticle      The model.
     * @param bool                     $blnAddArchive   Add the current archive parameter (news archive)
     *                                                  (default: false).
     * @param bool                     $blnIsReadMore   Determine if the link is a "read more" link.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     *
     * @psalm-suppress MixedArrayAccess - The global access can not be typed.
     */
    protected function generateLink(
        EventDispatcherInterface $eventDispatcher,
        string $strLink,
        NewsModel $objArticle,
        bool $blnAddArchive = false,
        bool $blnIsReadMore = false
    ): string {
        /**
         * @var StringUtil $stringUtilAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $stringUtilAdapter = $this->framework->getAdapter(StringUtil::class);

        // Internal link.
        if ($objArticle->source !== 'external') {
            return sprintf(
                '<a href="%s" title="%s">%s%s</a>',
                $this->generateNewsUrl($eventDispatcher, $objArticle, $blnAddArchive),
                $stringUtilAdapter->specialchars(
                    sprintf((string) $GLOBALS['TL_LANG']['MSC']['readMore'], $objArticle->headline),
                    true
                ),
                $strLink,
                ($blnIsReadMore ? ' <span class="invisible">' . $objArticle->headline . '</span>' : '')
            );
        }

        // Encode e-mail addresses.
        if (substr($objArticle->url, 0, 7) === 'mailto:') {
            $strArticleUrl = $stringUtilAdapter->encodeEmail($objArticle->url);
        } else {
        // Ampersand URIs.
            $strArticleUrl = $stringUtilAdapter->ampersand($objArticle->url);
        }

        // External link.
        return sprintf(
            '<a href="%s" title="%s"%s>%s</a>',
            $strArticleUrl,
            $stringUtilAdapter->specialchars(sprintf((string) $GLOBALS['TL_LANG']['MSC']['open'], $strArticleUrl)),
            (bool) $objArticle->target ? ' target="_blank"' : '',
            $strLink
        );
    }
}
