<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage Backend
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Backend;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when the client need the current active backend theme.
 */
class GetThemeEvent
	extends ContaoApiEvent
{
	/**
	 * The theme name.
	 *
	 * @var string
	 */
	protected $theme;

	/**
	 * @param string $theme
	 */
	public function setTheme($theme)
	{
		$this->theme = (string) $theme;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTheme()
	{
		return $this->theme;
	}
}
