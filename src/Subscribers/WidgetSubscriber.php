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
 * @author     Ingolf Steinhardt <info@e-spin.de>
 * @copyright  2014-2024 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Contao\Bindings\Subscribers;

use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Widget;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Widget\GetAttributesFromDcaEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscriber for the Widget class in Contao.
 */
class WidgetSubscriber implements EventSubscriberInterface
{
    /**
     * The contao framework.
     *
     * @var ContaoFramework
     */
    protected ContaoFramework $framework;

    /**
     * WidgetSubscriber constructor.
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
            ContaoEvents::WIDGET_GET_ATTRIBUTES_FROM_DCA => 'handleGetAttributesFromDca'
        ];
    }

    /**
     * Handle the widget preparation.
     *
     * @param GetAttributesFromDcaEvent $event The event.
     */
    public function handleGetAttributesFromDca(GetAttributesFromDcaEvent $event): void
    {
        /**
         * @var Widget $widgetAdapter
         * @psalm-suppress InternalMethod - getAdapter is the official way and NOT internal.
         */
        $widgetAdapter = $this->framework->getAdapter(Widget::class);
        $result = $widgetAdapter->getAttributesFromDca(
            $event->getFieldConfiguration(),
            $event->getWidgetName(),
            $event->getValue(),
            $event->getWidgetId(),
            $event->getTable(),
            $event->getDataContainer()
        );

        // Bugfix: Contao does not validate for label array when determining the description.
        if (
            strlen((string) $result['description']) === 1
            && !\is_array($event->getFieldConfiguration()['label'] ?? null)
        ) {
            $result['description'] = '';
        }

        $event->setResult($result);
    }
}
