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
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage Controller
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2017 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Controller;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event generate a frontend url.
 */
class GenerateFrontendUrlEvent extends ContaoApiEvent
{
    /**
     * The data for the page.
     *
     * @var array
     */
    protected $pageData;

    /**
     * The parameters to use in the url.
     *
     * @var string|null
     */
    protected $parameters = null;

    /**
     * The language code to use in the url.
     *
     * @var string|null
     */
    protected $language = null;

    /**
     * Check the domain of the target page and append it if necessary.
     *
     * @var string|null
     */
    protected $fixDomain = false;

    /**
     * The resulting url.
     *
     * @var string
     */
    protected $url;

    /**
     * Create a new instance.
     *
     * @param array       $pageData   The data for the page.
     *
     * @param array|null  $parameters The parameters to use in the url.
     *
     * @param string|null $language   The language code to use in the url.
     *                                This parameter will get dropped in Contao 5.0 (and thus then always be null).
     *
     * @param bool        $fixDomain  Check the domain of the target page and append it if necessary.
     *                                This parameter will get dropped for Contao 5.0 (and thus then always be true).
     */
    public function __construct(array $pageData, $parameters = null, $language = null, $fixDomain = false)
    {
        $this->pageData   = $pageData;
        $this->parameters = empty($parameters) ? null : (string) $parameters;
        $this->language   = empty($language) ? null : (string) $language;
        $this->fixDomain  = (bool) $fixDomain;
    }

    /**
     * Retrtieve the data for the page.
     *
     * @return array
     */
    public function getPageData()
    {
        return $this->pageData;
    }

    /**
     * Retrtieve the parameters to use in the url.
     *
     * @return string|null
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * Retrieve the language code to use in the url.
     *
     * This parameter will get dropped in Contao 5.0 (and thus then always be null).
     *
     * @return string|null
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Retrieve the check domain flag.
     *
     * This will get dropped for Contao 5.0 (and thus then always be true).
     *
     * @return null|string
     */
    public function getFixDomain()
    {
        return $this->fixDomain;
    }

    /**
     * Set the resulting url.
     *
     * @param string $url The resulting url.
     *
     * @return GenerateFrontendUrlEvent
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Retrieve the resulting url.
     *
     * @param bool $encoded Determine if return the encoded url.
     *
     * @return string
     */
    public function getUrl($encoded = false)
    {
        return $encoded ? $this->url : rawurldecode($this->url);
    }
}
