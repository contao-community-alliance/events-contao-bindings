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
 * @subpackage System
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

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
    protected string $category;

    /**
     * The function name.
     *
     * @var string
     */
    protected string $function;

    /**
     * The log message.
     *
     * @var string
     */
    protected string $text;

    /**
     * Create a new instance.
     *
     * @param string $text     The log message.
     *
     * @param string $function The function name.
     *
     * @param string $category The category name.
     */
    public function __construct(string $text, string $function, string $category)
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
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * Get the function name.
     *
     * @return string
     */
    public function getFunction(): string
    {
        return $this->function;
    }

    /**
     * Get the log message.
     *
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}
