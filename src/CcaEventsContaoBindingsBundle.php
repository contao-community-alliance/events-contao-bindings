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
 * @package    ContaoCommunityAlliance\Contao\Bindings
 * @subpackage System
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  201/ The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings;

use ContaoCommunityAlliance\Contao\EventDispatcher\Configuration\ResourceLocator;
use ContaoCommunityAlliance\Contao\EventDispatcher\DependencyInjection\Compiler\AddConfiguratorPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * This is the bundle for binding contao events.
 */
class CcaEventsContaoBindingsBundle extends Bundle
{
}
