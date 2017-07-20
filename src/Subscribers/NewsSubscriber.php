<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2017 The Contao Community Alliance
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
 * @copyright  2017 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Subscribers;

use Contao\ArticleModel;
use Contao\CommentsModel;
use Contao\ContentModel;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\Date;
use Contao\Environment;
use Contao\FilesModel;
use Contao\FrontendTemplate;
use Contao\Input;
use Contao\Model;
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
use ContaoCommunityAlliance\Contao\Bindings\Util\StringHelper;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the news extension.
 */
class NewsSubscriber implements EventSubscriberInterface
{
    /**
     * The contao framework.
     *
     * @var ContaoFrameworkInterface
     */
    protected $framework;

    /**
     * NewsSubscriber constructor.
     *
     * @param ContaoFrameworkInterface $framework
     */
    public function __construct(ContaoFrameworkInterface $framework)
    {
        $this->framework = $framework;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ContaoEvents::NEWS_GET_NEWS => 'handleNews',
        ];
    }

    /**
     * Render a news.
     *
     * @param GetNewsEvent             $event           The event.
     *
     * @param string                   $eventName       The event name.
     *
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
     */
    public function handleNews(GetNewsEvent $event, $eventName, EventDispatcherInterface $eventDispatcher)
    {
        if ($event->getNewsHtml()) {
            return;
        }


        $newsArchiveModelAdapter = $this->framework->getAdapter(NewsArchiveModel::class);
        $newsModelAdapter        = $this->framework->getAdapter(NewsModel::class);

        $newsArchiveCollection = $newsArchiveModelAdapter->findAll();
        $newsArchiveIds        = $newsArchiveCollection ? $newsArchiveCollection->fetchEach('id') : [];
        $newsModel             = $newsModelAdapter->findPublishedByParentAndIdOrAlias(
            $event->getNewsId(),
            $newsArchiveIds
        );

        if (!$newsModel) {
            return;
        }

        $newsModel = $newsModel->current();

        $pageModelAdapter = $this->framework->getAdapter(PageModel::class);

        $newsArchiveModel = $newsModel->getRelated('pid');
        $objPage          = $pageModelAdapter->findWithDetails($newsArchiveModel->jumpTo);

        $frontendTemplateAdapter = $this->framework->getAdapter(FrontendTemplate::class);

        $objTemplate = new $frontendTemplateAdapter($event->getTemplate());
        $objTemplate->setData($newsModel->row());

        $objTemplate->class          = (!empty($newsModel->cssClass) ? ' ' . $newsModel->cssClass : '');
        $objTemplate->newsHeadline   = $newsModel->headline;
        $objTemplate->subHeadline    = $newsModel->subheadline;
        $objTemplate->hasSubHeadline = $newsModel->subheadline ? true : false;
        $objTemplate->linkHeadline   = $this->generateLink($eventDispatcher, $newsModel->headline, $newsModel);
        $objTemplate->more           = $this->generateLink(
            $eventDispatcher,
            $GLOBALS['TL_LANG']['MSC']['more'],
            $newsModel,
            false,
            true
        );
        $objTemplate->link           = $this->generateNewsUrl($eventDispatcher, $newsModel);
        $objTemplate->archive        = $newsModel->getRelated('pid');
        $objTemplate->count          = 0;
        $objTemplate->text           = '';


        if (!empty($newsModel->teaser)) {
            $stringUtilAdapter = $this->framework->getAdapter(StringUtil::class); // Clean the RTE output.

            $objTemplate->teaser = $stringUtilAdapter->encodeEmail($stringUtilAdapter->toHtml5($newsModel->teaser));
        }

        // Display the "read more" button for external/article links.
        if ($newsModel->source !== 'default') {
            $objTemplate->text = true;
        } else {
            $contentModelAdapter = $this->framework->getAdapter(ContentModel::class);

            // Compile the news text.
            $objElement = $contentModelAdapter->findPublishedByPidAndTable($newsModel->id, 'tl_news');

            if ($objElement !== null) {
                while ($objElement->next()) {
                    $getContentElementEvent = new GetContentElementEvent($objElement->id);

                    $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GET_CONTENT_ELEMENT, $getContentElementEvent);

                    $objTemplate->text .= $getContentElementEvent->getContentElementHtml();
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
        $objTemplate->datetime         = date('Y-m-d\TH:i:sP', $newsModel->date);

        $objTemplate->addImage = false;

        // Add an image.
        if ($newsModel->addImage && !empty($newsModel->singleSRC)) {
            $filesModelAdapter = $this->framework->getAdapter(FilesModel::class);

            $objModel = $filesModelAdapter->findByUuid($newsModel->singleSRC);

            if ($objModel === null) {
                $validatorAdapter = $this->framework->getAdapter(Validator::class);

                if (!$validatorAdapter->isUuid($newsModel->singleSRC)) {
                    $objTemplate->text = '<p class="error">' . $GLOBALS['TL_LANG']['ERR']['version2format'] . '</p>';
                }
            } elseif (is_file(TL_ROOT . '/' . $objModel->path)) {
                // Do not override the field now that we have a model registry (see #6303).
                $arrArticle = $newsModel->row();

                // Override the default image size.
                // This is always false!
                if (!empty($this->imgSize)) {
                    $size = deserialize($this->imgSize);

                    if ($size[0] > 0 || $size[1] > 0) {
                        $arrArticle['size'] = $this->imgSize;
                    }
                }

                $arrArticle['singleSRC'] = $objModel->path;

                $addImageToTemplateEvent = new AddImageToTemplateEvent($arrArticle, $objTemplate);

                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_ADD_IMAGE_TO_TEMPLATE, $addImageToTemplateEvent);
            }
        }

        $objTemplate->enclosure = [];

        // Add enclosures.
        if ($newsModel->addEnclosure) {
            $addEnclosureToTemplateEvent = new AddEnclosureToTemplateEvent($newsModel->row(), $objTemplate);

            $eventDispatcher->dispatch(
                ContaoEvents::CONTROLLER_ADD_ENCLOSURE_TO_TEMPLATE,
                $addEnclosureToTemplateEvent
            );
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
     */
    protected function getMetaFields($objArticle)
    {
        $meta = deserialize($this->news_metaFields);

        if (!is_array($meta)) {
            return [];
        }

        $return = [];

        foreach ($meta as $field) {
            switch ($field) {
                case 'date':
                    $dateAdapter = $this->framework->getAdapter(Date::class);

                    $return['date'] = $dateAdapter->parse($GLOBALS['objPage']->datimFormat, $objArticle->date);
                    break;

                case 'author':
                    if (($objAuthor = $objArticle->getRelated('author')) !== null) {
                        if (!empty($objAuthor->google)) {
                            $return['author'] = $GLOBALS['TL_LANG']['MSC']['by'] .
                                ' <a href="https://plus.google.com/' . $objAuthor->google .
                                '" rel="author" target="_blank">' . $objAuthor->name . '</a>';
                        } else {
                            $return['author'] = $GLOBALS['TL_LANG']['MSC']['by'] . ' ' . $objAuthor->name;
                        }
                    }
                    break;

                case 'comments':
                    if ($objArticle->noComments || $objArticle->source !== 'default') {
                        break;
                    }

                    $commentsModelAdapter = $this->framework->getAdapter(CommentsModel::class);

                    $intTotal           = $commentsModelAdapter->countPublishedBySourceAndParent(
                        'tl_news',
                        $objArticle->id
                    );
                    $return['ccount']   = $intTotal;
                    $return['comments'] = sprintf($GLOBALS['TL_LANG']['MSC']['commentCount'], $intTotal);
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
     *
     * @param NewsModel                $objItem         The news model.
     *
     * @param boolean                  $blnAddArchive   Add the current archive parameter (news archive) (default: false).
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function generateNewsUrl(
        EventDispatcherInterface $eventDispatcher,
        NewsModel $objItem,
        $blnAddArchive = false
    ) {
        $url = null;

        switch ($objItem->source) {
            // Link to an external page.
            case 'external':
                if (substr($objItem->url, 0, 7) === 'mailto:') {
                    $url = StringHelper::encodeEmail($objItem->url);
                } else {
                    $url = ampersand($objItem->url);
                }
                break;

            // Link to an internal page.
            case 'internal':
                if (($objTarget = $objItem->getRelated('jumpTo')) !== null) {
                    $generateFrontendUrlEvent = new GenerateFrontendUrlEvent($objTarget->row());

                    $eventDispatcher->dispatch(
                        ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL,
                        $generateFrontendUrlEvent
                    );

                    $url = $generateFrontendUrlEvent->getUrl();
                }
                break;

            // Link to an article.
            case 'article':
                $articleModelAdapter = $this->framework->getAdapter(ArticleModel::class);

                if (($objArticle = $articleModelAdapter->findByPk($objItem->articleId, ['eager' => true])) !== null
                    && ($objPid = $objArticle->getRelated('pid')) !== null
                ) {
                    $generateFrontendUrlEvent = new GenerateFrontendUrlEvent(
                        $objPid->row(),
                        '/articles/' .
                        ((!$GLOBALS['TL_CONFIG']['disableAlias'] && !empty($objArticle->alias)) ? $objArticle->alias : $objArticle->id)
                    );

                    $eventDispatcher->dispatch(
                        ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL,
                        $generateFrontendUrlEvent
                    );

                    $url = $generateFrontendUrlEvent->getUrl();
                }
                break;

            default:
        }

        // Link to the default page.
        if ($url === null) {
            $pageModelAdapter = $this->framework->getAdapter(PageModel::class);

            $objPage = $pageModelAdapter->findByPk($objItem->getRelated('pid')->jumpTo);

            if ($objPage === null) {
                $url = ampersand(Environment::get('request'), true);
            } else {
                $generateFrontendUrlEvent = new GenerateFrontendUrlEvent(
                    $objPage->row(),
                    (($GLOBALS['TL_CONFIG']['useAutoItem'] && !$GLOBALS['TL_CONFIG']['disableAlias']) ? '/' : '/items/') .
                    ((!$GLOBALS['TL_CONFIG']['disableAlias'] && !empty($objItem->alias)) ? $objItem->alias : $objItem->id)
                );

                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $generateFrontendUrlEvent);

                $url = $generateFrontendUrlEvent->getUrl();
            }

            $inputAdapter = $this->framework->getAdapter(Input::class);

            // Add the current archive parameter (news archive).
            if ($blnAddArchive && !empty($inputAdapter->get('month'))) {
                $url .= ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?') . 'month=' . $inputAdapter->get('month');
            }
        }

        return $url;
    }
    // @codingStandardsIgnoreEnd

    /**
     * Generate a link and return it as string.
     *
     * @param EventDispatcherInterface $eventDispatcher The event dispatcher.
     *
     * @param string                   $strLink         The link text.
     *
     * @param Model                    $objArticle      The model.
     *
     * @param bool                     $blnAddArchive   Add the current archive parameter (news archive)
     *                                                  (default: false).
     *
     * @param bool                     $blnIsReadMore   Determine if the link is a "read more" link.
     *
     * @return string
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     */
    protected function generateLink(
        EventDispatcherInterface $eventDispatcher,
        $strLink,
        $objArticle,
        $blnAddArchive = false,
        $blnIsReadMore = false
    ) {
        // Internal link.
        if ($objArticle->source !== 'external') {
            return sprintf(
                '<a href="%s" title="%s">%s%s</a>',
                $this->generateNewsUrl($eventDispatcher, $objArticle, $blnAddArchive),
                specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $objArticle->headline), true),
                $strLink,
                ($blnIsReadMore ? ' <span class="invisible">' . $objArticle->headline . '</span>' : '')
            );
        }

        $stringUtilAdapter = $this->framework->getAdapter(StringUtil::class);

        // Encode e-mail addresses.
        if (substr($objArticle->url, 0, 7) === 'mailto:') {
            $strArticleUrl = $stringUtilAdapter->encodeEmail($objArticle->url);
        } else {
        // Ampersand URIs.
            $strArticleUrl = ampersand($objArticle->url);
        }

        // External link.
        return sprintf(
            '<a href="%s" title="%s"%s>%s</a>',
            $strArticleUrl,
            specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['open'], $strArticleUrl)),
            $objArticle->target ? ' target="_blank"' : '',
            $strLink
        );
    }
}
