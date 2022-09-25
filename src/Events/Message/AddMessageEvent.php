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

declare(strict_types=1);

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Message;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when a message should be added to the session.
 */
class AddMessageEvent extends ContaoApiEvent
{
    public const TYPE_ERROR = 'error';
    public const TYPE_CONFIRM = 'confirm';
    public const TYPE_NEW = 'new';
    public const TYPE_INFO = 'info';
    public const TYPE_RAW = 'raw';

    /**
     * Create an event to add an error message.
     *
     * @param string $content The message.
     *
     * @return AddMessageEvent
     *
     * @psalm-suppress UnsafeInstantiation
     * @psalm-suppress MixedArgument
     */
    public static function createError(string $content): self
    {
        return new static($content, static::TYPE_ERROR);
    }

    /**
     * Create an event to add a confirm message.
     *
     * @param string $content The message.
     *
     * @return AddMessageEvent
     *
     * @psalm-suppress UnsafeInstantiation
     * @psalm-suppress MixedArgument
     */
    public static function createConfirm(string $content): self
    {
        return new static($content, static::TYPE_CONFIRM);
    }

    /**
     * Create an event to add a new message.
     *
     * @param string $content The message.
     *
     * @return AddMessageEvent
     *
     * @psalm-suppress UnsafeInstantiation
     * @psalm-suppress MixedArgument
     */
    public static function createNew(string $content): self
    {
        return new static($content, static::TYPE_NEW);
    }

    /**
     * Create an event to add an info message.
     *
     * @param string $content The message.
     *
     * @return AddMessageEvent
     *
     * @psalm-suppress UnsafeInstantiation
     * @psalm-suppress MixedArgument
     */
    public static function createInfo(string $content): self
    {
        return new static($content, static::TYPE_INFO);
    }

    /**
     * Create an event to add a raw message.
     *
     * @param string $content The message.
     *
     * @return AddMessageEvent
     *
     * @psalm-suppress UnsafeInstantiation
     * @psalm-suppress MixedArgument
     */
    public static function createRaw(string $content): self
    {
        return new static($content);
    }

    /**
     * The message text.
     *
     * @var string
     */
    protected string $content;

    /**
     * The message type.
     *
     * @var string
     */
    protected string $type;

    /**
     * Create a new instance.
     *
     * @param string $content The message text.
     *
     * @param string $type    The message type.
     */
    public function __construct(string $content, string $type = self::TYPE_RAW)
    {
        $this->content = $content;
        $this->type    = $type;
    }

    /**
     * Retrieve the message text.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Retrieve the message type.
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
