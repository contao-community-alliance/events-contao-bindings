<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage System
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\System;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when a log entry shall be added to the database.
 */
class LogEvent extends ContaoApiEvent
{
    /**
     * The category name.
     *
     * @var string
     */
    protected $category;

    /**
     * The function name.
     *
     * @var string
     */
    protected $function;

    /**
     * The log message.
     *
     * @var string
     */
    protected $text;

    /**
     * Create a new instance.
     *
     * @param string $text     The log message.
     *
     * @param string $function The function name.
     *
     * @param string $category The category name.
     */
    public function __construct($text, $function, $category)
    {
        $this->text     = $text;
        $this->function = $function;
        $this->category = $category;
    }

    /**
     * Get the category name.
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Get the function name.
     *
     * @return string
     */
    public function getFunction()
    {
        return $this->function;
    }

    /**
     * Get the log message.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}
