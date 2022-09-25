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
 * @package    ContaoCommunityAlliance\Contao\Bindings
 * @subpackage Subscribers
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Contao\Bindings\Subscribers;

use Contao\Backend;
use Contao\CoreBundle\Framework\ContaoFramework;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\AddToUrlEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\Backend\GetThemeEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the Backend class in Contao.
 */
class BackendSubscriber implements EventSubscriberInterface
{
    /**
     * The contao framework.
     *
     * @var ContaoFramework
     */
    protected ContaoFramework $framework;

    /**
     * BackendSubscriber constructor.
     *
     * @param ContaoFramework $framework The contao framework.
     */
    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContaoEvents::BACKEND_ADD_TO_URL => 'handleAddToUrl',
            ContaoEvents::BACKEND_GET_THEME  => 'handleGetTheme',
        ];
    }

    /**
     * Add some suffix to the current URL.
     *
     * @param AddToUrlEvent $event The event.
     *
     * @return void
     */
    public function handleAddToUrl(AddToUrlEvent $event): void
    {
        /**
         * @var Backend $backendAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $backendAdapter = $this->framework->getAdapter(Backend::class);

        $event->setUrl($backendAdapter->addToUrl($event->getSuffix()));
    }

    /**
     * Add some suffix to the current URL.
     *
     * @param GetThemeEvent $event The event.
     *
     * @return void
     */
    public function handleGetTheme(GetThemeEvent $event): void
    {
        /**
         * @var Backend $backendAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $backendAdapter = $this->framework->getAdapter(Backend::class);

        $event->setTheme($backendAdapter->getTheme());
    }
}
