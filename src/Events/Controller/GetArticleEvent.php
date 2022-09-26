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
 * @subpackage Controller
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Controller;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when an article should be rendered.
 */
class GetArticleEvent extends ContaoApiEvent
{
    /**
     * Id of the article.
     *
     * @var int
     */
    protected int $articleId;

    /**
     * Flag determining if only the teaser shall be returned.
     *
     * @var bool
     */
    protected bool $teaserOnly = false;

    /**
     * The column for the article.
     *
     * @var string
     */
    protected string $column = 'main';

    /**
     * The html code for the article.
     *
     * @var string|null
     */
    protected ?string $article = null;

    /**
     * Create a new instance.
     *
     * @param int    $articleId  The id of the article.
     *
     * @param bool   $teaserOnly Flag determining if only the teaser shall be returned.
     *
     * @param string $column     The column for the content element.
     */
    public function __construct(int $articleId, bool $teaserOnly = false, string $column = 'main')
    {
        $this->articleId  = $articleId;
        $this->teaserOnly = $teaserOnly;
        $this->column     = $column;
    }

    /**
     * Retrieve the id of the article.
     *
     * @return int
     */
    public function getArticleId(): int
    {
        return $this->articleId;
    }

    /**
     * Retrieve the flag determining if only the teaser shall be returned.
     *
     * @return boolean
     *
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getTeaserOnly(): bool
    {
        return $this->teaserOnly;
    }

    /**
     * Retrieve the column for the article.
     *
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * Set the resulting html code for the article.
     *
     * @param string $article The resulting html code.
     *
     * @return GetArticleEvent
     */
    public function setArticle(string $article): self
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Retrieve the resulting html code for the article.
     *
     * @return string|null
     */
    public function getArticle(): ?string
    {
        return $this->article;
    }
}
