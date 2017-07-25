<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2017 The Contao Community Alliance
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings
 * @subpackage Subscribers
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2017 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Subscribers;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\Image;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\GenerateHtmlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Image\ResizeImageEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the System class in Contao.
 */
class ImageSubscriber implements EventSubscriberInterface
{
    /**
     * The contao framework.
     *
     * @var ContaoFrameworkInterface
     */
    protected $framework;

    /**
     * ImageSubscriber constructor.
     *
     * @param ContaoFrameworkInterface $framework The contao framework.
     */
    public function __construct(ContaoFrameworkInterface $framework)
    {
        $this->framework = $framework;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ContaoEvents::IMAGE_RESIZE   => 'handleResize',
            ContaoEvents::IMAGE_GET_HTML => 'handleGenerateHtml',
        ];
    }

    /**
     * Handle a resize image event.
     *
     * @param ResizeImageEvent $event The event.
     *
     * @return void
     *
     * Todo use the contao.image.image_factory instead Image::get.
     */
    public function handleResize(ResizeImageEvent $event)
    {
        $imageAdapter = $this->framework->getAdapter(Image::class);

        $event->setResultImage(
            $imageAdapter->get(
                $event->getImage(),
                $event->getWidth(),
                $event->getHeight(),
                $event->getMode(),
                $event->getTarget(),
                $event->isForced()
            )
        );
    }

    /**
     * Handle a get html for image event.
     *
     * @param GenerateHtmlEvent $event The event.
     *
     * @return void
     */
    public function handleGenerateHtml(GenerateHtmlEvent $event)
    {
        $imageAdapter = $this->framework->getAdapter(Image::class);

        $event->setHtml(
            $imageAdapter->getHtml(
                $event->getSrc(),
                $event->getAlt(),
                $event->getAttributes()
            )
        );
    }
}
