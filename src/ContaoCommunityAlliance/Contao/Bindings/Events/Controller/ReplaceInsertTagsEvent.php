<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
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
 * This Event is emitted when the insert tags shall get replaced in some text.
 */
class ReplaceInsertTagsEvent
	extends ContaoApiEvent
{
	/**
	 * The suffix to add.
	 *
	 * @var string
	 */
	protected $buffer;

	/**
	 * The resulting URL.
	 *
	 * @var bool
	 */
	protected $allowCache;

	/**
	 * Create a new instance.
	 *
	 * @param string $buffer     The string in which insert tags shall be replaced.
	 *
	 * @param bool   $allowCache True if caching is allowed, false otherwise (default: true).
	 */
	public function __construct($buffer, $allowCache = true)
	{
		$this->buffer     = $buffer;
		$this->allowCache = $allowCache;
	}

	/**
	 * Retrieve the suffix.
	 *
	 * @return string
	 */
	public function getBuffer()
	{
		return $this->buffer;
	}

	/**
	 * Set the resulting URL.
	 *
	 * @param string $buffer The new URL.
	 *
	 * @return ReplaceInsertTagsEvent
	 */
	public function setBuffer($buffer)
	{
		$this->buffer = $buffer;

		return $this;
	}

	/**
	 * Check if caching is allowed.
	 *
	 * @return bool
	 */
	public function isCachingAllowed()
	{
		return $this->allowCache;
	}
}
