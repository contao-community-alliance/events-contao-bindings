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
 * @subpackage Controller
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Controller;

use ArrayObject;
use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event collect a template group.
 */
class GetTemplateGroupEvent extends ContaoApiEvent
{
    /**
     * The template prefix.
     *
     * @var string
     */
    protected string $prefix;

    /**
     * The list of matching templates.
     *
     * @var ArrayObject<string, string>
     */
    protected ArrayObject $templates;

    /**
     * Create a new instance.
     *
     * @param string $prefix The prefix for the matching templates.
     */
    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
        /** @var ArrayObject<string, string> $templates */
        $templates       = new ArrayObject();
        $this->templates = $templates;
    }

    /**
     * Retrieve the prefix for the templates.
     *
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * Retrieve the array object containing the template list.
     *
     * @return ArrayObject
     */
    public function getTemplates(): ArrayObject
    {
        return $this->templates;
    }
}
