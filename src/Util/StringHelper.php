<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2017 The Contao Community Alliance
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings
 * @subpackage Util
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @author     Sven Baumann <baumann.sv@gmail.com>
 * @copyright  2017 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Util;

use Contao\StringUtil;

/**
 * This class is a simple helper class around the Contao String(Utils) class.
 *
 * @deprecated Deprecated since Contao 4.0, to be removed in Contao 5.0.
 *             Use the contao string util instead.
 */
class StringHelper extends StringUtil
{
    /**
     * Highlight a phrase within a string.
     *
     * @param string $strString     The string.
     *
     * @param string $strPhrase     The phrase to highlight.
     *
     * @param string $strOpeningTag The opening tag (defaults to <strong>).
     *
     * @param string $strClosingTag The closing tag (defaults to </strong>).
     *
     * @return string The highlighted string
     *
     * @deprecated Deprecated since Contao 4.0, to be removed in Contao 5.0.
     *             Use the contao string util highlight method instead.
     *             Note the parameters strOpeningTag, strClosingTag. These have a different default value
     */
    public static function highlight($strString, $strPhrase, $strOpeningTag = '<strong>', $strClosingTag = '</strong>')
    {
        return StringUtil::highlight($strString, $strPhrase, $strOpeningTag, $strClosingTag);
    }
}
