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

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Message;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when a message should be added to the session.
 */
class AddMessageEvent
	extends ContaoApiEvent
{
	const TYPE_ERROR = 'error';

	const TYPE_CONFIRM = 'confirm';

	const TYPE_NEW = 'new';

	const TYPE_INFO = 'info';

	const TYPE_RAW = 'raw';

	static public function createError($content)
	{
		return new static($content, 'error');
	}

	static public function createConfirm($content)
	{
		return new static($content, 'confirm');
	}

	static public function createNew($content)
	{
		return new static($content, 'new');
	}

	static public function createInfo($content)
	{
		return new static($content, 'info');
	}

	static public function createRaw($content)
	{
		return new static($content);
	}

	/**
	 * @var string
	 */
	protected $content;

	/**
	 * @var string
	 */
	protected $type;

	public function __construct($content, $type = self::TYPE_RAW)
	{
		$this->content = $content;
		$this->type    = $type;
	}

	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}
}
