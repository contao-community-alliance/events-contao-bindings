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
 * This Event collect a template group.
 */
class GetTemplateGroupEvent
	extends ContaoApiEvent
{
	/**
	 * The template prefix.
	 *
	 * @var string
	 */
	protected $prefix;

	/**
	 * The list of matching templates.
	 *
	 * @var \ArrayObject
	 */
	protected $templates;

	/**
	 * Create a new instance.
	 *
	 * @param string $prefix The prefix for the matching templates.
	 */
	public function __construct($prefix)
	{
		$this->prefix    = (string)$prefix;
		$this->templates = new \ArrayObject();
	}

	/**
	 * Retrieve the prefix for the templates.
	 *
	 * @return string
	 */
	public function getPrefix()
	{
		return $this->prefix;
	}

	/**
	 * Retrieve the array object containing the template list.
	 *
	 * @return \ArrayObject
	 */
	public function getTemplates()
	{
		return $this->templates;
	}
}
