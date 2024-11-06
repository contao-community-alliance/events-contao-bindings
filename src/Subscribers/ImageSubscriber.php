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
 * @package    ContaoCommunityAlliance\Contao\Bindings
 * @subpackage Subscribers
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2014-2024 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Contao\Bindings\Subscribers;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Image\ImageFactoryInterface;
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
     * @var ContaoFramework
     */
    protected ContaoFramework $framework;

    /**
     * The image factory.
     *
     * @var ImageFactoryInterface
     */
    private ImageFactoryInterface $imageFactory;

    /**
     * Project root dir.
     *
     * @var string
     */
    private string $rootDir;

    /**
     * ImageSubscriber constructor.
     *
     * @param ContaoFramework       $framework    The contao framework.
     * @param ImageFactoryInterface $imageFactory The image factory.
     * @param string                $rootDir      Project root dir.
     */
    public function __construct(ContaoFramework $framework, ImageFactoryInterface $imageFactory, string $rootDir)
    {
        $this->framework    = $framework;
        $this->imageFactory = $imageFactory;
        $this->rootDir      = $rootDir;
    }

    public static function getSubscribedEvents(): array
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
     */
    public function handleResize(ResizeImageEvent $event): void
    {
        $image = $this->imageFactory->create(
            $this->rootDir . '/' . $event->getImage(),
            [$event->getWidth(), $event->getHeight(), $event->getMode()],
            $event->getTarget()
        );

        $event->setResultImage($image->getUrl($this->rootDir));
    }

    /**
     * Handle a get html for image event.
     *
     * @param GenerateHtmlEvent $event The event.
     *
     * @return void
     */
    public function handleGenerateHtml(GenerateHtmlEvent $event): void
    {
        /**
         * @var Image $imageAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
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
