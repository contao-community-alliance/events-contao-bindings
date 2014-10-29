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

namespace ContaoCommunityAlliance\Contao\Bindings\Api\Message;

use ContaoCommunityAlliance\Contao\Bindings\Api;
use ContaoCommunityAlliance\Contao\Bindings\ContaoEvents;
use ContaoCommunityAlliance\Contao\Bindings\Events\Message\AddMessageEvent;

/**
 * Add a message to the session.
 *
 * @param string $content The message text.
 * @param string $type    The message type.
 *
 * @return void
 */
function addMessage($content, $type)
{
    $event = new AddMessageEvent($content, $type);

    Api\dispatch(ContaoEvents::IMAGE_RESIZE, $event);
}

/**
 * Create an error message.
 *
 * @param string $content The message text.
 *
 * @return void
 */
function addError($content)
{
    $event = AddMessageEvent::createError($content);

    Api\dispatch(ContaoEvents::MESSAGE_ADD, $event);
}

/**
 * Create an confirm message.
 *
 * @param string $content The message text.
 *
 * @return void
 */
function addConfirm($content)
{
    $event = AddMessageEvent::createConfirm($content);

    Api\dispatch(ContaoEvents::MESSAGE_ADD, $event);
}

/**
 * Create an new message.
 *
 * @param string $content The message text.
 *
 * @return void
 */
function addNew($content)
{
    $event = AddMessageEvent::createNew($content);

    Api\dispatch(ContaoEvents::MESSAGE_ADD, $event);
}

/**
 * Create an new message.
 *
 * @param string $content The message text.
 *
 * @return void
 */
function addInfo($content)
{
    $event = AddMessageEvent::createInfo($content);

    Api\dispatch(ContaoEvents::MESSAGE_ADD, $event);
}

/**
 * Create an new message.
 *
 * @param string $content The message text.
 *
 * @return void
 */
function addRaw($content)
{
    $event = AddMessageEvent::createRaw($content);

    Api\dispatch(ContaoEvents::MESSAGE_ADD, $event);
}
