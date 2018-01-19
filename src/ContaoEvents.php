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
 * @subpackage System
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Stefan Heimes <stefan_heimes@hotmail.com>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2017 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings;

/**
 * This class holds all event names.
 */
class ContaoEvents
{
    /**
     * Event for adding parameters to the current url and suffixing it with the current request token.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Backend\AddToUrlEvent
     *
     * @see \Contao\Backend::addToUrl()
     */
    const BACKEND_ADD_TO_URL = 'contao.events.backend.add.to.url';

    /**
     * Event for getting the name of the current active backend theme.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Backend\GetThemeEvent
     *
     * @see \Contao\Backend::getTheme()
     */
    const BACKEND_GET_THEME = 'contao.events.backend.get-theme';

    /**
     * Event for adding parameters to the current url and suffixing it with the current request token.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Frontend\AddToUrlEvent
     *
     * @see \Contao\Frontend::addToUrl()
     */
    const FRONTEND_ADD_TO_URL = 'contao.events.frontend.add.to.url';

    /**
     * Event for adding parameters to the current url.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddToUrlEvent
     *
     * @see \Contao\Controller::addToUrl()
     */
    const CONTROLLER_ADD_TO_URL = 'contao.events.controller.add.to.url';

    /**
     * Event for adding an enclosure to a template.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddEnclosureToTemplateEvent
     *
     * @see \Contao\Controller::addEnclosureToTemplate()
     */
    const CONTROLLER_ADD_ENCLOSURE_TO_TEMPLATE = 'contao.events.controller.add-enclosure-to-template';

    /**
     * Event for adding an image to a template.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Controller\AddImageToTemplateEvent
     *
     * @see \Contao\Controller::addImageToTemplate()
     */
    const CONTROLLER_ADD_IMAGE_TO_TEMPLATE = 'contao.events.controller.add-image-to-template';

    /**
     * Event to generate a frontend url.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GenerateFrontendUrlEvent
     *
     * @see \Contao\Controller::generateFrontendUrl()
     */
    const CONTROLLER_GENERATE_FRONTEND_URL = 'contao.events.controller.generate-frontend-url';

    /**
     * Event for getting a rendered article.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetArticleEvent
     *
     * @see \Contao\Controller::getArticle()
     */
    const CONTROLLER_GET_ARTICLE = 'contao.events.controller.get-article';

    /**
     * Event for getting a rendered content element.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetContentElementEvent
     *
     * @see \Contao\Controller::getContentElement()
     */
    const CONTROLLER_GET_CONTENT_ELEMENT = 'contao.events.controller.get-content-element';

    /**
     * Event for loading details of a page.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetPageDetailsEvent
     *
     * @see \Contao\Controller::getPageDetails()
     */
    const CONTROLLER_GET_PAGE_DETAILS = 'contao.events.controller.get-page-details';

    /**
     * Event for getting a template group.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Controller\GetTemplateGroupEvent
     *
     * @see \Contao\Controller::getTemplateGroup()
     */
    const CONTROLLER_GET_TEMPLATE_GROUP = 'contao.events.controller.get-template-group';

    /**
     * Event for loading a data container (DCA).
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Controller\LoadDataContainerEvent
     *
     * @see \Contao\Controller::loadDataContainer()
     */
    const CONTROLLER_LOAD_DATA_CONTAINER = 'contao.events.controller.load.data.container';

    /**
     * Event for redirecting the client.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Controller\RedirectEvent
     *
     * @see \Contao\Controller::redirect()
     */
    const CONTROLLER_REDIRECT = 'contao.events.controller.redirect';

    /**
     * Event for reloading the current page.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Controller\ReloadEvent
     *
     * @see \Contao\Controller::reload()
     */
    const CONTROLLER_RELOAD = 'contao.events.controller.reload';

    /**
     * Event for replacing Contao Insert Tags.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Controller\ReplaceInsertTagsEvent
     *
     * @see \Contao\Controller::replaceInsertTags()
     */
    const CONTROLLER_REPLACE_INSERT_TAGS = 'contao.events.controller.replace.insert.tags';

    /**
     * Event for parsing the date.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Date\ParseDateEvent
     *
     * @see \Contao\Date::parseDate()
     */
    const DATE_PARSE = 'contao.events.data.parse';

    /**
     * Event for generating a resized copy of an image.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Image\ResizeImageEvent
     *
     * @see \Contao\Image::get()
     */
    const IMAGE_RESIZE = 'contao.events.image.resize';

    /**
     * Event for generating an html tag for an image.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Image\GenerateHtmlEvent
     *
     * @see \Contao\Image::getHtml()
     */
    const IMAGE_GET_HTML = 'contao.events.image.get.html';

    /**
     * Event for getting the current referrer url.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\System\GetReferrerEvent
     *
     * @see \Contao\System::getReferer()
     */
    const SYSTEM_GET_REFERRER = 'contao.events.system.get.referrer';

    /**
     * Event for creating a log entry.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\System\LogEvent
     *
     * @see \Contao\System::log()
     */
    const SYSTEM_LOG = 'contao.events.system.log';

    /**
     * Event for loading a language file.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent
     *
     * @see \Contao\System::loadLanguageFile()
     */
    const SYSTEM_LOAD_LANGUAGE_FILE = 'contao.events.system.load.language.file';

    /**
     * Event for generate a calendar event.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Calendar\GetCalendarEventEvent
     */
    const CALENDAR_GET_EVENT = 'contao.events.calendar.get-event';

    /**
     * Event for generate a news.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\News\GetNewsEvent
     */
    const NEWS_GET_NEWS = 'contao.events.news.get-news';

    /**
     * Event for preparing the configuration array of an widget.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Widget\GetAttributesFromDcaEvent
     */
    const WIDGET_GET_ATTRIBUTES_FROM_DCA = 'contao.events.widget.get.attributes.from.dca';

    /**
     * Event to add a message.
     *
     * @see \ContaoCommunityAlliance\Contao\Bindings\Events\Message\AddMessageEvent
     */
    const MESSAGE_ADD = 'contao.message.add';
}
