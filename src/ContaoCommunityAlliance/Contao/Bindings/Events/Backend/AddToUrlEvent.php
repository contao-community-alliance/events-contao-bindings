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
 * This Event is emitted when the client shall append some value to the current URL.
 */
class AddToUrlEvent
	extends ContaoApiEvent
{
	/**
	 * The suffix to add.
	 *
	 * @var string
	 */
	protected $suffix;

	/**
	 * The resulting URL.
	 *
	 * @var string
	 */
	protected $newUrl;

	/**
	 * Create a new instance.
	 *
	 * @param string $suffix The string to add to the URL.
	 */
	public function __construct($suffix)
	{
		$this->suffix = $suffix;
	}

	/**
	 * Retrieve the suffix.
	 *
	 * @return string
	 */
	public function getSuffix()
	{
		return $this->suffix;
	}

	/**
	 * Set the resulting URL.
	 *
	 * @param string $newUrl The new URL.
	 *
	 * @return AddToUrlEvent
	 */
	public function setUrl($newUrl)
	{
		$this->newUrl = $newUrl;

		return $this;
	}

	/**
	 * Retrieve the new URL.
	 *
	 * @return string
	 */
	public function getUrl()
	{
		return $this->newUrl;
	}
}
