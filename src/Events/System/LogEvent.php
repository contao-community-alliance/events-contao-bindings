<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2016 The Contao Community Alliance
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage System
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2014 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
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
