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
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage System
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\System;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when a language file shall get loaded.
 */
class LoadLanguageFileEvent extends ContaoApiEvent
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
