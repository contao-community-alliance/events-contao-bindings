<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage Controller
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\Controller;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted to add an enclosure to a template.
 */
class AddEnclosureToTemplateEvent
	extends ContaoApiEvent
{
	/**
	 * @var array
	 */
	protected $enclosureData;

	/**
	 * @var \Template|object
	 */
	protected $template;

	/**
	 * @var string|null
	 */
	protected $key = null;

	function __construct($imageData, $template, $key = 'enclosure')
	{
		$this->enclosureData = $imageData;
		$this->template      = $template;
		$this->key           = (string) $key;
	}

	/**
	 * @return array
	 */
	public function getEnclosureData()
	{
		return $this->enclosureData;
	}

	/**
	 * @return \Template|object
	 */
	public function getTemplate()
	{
		return $this->template;
	}

	/**
	 * @return null|string
	 */
	public function getKey()
	{
		return $this->key;
	}
}
