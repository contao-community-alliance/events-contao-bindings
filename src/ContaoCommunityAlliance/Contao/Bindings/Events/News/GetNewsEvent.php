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

namespace ContaoCommunityAlliance\Contao\Bindings\Events\News;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when a news should be rendered.
 */
class GetNewsEvent extends ContaoApiEvent
{
    /**
     * The news ID.
     *
     * @var int
     */
    protected $newsId;

    /**
     * The template name.
     *
     * @var string
     */
    protected $template = 'news_full';

    /**
     * The rendered news html.
     *
     * @var string
     */
    protected $newsHtml;

    /**
     * Create the event.
     *
     * @param int    $newsId   The news ID.
     * @param string $template The template name.
     */
    public function __construct($newsId, $template = 'news_full')
    {
        $this->newsId   = (int) $newsId;
        $this->template = (string) $template;
    }

    /**
     * Return the news ID.
     *
     * @return int
     */
    public function getNewsId()
    {
        return $this->newsId;
    }

    /**
     * Return the template name.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set the rendered news html.
     *
     * @param string $newsHtml The rendered html.
     *
     * @return GetNewsEvent
     */
    public function setNewsHtml($newsHtml)
    {
        $this->newsHtml = $newsHtml;

        return $this;
    }

    /**
     * Return the rendered news html.
     *
     * @return string
     */
    public function getNewsHtml()
    {
        return $this->newsHtml;
    }
}
