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
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage Widget
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

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
     * @var DataContainer|null
     */
    protected ?DataContainer $dataContainer;

    /**
     * The input field configuration.
     *
     * @var array
     */
    protected array $fieldConfiguration;

    /**
     * The resulting widget configuration.
     *
     * @var array
     */
    protected array $result = [];

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
    protected string $table;

    /**
     * The (html) id for the widget.
     *
     * @var string
     */
    protected string $widgetId;

    /**
     * The name of the widget.
     *
     * @var string
     */
    protected string $widgetName;

    /**
     * Create a new instance.
     *
     * @param array              $fieldConfiguration The field configuration from the dca.
     * @param string             $widgetName         The name of the widget.
     * @param mixed              $value              The value to use in the widget (optional).
     * @param string             $widgetId           The widget id (optional).
     * @param string             $table              The table name (optional).
     * @param DataContainer|null $dataContainer      The data container in use.
     */
    public function __construct(
        array $fieldConfiguration,
        string $widgetName,
        $value = null,
        string $widgetId = '',
        string $table = '',
        ?DataContainer $dataContainer = null
    ) {
        $this->fieldConfiguration = $fieldConfiguration;
        $this->widgetName         = $widgetName;
        $this->value              = $value;
        $this->widgetId           = $widgetId;
        $this->table              = $table;
        $this->dataContainer      = $dataContainer;
    }

    /**
     * Retrieve the data container in use.
     *
     * @return DataContainer|null
     */
    public function getDataContainer(): ?DataContainer
    {
        return $this->dataContainer;
    }

    /**
     * Retrieve the field configuration.
     *
     * @return array
     */
    public function getFieldConfiguration(): array
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
    public function setResult(array $result): self
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Retrieve the result.
     *
     * @return array
     */
    public function getResult(): array
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
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Retrieve the widget id.
     *
     * @return string
     */
    public function getWidgetId(): string
    {
        return $this->widgetId;
    }

    /**
     * Retrieve the widget name.
     *
     * @return string
     */
    public function getWidgetName(): string
    {
        return $this->widgetName;
    }
}
