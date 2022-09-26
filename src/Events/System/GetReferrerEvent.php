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
 * @subpackage System
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2018 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

declare(strict_types=1);

namespace ContaoCommunityAlliance\Contao\Bindings\Events\System;

use ContaoCommunityAlliance\Contao\Bindings\Events\ContaoApiEvent;

/**
 * This Event is emitted when the current referring url shall get determined.
 */
class GetReferrerEvent extends ContaoApiEvent
{
    /**
     * If true, ampersands will be encoded.
     *
     * @var bool
     */
    protected bool $encodeAmpersands;

    /**
     * An optional table name.
     *
     * @var null|string
     */
    protected ?string $tableName;

    /**
     * The referrer url.
     *
     * @var string
     */
    protected string $referrerUrl;

    /**
     * Return the current referer URL and optionally encode ampersands.
     *
     * @param boolean     $encodeAmpersands If true, ampersands will be encoded.
     *
     * @param string|null $tableName        An optional table name.
     */
    public function __construct(bool $encodeAmpersands = false, string $tableName = null)
    {
        $this->encodeAmpersands = $encodeAmpersands;
        $this->tableName        = $tableName;
        $this->referrerUrl      = '';
    }

    /**
     * Get the flag if ampersands shall be encoded.
     *
     * @return boolean
     */
    public function isEncodeAmpersands(): bool
    {
        return $this->encodeAmpersands;
    }

    /**
     * Get the table name.
     *
     * @return null|string
     */
    public function getTableName(): ?string
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
    public function setReferrerUrl(string $referrerUrl): self
    {
        $this->referrerUrl = $referrerUrl;

        return $this;
    }

    /**
     * Get the referrer url.
     *
     * @param bool $encoded Determine if return the encoded url.
     *
     * @return string
     */
    public function getReferrerUrl(bool $encoded = false): string
    {
        return $encoded ? $this->referrerUrl : rawurldecode($this->referrerUrl);
    }
}
