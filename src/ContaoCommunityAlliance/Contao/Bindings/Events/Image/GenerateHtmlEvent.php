<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage Image
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Image;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted to generate an html image tag.
 */
class GenerateHtmlEvent
	extends ContaoApiEvent
{
	/**
	 * An optional alt attribute.
	 *
	 * @var string
	 */
	protected $alt;

	/**
	 * A string of other attributes.
	 *
	 * @var string
	 */
	protected $attributes;

	/**
	 * The image path.
	 *
	 * @var string
	 */
	protected $src;

	/**
	 * Resulting output.
	 *
	 * @var string
	 */
	protected $html;

	/**
	 * Generate an image tag and return it as string.
	 *
	 * @param string $src        The image path.
	 *
	 * @param string $alt        An optional alt attribute.
	 *
	 * @param string $attributes A string of other attributes.
	 */
	public function __construct($src, $alt = '', $attributes = '')
	{
		$this->src        = $src;
		$this->alt        = $alt;
		$this->attributes = $attributes;
	}

	/**
	 * Get the optional alt attribute.
	 *
	 * @return string
	 */
	public function getAlt()
	{
		return $this->alt;
	}

	/**
	 * Get the string of other attributes.
	 *
	 * @return string
	 */
	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 * Get the image path.
	 *
	 * @return string
	 */
	public function getSrc()
	{
		return $this->src;
	}

	/**
	 * Set the generated html representation.
	 *
	 * @param string $html The generated html string.
	 *
	 * @return GenerateHtmlEvent
	 */
	public function setHtml($html)
	{
		$this->html = $html;

		return $this;
	}

	/**
	 * Get the generated html representation.
	 *
	 * @return string
	 */
	public function getHtml()
	{
		return $this->html;
	}
}
