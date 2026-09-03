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
 * @package    ContaoCommunityAlliance\Contao\Bindings
 * @subpackage System
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Contao\Bindings;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * This is the bundle for binding contao events.
 *
 * @psalm-suppress DeprecatedInterface Bundle implements the deprecated BundleInterface under
 *     Symfony 8, but Symfony\Component\DependencyInjection\Kernel\AbstractBundle is not a drop-in
 *     replacement here: its getContainerExtension() does not do the classic reflection-based
 *     lookup of a "<Namespace>\DependencyInjection\<Name>Extension" class that
 *     CcaEventsContaoBindingsExtension relies on, so swapping the base class silently stops that
 *     extension (and all its services) from ever loading.
 */
class CcaEventsContaoBindingsBundle extends Bundle
{
}
