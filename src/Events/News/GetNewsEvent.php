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
 * @subpackage News
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2014-2024 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Contao\Bindings\Events\News;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when a news should be rendered.
 *
 * @deprecated The event has been deprecated will get removed in version 5.
 */
class GetNewsEvent extends ContaoApiEvent
{
    /**
     * The news ID.
     *
     * @var int
     */
    protected int $newsId;

    /**
     * The template name.
     *
     * @var string
     */
    protected string $template = 'news_full';

    /**
     * The rendered news html.
     *
     * @var string|null
     */
    protected ?string $newsHtml = null;

    /**
     * Create the event.
     *
     * @param int    $newsId   The news ID.
     * @param string $template The template name.
     */
    public function __construct(int $newsId, string $template = 'news_full')
    {
        $this->newsId   = $newsId;
        $this->template = $template;
    }

    /**
     * Return the news ID.
     *
     * @return int
     */
    public function getNewsId(): int
    {
        return $this->newsId;
    }

    /**
     * Return the template name.
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * Set the rendered news html.
     *
     * @param string $newsHtml The rendered html.
     *
     * @return GetNewsEvent
     */
    public function setNewsHtml(string $newsHtml): self
    {
        $this->newsHtml = $newsHtml;

        return $this;
    }

    /**
     * Return the rendered news html.
     *
     * @return string|null
     */
    public function getNewsHtml(): ?string
    {
        return $this->newsHtml;
    }
}
