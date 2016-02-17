<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings
 * @subpackage Subscribers
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Subscribers;

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
 */
class NewsSubscriber implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            ContaoEvents::NEWS_GET_NEWS => 'handleNews',
        );
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

        $newsArchiveCollection = \NewsArchiveModel::findAll();
        $newsArchiveIds        = $newsArchiveCollection ? $newsArchiveCollection->fetchEach('id') : array();
        $newsModel             = \NewsModel::findPublishedByParentAndIdOrAlias(
            $event->getNewsId(),
            $newsArchiveIds
        );

        if (!$newsModel) {
            return;
        }

        $newsModel = $newsModel->current();

        $newsArchiveModel = $newsModel->getRelated('pid');
        $objPage          = \PageModel::findWithDetails($newsArchiveModel->jumpTo);

        $objTemplate = new \FrontendTemplate($event->getTemplate());
        $objTemplate->setData($newsModel->row());

        $objTemplate->class          = (($newsModel->cssClass != '') ? ' ' . $newsModel->cssClass : '');
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

        // Clean the RTE output.
        if ($newsModel->teaser != '') {
            // PHP 7 compatibility
            // See #309 (https://github.com/contao/core-bundle/issues/309)
            if (version_compare('3.5.5', VERSION . '.' . BUILD, '>=')) {
                if ($objPage->outputFormat == 'xhtml') {
                    $objTemplate->teaser = \StringUtil::toXhtml($newsModel->teaser);
                } else {
                    $objTemplate->teaser = \StringUtil::toHtml5($newsModel->teaser);
                }

                $objTemplate->teaser = \StringUtil::encodeEmail($objTemplate->teaser);
            } else {
                if ($objPage->outputFormat == 'xhtml') {
                    $objTemplate->teaser = \String::toXhtml($newsModel->teaser);
                } else {
                    $objTemplate->teaser = \String::toHtml5($newsModel->teaser);
                }

                $objTemplate->teaser = \String::encodeEmail($objTemplate->teaser);
            }
        }

        // Display the "read more" button for external/article links.
        if ($newsModel->source != 'default') {
            $objTemplate->text = true;
        } else {
            // Compile the news text.
            $objElement = \ContentModel::findPublishedByPidAndTable($newsModel->id, 'tl_news');

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
        if ($newsModel->addImage && $newsModel->singleSRC != '') {
            $objModel = \FilesModel::findByUuid($newsModel->singleSRC);

            if ($objModel === null) {
                if (!\Validator::isUuid($newsModel->singleSRC)) {
                    $objTemplate->text = '<p class="error">' . $GLOBALS['TL_LANG']['ERR']['version2format'] . '</p>';
                }
            } elseif (is_file(TL_ROOT . '/' . $objModel->path)) {
                // Do not override the field now that we have a model registry (see #6303).
                $arrArticle = $newsModel->row();

                // Override the default image size.
                // This is always false!
                if ($this->imgSize != '') {
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

        $objTemplate->enclosure = array();

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
     * @param \NewsModel $objArticle The model.
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
            return array();
        }

        $return = array();

        foreach ($meta as $field) {
            switch ($field) {
                case 'date':
                    $return['date'] = \Date::parse($GLOBALS['objPage']->datimFormat, $objArticle->date);
                    break;

                case 'author':
                    if (($objAuthor = $objArticle->getRelated('author')) !== null) {
                        if ($objAuthor->google != '') {
                            $return['author'] = $GLOBALS['TL_LANG']['MSC']['by'] .
                                ' <a href="https://plus.google.com/' . $objAuthor->google .
                                '" rel="author" target="_blank">' . $objAuthor->name . '</a>';
                        } else {
                            $return['author'] = $GLOBALS['TL_LANG']['MSC']['by'] . ' ' . $objAuthor->name;
                        }
                    }
                    break;

                case 'comments':
                    if ($objArticle->noComments || $objArticle->source != 'default') {
                        break;
                    }
                    $intTotal           = \CommentsModel::countPublishedBySourceAndParent('tl_news', $objArticle->id);
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
     * @param \NewsModel               $objItem         The news model.
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
        \NewsModel $objItem,
        $blnAddArchive = false
    ) {
        $url = null;

        switch ($objItem->source) {
            // Link to an external page.
            case 'external':
                if (substr($objItem->url, 0, 7) == 'mailto:') {
                    // PHP 7 compatibility
                    // See #309 (https://github.com/contao/core-bundle/issues/309)
                    if (version_compare('3.5.5', VERSION . '.' . BUILD, '>=')) {
                        $url = \StringUtil::encodeEmail($objItem->url);
                    } else {
                        $url = \String::encodeEmail($objItem->url);
                    }
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
                if (($objArticle = \ArticleModel::findByPk($objItem->articleId, array('eager' => true))) !== null
                    && ($objPid = $objArticle->getRelated('pid')) !== null
                ) {
                    $generateFrontendUrlEvent = new GenerateFrontendUrlEvent(
                        $objPid->row(),
                        '/articles/' .
                        ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objArticle->alias != '') ? $objArticle->alias : $objArticle->id)
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
            $objPage = \PageModel::findByPk($objItem->getRelated('pid')->jumpTo);

            if ($objPage === null) {
                $url = ampersand(\Environment::get('request'), true);
            } else {
                $generateFrontendUrlEvent = new GenerateFrontendUrlEvent(
                    $objPage->row(),
                    (($GLOBALS['TL_CONFIG']['useAutoItem'] && !$GLOBALS['TL_CONFIG']['disableAlias']) ? '/' : '/items/') .
                    ((!$GLOBALS['TL_CONFIG']['disableAlias'] && $objItem->alias != '') ? $objItem->alias : $objItem->id)
                );

                $eventDispatcher->dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $generateFrontendUrlEvent);

                $url = $generateFrontendUrlEvent->getUrl();
            }

            // Add the current archive parameter (news archive).
            if ($blnAddArchive && \Input::get('month') != '') {
                $url .= ($GLOBALS['TL_CONFIG']['disableAlias'] ? '&amp;' : '?') . 'month=' . \Input::get('month');
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
     * @param \Model                   $objArticle      The model.
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
        if ($objArticle->source != 'external') {
            return sprintf(
                '<a href="%s" title="%s">%s%s</a>',
                $this->generateNewsUrl($eventDispatcher, $objArticle, $blnAddArchive),
                specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['readMore'], $objArticle->headline), true),
                $strLink,
                ($blnIsReadMore ? ' <span class="invisible">' . $objArticle->headline . '</span>' : '')
            );
        }

        // Encode e-mail addresses.
        if (substr($objArticle->url, 0, 7) == 'mailto:') {
            // PHP 7 compatibility
            // See #309 (https://github.com/contao/core-bundle/issues/309)
            if (version_compare('3.5.5', VERSION . '.' . BUILD, '>=')) {
                $strArticleUrl = \StringUtil::encodeEmail($objArticle->url);
            } else {
                $strArticleUrl = \String::encodeEmail($objArticle->url);
            }
        } else {
        // Ampersand URIs.
            $strArticleUrl = ampersand($objArticle->url);
        }

        // External link.
        return sprintf(
            '<a href="%s" title="%s"%s>%s</a>',
            $strArticleUrl,
            specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['open'], $strArticleUrl)),
            $objArticle->target
            ? (
            ($GLOBALS['objPage']->outputFormat == 'xhtml')
                ? ' onclick="return !window.open(this.href)"'
                : ' target="_blank"'
            )
            : '',
            $strLink
        );
    }
}
