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

use Contao\System;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\GetReferrerEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LoadLanguageFileEvent;
use ContaoCommunityAlliance\Contao\Bindings\Events\System\LogEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the System class in Contao.
 */
class SystemSubscriber implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
    public static function getSubscribedEvents()
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
     *
     * @return void
     */
    public function handleGetReferer(GetReferrerEvent $event)
    {
        $event->setReferrerUrl(System::getReferer($event->isEncodeAmpersands(), $event->getTableName()));
    }

    /**
     * Handle a log event.
     *
     * @param LogEvent $event The event.
     *
     * @return void
     */
    public function handleLog(LogEvent $event)
    {
        System::log($event->getText(), $event->getFunction(), $event->getCategory());
    }

    /**
     * Handle a load language file event.
     *
     * @param LoadLanguageFileEvent $event The event.
     *
     * @return void
     */
    public function handleLoadLanguageFile(LoadLanguageFileEvent $event)
    {
        System::loadLanguageFile($event->getFileName(), $event->getLanguage(), $event->isCacheIgnored());
    }
}
