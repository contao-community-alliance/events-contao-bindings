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

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when a content element should be rendered.
 */
class GetContentElementEvent extends ContaoApiEvent
{
    /**
     * The id of the content element.
     *
     * @var int
     */
    protected int $contentElementId;

    /**
     * The column for the content element.
     *
     * @var string
     */
    protected string $column = 'main';

    /**
     * The html code for the content element.
     *
     * @var string|null
     */
    protected ?string $contentElementHtml = null;

    /**
     * Create a new instance.
     *
     * @param int    $contentElementId The id of the content element.
     *
     * @param string $column           The column for the content element.
     */
    public function __construct(int $contentElementId, string $column = 'main')
    {
        $this->contentElementId = $contentElementId;
        $this->column           = $column;
    }

    /**
     * Retrieve the id of the content element.
     *
     * @return int
     */
    public function getContentElementId(): int
    {
        return $this->contentElementId;
    }

    /**
     * Retrieve the column for the content element.
     *
     * @return string
     */
    public function getColumn(): string
    {
        return $this->column;
    }

    /**
     * Set the html code for the content element.
     *
     * @param string $contentElement The html code.
     *
     * @return GetContentElementEvent
     */
    public function setContentElementHtml(string $contentElement): self
    {
        $this->contentElementHtml = $contentElement;

        return $this;
    }

    /**
     * Retrieve the html code for the content element.
     *
     * @return string|null
     */
    public function getContentElementHtml(): ?string
    {
        return $this->contentElementHtml;
    }
}
