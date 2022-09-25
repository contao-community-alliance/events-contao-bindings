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
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @author     David Molineus <david.molineus@netzmacht.de>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Controller;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when the client shall append some value to the current url.
 */
class AddToUrlEvent extends ContaoApiEvent
{
    /**
     * The suffix to add.
     *
     * @var string
     */
    protected string $suffix;

    /**
     * The resulting URL.
     *
     * @var string
     */
    protected string $newUrl;

    /**
     * Create a new instance.
     *
     * @param string $suffix The string to add to the URL.
     */
    public function __construct(string $suffix)
    {
        $this->suffix = $suffix;
        $this->newUrl = '';
    }

    /**
     * Retrieve the suffix.
     *
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->suffix;
    }

    /**
     * Set the resulting URL.
     *
     * @param string $newUrl The new URL.
     *
     * @return AddToUrlEvent
     */
    public function setUrl(string $newUrl): self
    {
        $this->newUrl = $newUrl;

        return $this;
    }

    /**
     * Retrieve the new URL.
     *
     * @param bool $encode Determine if return the encoded url.
     *
     * @return string
     */
    public function getUrl(bool $encode = false): string
    {
        return $encode ? $this->newUrl : rawurldecode($this->newUrl);
    }
}
