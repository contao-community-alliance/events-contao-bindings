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
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Contao\Bindings\Subscribers;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Monolog\ContaoContext;
use Contao\System;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\GetReferrerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LogEvent;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the System class in Contao.
 */
class SystemSubscriber implements EventSubscriberInterface
{
    /**
     * The contao framework.
     */
    protected ContaoFramework $framework;

    /**
     * The logger service.
     *
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * SystemSubscriber constructor.
     *
     * @param ContaoFramework $framework The contao framework.
     * @param LoggerInterface $logger    The logger service.
     */
    public function __construct(ContaoFramework $framework, LoggerInterface $logger)
    {
        $this->framework = $framework;
        $this->logger    = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ContaoEvents::SYSTEM_GET_REFERRER       => 'handleGetReferer',
            ContaoEvents::SYSTEM_LOG                => 'handleLog',
            ContaoEvents::SYSTEM_LOAD_LANGUAGE_FILE => 'handleLoadLanguageFile',
        ];
    }

    /**
     * Retrieve the current referrer url.
     *
     * @param GetReferrerEvent $event The event.
     */
    public function handleGetReferer(GetReferrerEvent $event): void
    {
        /**
         * @var System $systemAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $systemAdapter = $this->framework->getAdapter(System::class);

        $event->setReferrerUrl($systemAdapter->getReferer($event->isEncodeAmpersands(), $event->getTableName()));
    }

    /**
     * Handle a log event.
     *
     * @param LogEvent $event The event.
     */
    public function handleLog(LogEvent $event): void
    {
        $level = /*TL_ERROR*/'ERROR' === $event->getCategory() ? LogLevel::ERROR : LogLevel::INFO;
        $this->logger->log(
            $level,
            $event->getText(),
            ['contao' => new ContaoContext($event->getFunction(), $event->getCategory())]
        );
    }

    /**
     * Handle a load language file event.
     *
     * @param LoadLanguageFileEvent $event The event.
     */
    public function handleLoadLanguageFile(LoadLanguageFileEvent $event): void
    {
        /**
         * @var System $systemAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $systemAdapter = $this->framework->getAdapter(System::class);

        /** @psalm-suppress InvalidScalarArgument - Contao 4.9 incorrectly denotes the second arg as bool. */
        $systemAdapter->loadLanguageFile($event->getFileName(), $event->getLanguage(), $event->isCacheIgnored());
    }
}
