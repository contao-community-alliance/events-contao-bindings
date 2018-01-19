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
 * @subpackage Date
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2017 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Date;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted to render a timestamp into a string representation.
 */
class ParseDateEvent extends ContaoApiEvent
{
    /**
     * The date format.
     *
     * @var string
     */
    protected $format;

    /**
     * The timestamp.
     *
     * @var int
     */
    protected $timestamp;

    /**
     * The parsed date.
     *
     * @var string
     */
    protected $result;

    /**
     * Create a new instance.
     *
     * @param int    $timestamp The timestamp.
     *
     * @param string $format    The format string.
     */
    public function __construct($timestamp = null, $format = null)
    {
        $this->timestamp = $timestamp;
        $this->format    = $format;
    }

    /**
     * Retrieve the format string.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set the format string.
     *
     * @param string $format The format string.
     *
     * @return ParseDateEvent
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Retrieve the timestamp.
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set the timestamp.
     *
     * @param int $timestamp The timestamp.
     *
     * @return ParseDateEvent
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Retrieve the parsed date.
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set the parsed date.
     *
     * @param string $result The parsed date.
     *
     * @return ParseDateEvent
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }
}
