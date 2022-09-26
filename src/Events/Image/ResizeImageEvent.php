<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2018 The Contao Community Alliance
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage Image
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Image;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted to resize an image and store the resized version in the assets/images folder.
 */
class ResizeImageEvent extends ContaoApiEvent
{
    /**
     * Use upper left corner.
     */
    public const MODE_LEFT_TOP = 'left_top';

    /**
     * Use upper centered.
     */
    public const MODE_CENTER_TOP = 'center_top';

    /**
     * Use upper right corner.
     */
    public const MODE_RIGHT_TOP = 'right_top';

    /**
     * Use left center.
     */
    public const MODE_LEFT_CENTER = 'left_center';

    /**
     * Use the center of the image.
     */
    public const MODE_CENTER_CENTER = 'center_center';

    /**
     * Use right center.
     */
    public const MODE_RIGHT_CENTER = 'right_center';

    /**
     * Use lower left corner.
     */
    public const MODE_LEFT_BOTTOM = 'left_bottom';

    /**
     * Use bottom centered.
     */
    public const MODE_CENTER_BOTTOM = 'center_bottom';

    /**
     * Use lower right corner.
     */
    public const MODE_RIGHT_BOTTOM = 'right_bottom';

    /**
     * Resize proportional.
     */
    public const MODE_PROPORTIONAL = 'proportional';

    /**
     * Fit image into box.
     */
    public const MODE_BOX = 'box';

    /**
     * The image path.
     *
     * @var string
     */
    protected string $image;

    /**
     * The target width.
     *
     * @var int
     */
    protected int $width;

    /**
     * The target height.
     *
     * @var int
     */
    protected int $height;

    /**
     * The resize mode.
     *
     * @var string
     */
    protected string $mode;

    /**
     * An optional target path.
     *
     * @var null|string
     */
    protected ?string $target;

    /**
     * Override existing target images.
     *
     * @var bool
     */
    protected bool $force;

    /**
     * The path of the resized image or null.
     *
     * @var string|null
     */
    protected ?string $resultImage = null;

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
     * @param string|null $target An optional target path.
     *
     * @param bool        $force  Override existing target images.
     */
    public function __construct(
        string $image,
        int $width,
        int $height,
        string $mode = '',
        ?string $target = null,
        bool $force = false
    ) {
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
    public function isForced(): bool
    {
        return $this->force;
    }

    /**
     * Retrieve the height.
     *
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * Retrieve the source image.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Retrieve the mode.
     *
     * @return string
     */
    public function getMode(): string
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
    public function setResultImage(?string $resultImage): self
    {
        $this->resultImage = $resultImage;

        return $this;
    }

    /**
     * Get the path of the resized image or null.
     *
     * @return string|null
     */
    public function getResultImage(): ?string
    {
        return $this->resultImage;
    }

    /**
     * Get the target.
     *
     * @return null|string
     */
    public function getTarget(): ?string
    {
        return $this->target;
    }

    /**
     * Get the width.
     *
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }
}
