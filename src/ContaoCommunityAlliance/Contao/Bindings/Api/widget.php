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

namespace ContaoCommunityAlliance\Contao\Bindings\Api\Widget;

use ContaoCommunityAlliance\Contao\Bindings\Api;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Widget\GetAttributesFromDcaEvent;

/**
 * Retrieve attributes for a certain widget from an dc array.
 *
 * @param array          $fieldConfiguration The field configuration from the dca.
 *
 * @param string         $widgetName         The name of the widget.
 *
 * @param mixed          $value              The value to use in the widget (optional).
 *
 * @param string         $widgetId           The widget id (optional).
 *
 * @param string         $table              The table name (optional).
 *
 * @param \DataContainer $dc                 The data container in use.
 *
 * @return array
 */
function getAttributesFromDca($fieldConfiguration, $widgetName, $value = null, $widgetId = '', $table = '', $dc = null)
{
    $event = new GetAttributesFromDcaEvent($fieldConfiguration, $widgetName, $value, $widgetId, $table, $dc);

    Api\dispatch(ContaoEvents::WIDGET_GET_ATTRIBUTES_FROM_DCA, $event);

    return $event->getResult();
}
