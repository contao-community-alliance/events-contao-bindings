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
 * This Event is emitted when the details of a page must be collected.
 */
class GetPageDetailsEvent extends ContaoApiEvent
{
    /**
     * Id of the page.
     *
     * @var int
     */
    protected int $pageId;

    /**
     * The page details.
     *
     * @var array
     */
    protected array $pageDetails;

    /**
     * Create a new instance.
     *
     * @param int $pageId The id of the page.
     */
    public function __construct(int $pageId)
    {
        $this->pageId      = $pageId;
        $this->pageDetails = [];
    }

    /**
     * Retrieve the id of the article.
     *
     * @return int
     */
    public function getPageId(): int
    {
        return $this->pageId;
    }

    /**
     * Set the page details.
     *
     * @param array $pageDetails The page details array.
     *
     * @return GetPageDetailsEvent
     */
    public function setPageDetails(array $pageDetails): self
    {
        $this->pageDetails = $pageDetails;

        return $this;
    }

    /**
     * Retrieve the page details.
     *
     * @return array
     */
    public function getPageDetails(): array
    {
        return $this->pageDetails;
    }
}
