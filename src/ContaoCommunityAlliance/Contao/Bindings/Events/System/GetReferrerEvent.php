<?php
/**
 * The Contao Community Alliance events-contao-bindings library allows easy use of various Contao classes.
 *
 * PHP version 5
 * @package    ContaoCommunityAlliance\Contao\Bindings\Events
 * @subpackage System
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  The Contao Community Alliance
 * @license    LGPL.
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Events\System;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when the current referring url shall get determined.
 */
class GetReferrerEvent
	extends ContaoApiEvent
{
	/**
	 * If true, ampersands will be encoded.
	 *
	 * @var bool
	 */
	protected $encodeAmpersands;

	/**
	 * An optional table name.
	 *
	 * @var null|string
	 */
	protected $tableName;

	/**
	 * The referrer url.
	 *
	 * @var null|string
	 */
	protected $referrerUrl;

	/**
	 * Return the current referer URL and optionally encode ampersands.
	 *
	 * @param boolean $encodeAmpersands If true, ampersands will be encoded.
	 *
	 * @param string  $tableName        An optional table name.
	 */
	public function __construct($encodeAmpersands = false, $tableName = null)
	{
		$this->encodeAmpersands = $encodeAmpersands;
		$this->tableName        = $tableName;
	}

	/**
	 * Get the flag if ampersands shall be encoded.
	 *
	 * @return boolean
	 */
	public function isEncodeAmpersands()
	{
		return $this->encodeAmpersands;
	}

	/**
	 * Get the table name.
	 *
	 * @return null|string
	 */
	public function getTableName()
	{
		return $this->tableName;
	}

	/**
	 * Set the referrerUrl.
	 *
	 * @param string $referrerUrl The referrer url.
	 *
	 * @return GetReferrerEvent
	 */
	public function setReferrerUrl($referrerUrl)
	{
		$this->referrerUrl = $referrerUrl;

		return $this;
	}

	/**
	 * Get the referrer url.
	 *
	 * @return string|null
	 */
	public function getReferrerUrl()
	{
		return $this->referrerUrl;
	}
}
