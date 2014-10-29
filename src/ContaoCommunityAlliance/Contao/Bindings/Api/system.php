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

namespace ContaoCommunityAlliance\Contao\Bindings\Api\System;

use ContaoCommunityAlliance\Contao\Bindings\Api;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\GetReferrerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LogEvent;

/**
 * Return the current referer URL and optionally encode ampersands.
 *
 * @param boolean $encodeAmpersands If true, ampersands will be encoded.
 * @param string  $tableName        An optional table name.
 *
 * @return string
 */
function getReferrer($encodeAmpersands = false, $tableName = null)
{
	$event = new GetReferrerEvent($encodeAmpersands, $tableName);

	Api\dispatch(ContaoEvents::SYSTEM_GET_REFERRER, $event);

	return $event->getReferrerUrl();
}

/**
 * Load a language file.
 *
 * @param string $fileName    The name of the language file to load.
 * @param string $language    Optional language code of the language in which the file shall get loaded.
 * @param bool   $ignoreCache Determinator if the cache shall be ignored and the file loaded again.
 *
 * @return void
 */
function loadLanguageFile($fileName, $language = null, $ignoreCache = false)
{
	$event = new LoadLanguageFileEvent($fileName, $language, $ignoreCache);

	Api\dispatch(ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE, $event);
}

/**
 * Add a log entry to the database.
 *
 * @param string $text     The log message.
 * @param string $function The function name.
 * @param string $category The category name.
 *
 * @return void
 */
function log($text, $function, $category)
{
	$event = new LogEvent($text, $function, $category);

	Api\dispatch(ContaoEvents::SYSTEM_LOG, $event);
}
