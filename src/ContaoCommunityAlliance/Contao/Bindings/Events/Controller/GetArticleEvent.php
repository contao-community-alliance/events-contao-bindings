<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage Controller
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Controller;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when an article should be rendered.
 */
class GetArticleEvent
	extends ContaoApiEvent
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
		$this->articleId  = (int)$articleId;
		$this->teaserOnly = (bool)$teaserOnly;
		$this->column     = (string)$column;
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
