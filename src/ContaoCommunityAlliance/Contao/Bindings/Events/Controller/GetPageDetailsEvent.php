<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage Controller
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Tristan Lins <tristan.lins@bit3.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

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
    protected $pageId;

    /**
     * The page details.
     *
     * @var array
     */
    protected $pageDetails;

    /**
     * Create a new instance.
     *
     * @param int $pageId The id of the page.
     */
    public function __construct($pageId)
    {
        $this->pageId = (int) $pageId;
    }

    /**
     * Retrieve the id of the article.
     *
     * @return int
     */
    public function getPageId()
    {
        return $this->pageId;
    }

    /**
     * Set the page details.
     *
     * @param array $pageDetails The page details array.
     *
     * @return GetArticleEvent
     */
    public function setPageDetails(array $pageDetails)
    {
        $this->pageDetails = $pageDetails;

        return $this;
    }

    /**
     * Retrieve the page details.
     *
     * @return array
     */
    public function getPageDetails()
    {
        return $this->pageDetails;
    }
}
