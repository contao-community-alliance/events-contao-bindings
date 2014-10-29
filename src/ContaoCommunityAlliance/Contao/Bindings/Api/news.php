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

namespace ContaoCommunityAlliance\Contao\Bindings\Api\News;

use ContaoCommunityAlliance\Contao\Bindings\Api;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\News\GetNewsEvent;

/**
 * Render a news item.
 *
 * @param int    $newsId   The news ID.
 * @param string $template The template name.
 *
 * @return string
 */
function getNews($newsId, $template = 'news_full')
{
	$event = new GetNewsEvent($newsId, $template);

	Api\dispatch(ContaoEvents::NEWS_GET_NEWS, $event);

	return $event->getNewsHtml();
}
