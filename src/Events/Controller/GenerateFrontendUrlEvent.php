<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2024 The Contao Community Alliance
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
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2014-2024 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

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
    protected array $pageData;

    /**
     * The parameters to use in the url.
     *
     * @var string|null
     */
    protected ?string $parameters = null;

    /**
     * The language code to use in the url.
     *
     * @var string|null
     */
    protected ?string $language = null;

    /**
     * Check the domain of the target page and append it if necessary.
     *
     * @var bool
     */
    protected bool $fixDomain = false;

    /**
     * The resulting url.
     *
     * @var string
     */
    protected string $url;

    /**
     * Create a new instance.
     *
     * @param array       $pageData   The data for the page.
     * @param string|null $parameters The parameters to use in the url.
     * @param string|null $language   The language code to use in the url.
     *                                This parameter will get dropped in Contao 5.0 (and thus then always be null).
     * @param bool        $fixDomain  Check the domain of the target page and append it if necessary.
     *                                This parameter will get dropped for Contao 5.0 (and thus then always be true).
     */
    public function __construct(
        array $pageData,
        ?string $parameters = null,
        ?string $language = null,
        bool $fixDomain = false
    ) {
        $this->pageData   = $pageData;
        $this->parameters = ('' === $parameters) ? null : $parameters;
        $this->language   = ('' === $language) ? null : $language;
        $this->fixDomain  = $fixDomain;
        $this->url        = '';
    }

    /**
     * Retrtieve the data for the page.
     *
     * @return array
     */
    public function getPageData(): array
    {
        return $this->pageData;
    }

    /**
     * Retrtieve the parameters to use in the url.
     *
     * @return string|null
     */
    public function getParameters(): ?string
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
    public function getLanguage(): ?string
    {
        return $this->language;
    }

    /**
     * Retrieve the check domain flag.
     *
     * This will get dropped for Contao 5.0 (and thus then always be true).
     *
     * @return bool
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getFixDomain(): bool
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
    public function setUrl(string $url): self
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
    public function getUrl(bool $encoded = false): string
    {
        return $encoded ? $this->url : rawurldecode($this->url);
    }
}
