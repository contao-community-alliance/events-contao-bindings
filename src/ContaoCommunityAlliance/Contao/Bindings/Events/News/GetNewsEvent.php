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

namespace ContaoCommunityAlliance\Contao\Bindings\Events\News;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when a news should be rendered.
 */
class GetNewsEvent
	extends ContaoApiEvent
{
	/**
	 * @var int
	 */
	protected $newsId;

	/**
	 * @var string
	 */
	protected $template = 'news_full';

	/**
	 * @var string
	 */
	protected $newsHtml;

	/**
	 * @param int  $newsId     The news ID.
	 * @param bool $teaserOnly Generate the teaser only.
	 */
	function __construct($newsId, $template = 'news_full')
	{
		$this->newsId   = (int) $newsId;
		$this->template = (string) $template;
	}

	/**
	 * Return the calendar event ID.
	 *
	 * @return int
	 */
	public function getNewsId()
	{
		return $this->newsId;
	}

	/**
	 * @return string
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * @param string $calendarEvent
	 */
	public function setNewsHtml($calendarEvent)
	{
		$this->newsHtml = $calendarEvent;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getNewsHtml()
	{
		return $this->newsHtml;
	}
}
