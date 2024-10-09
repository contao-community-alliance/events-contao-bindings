<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2024 The Contao Community Alliance
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage Controller
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2014-2024 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Controller;

use Contao\Template;
use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted to add an image to a template.
 *
 * @deprecated The event has been deprecated will get removed in version 5.
 */
class AddImageToTemplateEvent extends ContaoApiEvent
{
    /**
     * The image data.
     *
     * @var array
     */
    protected array $imageData;

    /**
     * The template object.
     *
     * @var Template|object
     */
    protected $template;

    /**
     * The max image width.
     *
     * @var int|null
     */
    protected ?int $maxWidth = null;

    /**
     * The lightbox ID.
     *
     * @var string|null
     */
    protected ?string $lightboxId = null;

    /**
     * Create new event.
     *
     * @param array           $imageData  The image data.
     * @param Template|object $template   The template object.
     * @param int|null        $maxWidth   The max image width.
     * @param string|null     $lightboxId The lightbox ID.
     */
    public function __construct(array $imageData, $template, ?int $maxWidth = null, ?string $lightboxId = null)
    {
        $this->imageData  = $imageData;
        $this->template   = $template;
        $this->maxWidth   = (0 === $maxWidth) ? null : $maxWidth;
        $this->lightboxId = ('' === $lightboxId) ? null : $lightboxId;
    }

    /**
     * Return the image data.
     *
     * @return array
     */
    public function getImageData(): array
    {
        return $this->imageData;
    }

    /**
     * Return the template object, to add the image.
     *
     * @return Template|object
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Return the max image width, if any is set.
     *
     * @return int|null
     */
    public function getMaxWidth(): ?int
    {
        return $this->maxWidth;
    }

    /**
     * Return the lightbox ID, if any is set.
     *
     * @return null|string
     */
    public function getLightboxId(): ?string
    {
        return $this->lightboxId;
    }
}
