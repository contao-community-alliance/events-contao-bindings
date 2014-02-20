<?php

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
}
