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
 * @subpackage Image
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Image;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted to generate an html image tag.
 */
class GenerateHtmlEvent extends ContaoApiEvent
{
    /**
     * An optional alt attribute.
     *
     * @var string
     */
    protected string $alt;

    /**
     * A string of other attributes.
     *
     * @var string
     */
    protected string $attributes;

    /**
     * The image path.
     *
     * @var string
     */
    protected string $src;

    /**
     * Resulting output.
     *
     * @var string|null
     */
    protected ?string $html;

    /**
     * Generate an image tag and return it as string.
     *
     * @param string $src        The image path.
     *
     * @param string $alt        An optional alt attribute.
     *
     * @param string $attributes A string of other attributes.
     */
    public function __construct(string $src, string $alt = '', string $attributes = '')
    {
        $this->src        = $src;
        $this->alt        = $alt;
        $this->attributes = $attributes;
        $this->html       = null;
    }

    /**
     * Get the optional alt attribute.
     *
     * @return string
     */
    public function getAlt(): string
    {
        return $this->alt;
    }

    /**
     * Get the string of other attributes.
     *
     * @return string
     */
    public function getAttributes(): string
    {
        return $this->attributes;
    }

    /**
     * Get the image path.
     *
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }

    /**
     * Set the generated html representation.
     *
     * @param string $html The generated html string.
     *
     * @return GenerateHtmlEvent
     */
    public function setHtml(string $html): self
    {
        $this->html = $html;

        return $this;
    }

    /**
     * Get the generated html representation.
     *
     * @return string|null
     */
    public function getHtml(): ?string
    {
        return $this->html;
    }
}
