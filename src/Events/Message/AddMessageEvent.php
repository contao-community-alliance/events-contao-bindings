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
 * @subpackage Message
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Message;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when a message should be added to the session.
 */
class AddMessageEvent extends ContaoApiEvent
{
    const TYPE_ERROR = 'error';

    const TYPE_CONFIRM = 'confirm';

    const TYPE_NEW = 'new';

    const TYPE_INFO = 'info';

    const TYPE_RAW = 'raw';

    /**
     * Create an event to add an error message.
     *
     * @param string $content The message.
     *
     * @return AddMessageEvent
     */
    public static function createError($content)
    {
        return new static($content, static::TYPE_ERROR);
    }

    /**
     * Create an event to add a confirm message.
     *
     * @param string $content The message.
     *
     * @return AddMessageEvent
     */
    public static function createConfirm($content)
    {
        return new static($content, static::TYPE_CONFIRM);
    }

    /**
     * Create an event to add a new message.
     *
     * @param string $content The message.
     *
     * @return AddMessageEvent
     */
    public static function createNew($content)
    {
        return new static($content, static::TYPE_NEW);
    }

    /**
     * Create an event to add an info message.
     *
     * @param string $content The message.
     *
     * @return AddMessageEvent
     */
    public static function createInfo($content)
    {
        return new static($content, static::TYPE_INFO);
    }

    /**
     * Create an event to add a raw message.
     *
     * @param string $content The message.
     *
     * @return AddMessageEvent
     */
    public static function createRaw($content)
    {
        return new static($content);
    }

    /**
     * The message text.
     *
     * @var string
     */
    protected $content;

    /**
     * The message type.
     *
     * @var string
     */
    protected $type;

    /**
     * Create a new instance.
     *
     * @param string $content The message text.
     *
     * @param string $type    The message type.
     */
    public function __construct($content, $type = self::TYPE_RAW)
    {
        $this->content = $content;
        $this->type    = $type;
    }

    /**
     * Retrieve the message text.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Retrieve the message type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
