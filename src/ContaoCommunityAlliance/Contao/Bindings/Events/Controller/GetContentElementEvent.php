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
 * This Event is emitted when a content element should be rendered.
 */
class GetContentElementEvent
	extends ContaoApiEvent
{
	/**
	 * @var int
	 */
	protected $contentElementId;

	/**
	 * @var string
	 */
	protected $column = 'main';

	/**
	 * @var string
	 */
	protected $contentElement;

	function __construct($contentElementId, $column = 'main')
	{
		$this->contentElementId  = (int) $contentElementId;
		$this->column     = (string) $column;
	}

	/**
	 * @return int
	 */
	public function getContentElementId()
	{
		return $this->contentElementId;
	}

	/**
	 * @return string
	 */
	public function getColumn()
	{
		return $this->column;
	}

	/**
	 * @param string $contentElement
	 */
	public function setContentElement($contentElement)
	{
		$this->contentElement = $contentElement;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getContentElement()
	{
		return $this->contentElement;
	}
}
