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
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2017 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Subscribers;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
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
     * @var ContaoFrameworkInterface
     */
    protected $framework;

    /**
     * DateSubscriber constructor.
     *
     * @param ContaoFrameworkInterface $framework
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
    public function handleParseDate($event)
    {
        $dateAdapter = $this->framework->getAdapter(Date::class);

        if ($event->getResult() === null) {
            $event->setResult($dateAdapter->parse($event->getFormat(), $event->getTimestamp()));
        }
    }
}
