<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage System
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\System;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when a language file shall get loaded.
 */
class LoadLanguageFileEvent
	extends ContaoApiEvent
{
	/**
	 * Determinator if the cache shall be ignored and the file loaded again.
	 *
	 * @var bool
	 */
	protected $ignoreCache = false;

	/**
	 * The language code of the language in which the file shall get loaded.
	 *
	 * @var null|string
	 */
	protected $language = null;

	/**
	 * The name of the language file to load.
	 *
	 * @var string
	 */
	protected $fileName;

	/**
	 * Create a new instance of the event.
	 *
	 * @param string $fileName    The name of the language file to load.
	 *
	 * @param string $language    Optional language code of the language in which the file shall get loaded.
	 *
	 * @param bool   $ignoreCache Determinator if the cache shall be ignored and the file loaded again.
	 */
	public function __construct($fileName, $language = null, $ignoreCache = false)
	{
		$this->fileName    = $fileName;
		$this->language    = $language;
		$this->ignoreCache = $ignoreCache;
	}

	/**
	 * Set the determinator if the cache shall be ignored and the file loaded again.
	 *
	 * @param boolean $ignoreCache The value.
	 *
	 * @return LoadLanguageFileEvent
	 */
	public function setIgnoreCache($ignoreCache)
	{
		$this->ignoreCache = $ignoreCache;

		return $this;
	}

	/**
	 * Get the determinator if the cache shall be ignored and the file loaded again.
	 *
	 * @return boolean
	 */
	public function isCacheIgnored()
	{
		return $this->ignoreCache;
	}

	/**
	 * Set the language code of the language in which the file shall get loaded.
	 *
	 * @param null|string $language The value.
	 *
	 * @return LoadLanguageFileEvent
	 */
	public function setLanguage($language)
	{
		$this->language = $language;

		return $this;
	}

	/**
	 * Get the language code of the language in which the file shall get loaded.
	 *
	 * @return null|string
	 */
	public function getLanguage()
	{
		return $this->language;
	}

	/**
	 * Set the name of the language file to load.
	 *
	 * @param string $fileName The value.
	 *
	 * @return LoadLanguageFileEvent
	 */
	public function setFileName($fileName)
	{
		$this->fileName = $fileName;

		return $this;
	}

	/**
	 * Get the name of the language file to load.
	 *
	 * @return string
	 */
	public function getFileName()
	{
		return $this->fileName;
	}
}
