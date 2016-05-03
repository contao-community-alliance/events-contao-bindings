<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2016 The Contao Community Alliance
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
 * @copyright  2014 The Contao Community Alliance.
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
     */
    public function __construct(array $pageData, $parameters = null, $language = null)
    {
        $this->pageData   = $pageData;
        $this->parameters = empty($parameters) ? null : (string) $parameters;
        $this->language   = empty($language) ? null : (string) $language;
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
     * Retrtieve the language code to use in the url.
     *
     * @return string|null
     */
    public function getLanguage()
    {
        return $this->language;
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
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
}
