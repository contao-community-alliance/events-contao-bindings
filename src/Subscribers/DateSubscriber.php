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
use Contao\Date;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Date\ParseDateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the System class in Contao.
 */
class DateSubscriber implements EventSubscriberInterface
{
    /**
     * The contao framework.
     *
     * @var ContaoFramework
     */
    protected ContaoFramework $framework;

    /**
     * DateSubscriber constructor.
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
            ContaoEvents::DATE_PARSE => 'handleParseDate',
        ];
    }

    /**
     * Handle the date parsing.
     *
     * @param ParseDateEvent $event The event.
     *
     * @return void
     */
    public function handleParseDate(ParseDateEvent $event): void
    {
        if ($event->getResult() === null) {
            /**
             * @var Date $dateAdapter
             * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
             */
            $dateAdapter = $this->framework->getAdapter(Date::class);
            $event->setResult($dateAdapter->parse($event->getFormat() ?? '', $event->getTimestamp()));
        }
    }
}
