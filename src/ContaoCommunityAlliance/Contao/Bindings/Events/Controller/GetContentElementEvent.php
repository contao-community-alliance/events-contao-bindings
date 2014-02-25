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
	 * The id of the content element.
	 *
	 * @var int
	 */
	protected $contentElementId;

	/**
	 * The column for the content element.
	 *
	 * @var string
	 */
	protected $column = 'main';

	/**
	 * The html code for the content element.
	 *
	 * @var string
	 */
	protected $contentElementHtml;

	/**
	 * Create a new instance.
	 *
	 * @param int    $contentElementId The id of the content element.
	 *
	 * @param string $column           The column for the content element.
	 */
	public function __construct($contentElementId, $column = 'main')
	{
		$this->contentElementId = (int)$contentElementId;
		$this->column           = (string)$column;
	}

	/**
	 * Retrieve the id of the content element.
	 *
	 * @return int
	 */
	public function getContentElementId()
	{
		return $this->contentElementId;
	}

	/**
	 * Retrieve the column for the content element.
	 *
	 * @return string
	 */
	public function getColumn()
	{
		return $this->column;
	}

	/**
	 * Set the html code for the content element.
	 *
	 * @param string $contentElement The html code.
	 *
	 * @return GetContentElementEvent
	 */
	public function setContentElementHtml($contentElement)
	{
		$this->contentElementHtml = $contentElement;

		return $this;
	}

	/**
	 * Retrieve the html code for the content element.
	 *
	 * @return string
	 */
	public function getContentElementHtml()
	{
		return $this->contentElementHtml;
	}
}
