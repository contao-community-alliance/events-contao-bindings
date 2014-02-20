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
 * This Event is emitted to add an image to a template.
 */
class AddImageToTemplateEvent
	extends ContaoApiEvent
{
	/**
	 * @var array
	 */
	protected $imageData;

	/**
	 * @var \Template|object
	 */
	protected $template;

	/**
	 * @var int|null
	 */
	protected $maxWidth = null;

	/**
	 * @var string|null
	 */
	protected $lightboxId = null;

	function __construct($imageData, $template, $maxWidth = null, $lightboxId = null)
	{
		$this->imageData = $imageData;
		$this->template  = $template;
		$this->maxWidth  = empty($maxWidth) ? null : (int) $maxWidth;
		$this->lightboxId = empty($lightboxId) ? null : (string) $lightboxId;
	}

	/**
	 * @return array
	 */
	public function getImageData()
	{
		return $this->imageData;
	}

	/**
	 * @return \Template|object
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * @return int|null
	 */
	public function getMaxWidth()
	{
		return $this->maxWidth;
	}

	/**
	 * @return null|string
	 */
	public function getLightboxId()
	{
		return $this->lightboxId;
	}
}
