<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings\Api
 * @subpackage Controller
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Api\Controller;

use ContaoCommunityAlliance\Contao\Bindings\Api;
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
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\ReloadEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Controller\ReplaceInsertTagsEvent;

/**
 * Add an enclosure to a template.
 *
 * @param array            $imageData The enclosure data.
 * @param \Template|object $template  The template object.
 * @param string           $key       The key to use in the template.
 *
 * @return void
 */
function addEnclosureToTemplate($imageData, $template, $key = 'enclosure')
{
	$event = new AddEnclosureToTemplateEvent($imageData, $template, $key);

	Api\dispatch(ContaoEvents::CONTROLLER_ADD_ENCLOSURE_TO_TEMPLATE, $event);
}

/**
 * Add an image to a template.
 *
 * @param array            $imageData  The image data.
 * @param \Template|object $template   The template object.
 * @param int|null         $maxWidth   The max image width.
 * @param string|null      $lightboxId The lightbox ID.
 *
 * @return void
 */
function addImageToTemplate($imageData, $template, $maxWidth = null, $lightboxId = null)
{
	$event = new AddImageToTemplateEvent($imageData, $template, $maxWidth, $lightboxId);

	Api\dispatch(ContaoEvents::CONTROLLER_ADD_IMAGE_TO_TEMPLATE, $event);
}

/**
 * Append some value to the current url.
 *
 * @param string $suffix The string to add to the URL.
 *
 * @return string
 */
function addToUrl($suffix)
{
	$event = new AddToUrlEvent($suffix);

	Api\dispatch(ContaoEvents::CONTROLLER_ADD_TO_URL, $event);

	return $event->getUrl();
}

/**
 * Generate a frontend url.
 *
 * @param array       $pageData   The data for the page.
 * @param array|null  $parameters The parameters to use in the url.
 * @param string|null $language   The language code to use in the url.
 *
 * @return string
 */
function generateFrontendUrl(array $pageData, $parameters = null, $language = null)
{
	$event = new GenerateFrontendUrlEvent($pageData, $parameters, $language);

	Api\dispatch(ContaoEvents::CONTROLLER_GENERATE_FRONTEND_URL, $event);

	return $event->getUrl();
}

/**
 * Render an article.
 *
 * @param int    $articleId  The id of the article.
 * @param bool   $teaserOnly Flag determining if only the teaser shall be returned.
 * @param string $column     The column for the content element.
 *
 * @return string
 */
function getArticle($articleId, $teaserOnly = false, $column = 'main')
{
	$event = new GetArticleEvent($articleId, $teaserOnly, $column);

	Api\dispatch(ContaoEvents::CONTROLLER_GET_ARTICLE, $event);

	return $event->getArticle();
}

/**
 * Render a content element.
 *
 * @param int    $contentElementId The id of the content element.
 * @param string $column           The column for the content element.
 *
 * @return string
 */
function getContentElement($contentElementId, $column = 'main')
{
	$event = new GetContentElementEvent($contentElementId, $column);

	Api\dispatch(ContaoEvents::CONTROLLER_GET_CONTENT_ELEMENT, $event);

	return $event->getContentElementHtml();
}

/**
 * Collect details of a page.
 *
 * @param int $pageId The id of the page.
 *
 * @return array
 */
function getPageDetails($pageId)
{
	$event = new GetPageDetailsEvent($pageId);

	Api\dispatch(ContaoEvents::CONTROLLER_GET_PAGE_DETAILS, $event);

	return $event->getPageDetails();
}

/**
 * Collect a template group.
 *
 * @param string $prefix The prefix for the matching templates.
 *
 * @return \ArrayObject
 */
function getTemplateGroup($prefix)
{
	$event = new GetTemplateGroupEvent($prefix);

	Api\dispatch(ContaoEvents::CONTROLLER_GET_TEMPLATE_GROUP, $event);

	return $event->getTemplates();
}

/**
 * Load a data container.
 *
 * @param string $name        The name of the data container to load.
 * @param bool   $ignoreCache Flag if the cache shall get bypassed.
 *
 * @return void
 */
function loadDataContainer($name, $ignoreCache = false)
{
	$event = new LoadDataContainerEvent($name, $ignoreCache);

	Api\dispatch(ContaoEvents::CONTROLLER_LOAD_DATA_CONTAINER, $event);
}

/**
 * Redirect client to another url.
 *
 * @param string $newLocation The target URL.
 * @param int    $statusCode  The HTTP status code (301, 302, 303, 307, defaults to 303).
 *
 * @return void
 */
function redirect($newLocation, $statusCode = 303)
{
	$event = new RedirectEvent($newLocation, $statusCode);

	Api\dispatch(ContaoEvents::CONTROLLER_REDIRECT, $event);
}

/**
 * Reload current url.
 *
 * @return void
 */
function reload()
{
	$event = new ReloadEvent();

	Api\dispatch(ContaoEvents::CONTROLLER_RELOAD, $event);
}

/**
 * Replace insert tags in some text.
 *
 * @param string $buffer     The string in which insert tags shall be replaced.
 * @param bool   $allowCache True if caching is allowed, false otherwise (default: true).
 *
 * @return string
 */
function replaceInsertTags($buffer, $allowCache = true)
{
	$event = new ReplaceInsertTagsEvent($buffer, $allowCache);

	Api\dispatch(ContaoEvents::CONTROLLER_REPLACE_INSERT_TAGS, $event);

	return $event->getBuffer();
}
