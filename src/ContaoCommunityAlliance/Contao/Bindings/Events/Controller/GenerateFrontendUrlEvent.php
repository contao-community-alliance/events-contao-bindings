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
 * This Event generate a frontend url.
 */
class GenerateFrontendUrlEvent
	extends ContaoApiEvent
{
	/**
	 * @var array
	 */
	protected $pageData;

	/**
	 * @var string|null
	 */
	protected $parameters = null;

	/**
	 * @var string|null
	 */
	protected $language = null;

	/**
	 * @var string
	 */
	protected $url;

	function __construct(array $pageData, $parameters = null, $language = null)
	{
		$this->pageData   = $pageData;
		$this->parameters = empty($parameters) ? null : (string) $parameters;
		$this->language   = empty($language) ? null : (string) $language;
	}

	/**
	 * @return array
	 */
	public function getPageData()
	{
		return $this->pageData;
	}

	/**
	 * @return string|null
	 */
	public function getParameters()
	{
		return $this->parameters;
	}

	/**
	 * @return string|null
	 */
	public function getLanguage()
	{
		return $this->language;
	}

	/**
	 * @param string $contentElement
	 */
	public function setUrl($contentElement)
	{
		$this->url = $contentElement;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return $this->url;
	}
}
