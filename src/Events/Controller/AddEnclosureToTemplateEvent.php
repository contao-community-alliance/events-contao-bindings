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

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Controller;

use Contao\Template;
use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted to add an enclosure to a template.
 */
class AddEnclosureToTemplateEvent extends ContaoApiEvent
{
    /**
     * The enclosure data.
     *
     * @var array
     */
    protected $enclosureData;

    /**
     * The template object.
     *
     * @var Template|object
     */
    protected $template;

    /**
     * The key to use in the template.
     *
     * @var string|null
     */
    protected $key = null;

    /**
     * Create a new instance.
     *
     * @param array           $imageData The enclosure data.
     *
     * @param Template|object $template  The template object.
     *
     * @param string          $key       The key to use in the template.
     */
    public function __construct($imageData, $template, $key = 'enclosure')
    {
        $this->enclosureData = $imageData;
        $this->template      = $template;
        $this->key           = (string) $key;
    }

    /**
     * Retrieve the enclosure data.
     *
     * @return array
     */
    public function getEnclosureData()
    {
        return $this->enclosureData;
    }

    /**
     * Retrieve the template object.
     *
     * @return Template|object
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Retrieve the key to use in the template.
     *
     * @return null|string
     */
    public function getKey()
    {
        return $this->key;
    }
}
