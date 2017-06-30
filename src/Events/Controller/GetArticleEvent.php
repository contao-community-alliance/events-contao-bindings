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
 * This Event is emitted when an article should be rendered.
 */
class GetArticleEvent extends ContaoApiEvent
{
    /**
     * Id of the article.
     *
     * @var int
     */
    protected $articleId;

    /**
     * Flag determining if only the teaser shall be returned.
     *
     * @var bool
     */
    protected $teaserOnly = false;

    /**
     * The column for the article.
     *
     * @var string
     */
    protected $column = 'main';

    /**
     * The html code for the article.
     *
     * @var string
     */
    protected $article;

    /**
     * Create a new instance.
     *
     * @param int    $articleId  The id of the article.
     *
     * @param bool   $teaserOnly Flag determining if only the teaser shall be returned.
     *
     * @param string $column     The column for the content element.
     */
    public function __construct($articleId, $teaserOnly = false, $column = 'main')
    {
        $this->articleId  = (int) $articleId;
        $this->teaserOnly = (bool) $teaserOnly;
        $this->column     = (string) $column;
    }

    /**
     * Retrieve the id of the article.
     *
     * @return int
     */
    public function getArticleId()
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
    public function getTeaserOnly()
    {
        return $this->teaserOnly;
    }

    /**
     * Retrieve the column for the article.
     *
     * @return string
     */
    public function getColumn()
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
    public function setArticle($article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Retrieve the resulting html code for the article.
     *
     * @return string
     */
    public function getArticle()
    {
        return $this->article;
    }
}
