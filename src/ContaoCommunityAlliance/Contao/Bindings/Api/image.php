<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings\Api
 * @subpackage Controller
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Api\Image;

use ContaoCommunityAlliance\Contao\Bindings\Api;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\GenerateHtmlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\ResizeImageEvent;

/**
 * Generate an image tag and return it as string.
 *
 * @param string $src        The image path.
 * @param string $alt        An optional alt attribute.
 * @param string $attributes A string of other attributes.
 *
 * @return string
 */
function generateHtml($src, $alt = '', $attributes = '')
{
	$event = new GenerateHtmlEvent($src, $alt, $attributes);

	Api\dispatch(ContaoEvents::IMAGE_GET_HTML, $event);

	return $event->getHtml();
}

/**
 * Resize an image and store the resized version in the assets/images folder.
 *
 * @param string      $image  The image path.
 * @param int         $width  The target width.
 * @param int         $height The target height.
 * @param string      $mode   The resize mode.
 * @param null|string $target An optional target path.
 * @param bool        $force  Override existing target images.
 *
 * @return null|string
 */
function resizeImage($image, $width, $height, $mode = '', $target = null, $force = false)
{
	$event = new ResizeImageEvent($image, $width, $height, $mode, $target, $force);

	Api\dispatch(ContaoEvents::IMAGE_RESIZE, $event);

	return $event->getResultImage();
}
