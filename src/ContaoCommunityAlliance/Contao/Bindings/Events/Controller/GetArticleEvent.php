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
	 * @var int
	 */
	protected $articleId;

	/**
	 * @var bool
	 */
	protected $teaserOnly = false;

	/**
	 * @var string
	 */
	protected $column = 'main';

	/**
	 * @var string
	 */
	protected $article;

	public function __construct($articleId, $teaserOnly = false, $column = 'main')
	{
		$this->articleId  = (int) $articleId;
		$this->teaserOnly = (bool) $teaserOnly;
		$this->column     = (string) $column;
	}

	/**
	 * @return int
	 */
	public function getArticleId()
	{
		return $this->articleId;
	}

	/**
	 * @return boolean
	 */
	public function getTeaserOnly()
	{
		return $this->teaserOnly;
	}

	/**
	 * @return string
	 */
	public function getColumn()
	{
		return $this->column;
	}

	/**
	 * @param string $article
	 */
	public function setArticle($article)
	{
		$this->article = $article;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getArticle()
	{
		return $this->article;
	}
}
