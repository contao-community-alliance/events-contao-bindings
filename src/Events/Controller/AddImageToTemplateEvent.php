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
 * @subpackage Controller
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Controller;

use Contao\Template;
use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted to add an image to a template.
 */
class AddImageToTemplateEvent extends ContaoApiEvent
{
    /**
     * The image data.
     *
     * @var array
     */
    protected $imageData;

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
    protected $maxWidth = null;

    /**
     * The lightbox ID.
     *
     * @var string|null
     */
    protected $lightboxId = null;

    /**
     * Create new event.
     *
     * @param array           $imageData  The image data.
     * @param Template|object $template   The template object.
     * @param int|null        $maxWidth   The max image width.
     * @param string|null     $lightboxId The lightbox ID.
     */
    public function __construct($imageData, $template, $maxWidth = null, $lightboxId = null)
    {
        $this->imageData  = $imageData;
        $this->template   = $template;
        $this->maxWidth   = empty($maxWidth) ? null : (int) $maxWidth;
        $this->lightboxId = empty($lightboxId) ? null : (string) $lightboxId;
    }

    /**
     * Return the image data.
     *
     * @return array
     */
    public function getImageData()
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
    public function getMaxWidth()
    {
        return $this->maxWidth;
    }

    /**
     * Return the lightbox ID, if any is set.
     *
     * @return null|string
     */
    public function getLightboxId()
    {
        return $this->lightboxId;
    }
}
