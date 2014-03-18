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
	 * The enclosure data.
	 *
	 * @var array
	 */
	protected $enclosureData;

	/**
	 * The template object.
	 *
	 * @var \Template|object
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
	 * @param array            $imageData The enclosure data.
	 *
	 * @param \Template|object $template  The template object.
	 *
	 * @param string           $key       The key to use in the template.
	 */
	public function __construct($imageData, $template, $key = 'enclosure')
	{
		$this->enclosureData = $imageData;
		$this->template      = $template;
		$this->key           = (string)$key;
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
	 * @return \Template|object
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
