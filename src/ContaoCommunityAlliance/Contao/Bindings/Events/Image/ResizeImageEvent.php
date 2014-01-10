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
 * This Event is emitted to resize an image and store the resized version in the assets/images folder.
 */
class ResizeImageEvent
	extends ContaoApiEvent
{
	/**
	 * Use upper left corner.
	 */
	const MODE_LEFT_TOP = 'left_top';

	/**
	 * Use upper centered.
	 */
	const MODE_CENTER_TOP = 'center_top';

	/**
	 * Use upper right corner.
	 */
	const MODE_RIGHT_TOP = 'right_top';

	/**
	 * Use left center.
	 */
	const MODE_LEFT_CENTER = 'left_center';

	/**
	 * Use the center of the image.
	 */
	const MODE_CENTER_CENTER = 'center_center';

	/**
	 * Use right center.
	 */
	const MODE_RIGHT_CENTER = 'right_center';

	/**
	 * Use lower left corner.
	 */
	const MODE_LEFT_BOTTOM = 'left_bottom';

	/**
	 * Use bottom centered.
	 */
	const MODE_CENTER_BOTTOM = 'center_bottom';

	/**
	 * Use lower right corner.
	 */
	const MODE_RIGHT_BOTTOM = 'right_bottom';

	/**
	 * Resize proportional.
	 */
	const MODE_PROPORTIONAL = 'proportional';

	/**
	 * Fit image into box.
	 */
	const MODE_BOX = 'box';

	/**
	 * The image path.
	 *
	 * @var string
	 */
	protected $image;

	/**
	 * The target width.
	 *
	 * @var int
	 */
	protected $width;

	/**
	 * The target height.
	 *
	 * @var int
	 */
	protected $height;

	/**
	 * The resize mode.
	 *
	 * @var string
	 */
	protected $mode;

	/**
	 * An optional target path.
	 *
	 * @var null|string
	 */
	protected $target;

	/**
	 * Override existing target images.
	 *
	 * @var bool
	 */
	protected $force;

	/**
	 * The path of the resized image or null.
	 *
	 * @var string|null
	 */
	protected $resultImage;

	/**
	 * Create a new instance.
	 *
	 * @param string      $image  The image path.
	 *
	 * @param int         $width  The target width.
	 *
	 * @param int         $height The target height.
	 *
	 * @param string      $mode   The resize mode.
	 *
	 * @param null|string $target An optional target path.
	 *
	 * @param bool        $force  Override existing target images.
	 */
	public function __construct($image, $width, $height, $mode = '', $target = null, $force = false)
	{
		$this->image  = $image;
		$this->width  = $width;
		$this->height = $height;
		$this->mode   = $mode;
		$this->target = $target;
		$this->force  = $force;
	}

	/**
	 * Determine if image regenerating is forced.
	 *
	 * @return boolean
	 */
	public function isForced()
	{
		return $this->force;
	}

	/**
	 * Retrieve the height.
	 *
	 * @return int
	 */
	public function getHeight()
	{
		return $this->height;
	}

	/**
	 * Retrieve the source image.
	 *
	 * @return string
	 */
	public function getImage()
	{
		return $this->image;
	}

	/**
	 * Retrieve the mode.
	 *
	 * @return string
	 */
	public function getMode()
	{
		return $this->mode;
	}

	/**
	 * Set the resized image.
	 *
	 * @param string|null $resultImage The resized image or null.
	 *
	 * @return ResizeImageEvent
	 */
	public function setResultImage($resultImage)
	{
		$this->resultImage = $resultImage;

		return $this;
	}

	/**
	 * Get the path of the resized image or null.
	 *
	 * @return string|null
	 */
	public function getResultImage()
	{
		return $this->resultImage;
	}

	/**
	 * Get the target.
	 *
	 * @return null|string
	 */
	public function getTarget()
	{
		return $this->target;
	}

	/**
	 * Get the width.
	 *
	 * @return int
	 */
	public function getWidth()
	{
		return $this->width;
	}
}
