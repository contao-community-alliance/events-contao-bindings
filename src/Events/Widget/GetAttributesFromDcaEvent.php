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
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage Widget
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2017 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Widget;

use Contao\DataContainer;
use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when the attributes for a certain widget shall get retrieved from an dc array.
 */
class GetAttributesFromDcaEvent extends ContaoApiEvent
{
    /**
     * The data container in use.
     *
     * @var DataContainer
     */
    protected $dataContainer;

    /**
     * The input field configuration.
     *
     * @var array
     */
    protected $fieldConfiguration;

    /**
     * The resulting widget configuration.
     *
     * @var array
     */
    protected $result;

    /**
     * The value to use in the widget.
     *
     * @var mixed
     */
    protected $value;

    /**
     * Table name.
     *
     * @var string
     */
    protected $table;

    /**
     * The (html) id for the widget.
     *
     * @var string
     */
    protected $widgetId;

    /**
     * The name of the widget.
     *
     * @var string
     */
    protected $widgetName;

    /**
     * Create a new instance.
     *
     * @param array         $fieldConfiguration The field configuration from the dca.
     *
     * @param string        $widgetName         The name of the widget.
     *
     * @param mixed         $value              The value to use in the widget (optional).
     *
     * @param string        $widgetId           The widget id (optional).
     *
     * @param string        $table              The table name (optional).
     *
     * @param DataContainer $dataContainer      The data container in use.
     */
    public function __construct(
        $fieldConfiguration,
        $widgetName,
        $value = null,
        $widgetId = '',
        $table = '',
        $dataContainer = null
    ) {
        $this->fieldConfiguration = (array) $fieldConfiguration;
        $this->widgetName         = $widgetName;
        $this->value              = $value;
        $this->widgetId           = $widgetId;
        $this->table              = $table;
        $this->dataContainer      = $dataContainer;
    }

    /**
     * Retrieve the data container in use.
     *
     * @return \DataContainer|null
     */
    public function getDataContainer()
    {
        return $this->dataContainer;
    }

    /**
     * Retrieve the field configuration.
     *
     * @return array
     */
    public function getFieldConfiguration()
    {
        return $this->fieldConfiguration;
    }

    /**
     * Set the result.
     *
     * @param array $result The widget attribute array.
     *
     * @return GetAttributesFromDcaEvent
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Retrieve the result.
     *
     * @return array
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Retrieve the value.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Retrieve the table name.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Retrieve the widget id.
     *
     * @return string
     */
    public function getWidgetId()
    {
        return $this->widgetId;
    }

    /**
     * Retrieve the widget name.
     *
     * @return string
     */
    public function getWidgetName()
    {
        return $this->widgetName;
    }
}
