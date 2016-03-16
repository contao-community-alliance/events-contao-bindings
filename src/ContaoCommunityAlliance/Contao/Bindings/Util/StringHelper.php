<?php

/**
 * This file is part of contao-community-alliance/events-contao-bindings
 *
 * (c) 2014-2016 The Contao Community Alliance
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This project is provided in good faith and hope to be usable by anyone.
 *
 * @package    ContaoCommunityAlliance\Contao\Bindings
 * @subpackage Util
 * @author     Christian Schiffler <c.schiffler@cyberspectrum.de>
 * @copyright  2014 The Contao Community Alliance.
 * @license    https://github.com/contao-community-alliance/events-contao-bindings/blob/master/LICENSE LGPL-3.0
 * @filesource
 */

namespace ContaoCommunityAlliance\Contao\Bindings\Util;

use Contao\StringUtil;

/**
 * This class is a simple helper class around the Contao String(Utils) class.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class StringHelper
{
    /**
     * Shorten a string to a given number of characters.
     *
     * The function preserves words, so the result might be a bit shorter or
     * longer than the number of characters given. It strips all tags.
     *
     * @param string  $strString        The string to shorten.
     * @param integer $intNumberOfChars The target number of characters.
     * @param string  $strEllipsis      An optional ellipsis to append to the shortened string.
     *
     * @return string The shortened string
     */
    public static function substr($strString, $intNumberOfChars, $strEllipsis = ' …')
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::substr($strString, $intNumberOfChars, $strEllipsis);
        }
        return \Contao\String::substr($strString, $intNumberOfChars, $strEllipsis);
    }

    /**
     * Shorten a HTML string to a given number of characters.
     *
     * The function preserves words, so the result might be a bit shorter or
     * longer than the number of characters given. It preserves allowed tags.
     *
     * @param string  $strString        The string to shorten.
     * @param integer $intNumberOfChars The target number of characters.
     *
     * @return string The shortened HTML string
     */
    public static function substrHtml($strString, $intNumberOfChars)
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::substrHtml($strString, $intNumberOfChars);
        }
        return \Contao\String::substrHtml($strString, $intNumberOfChars);
    }

    /**
     * Decode all entities.
     *
     * @param string  $strString     The string to decode.
     * @param integer $strQuoteStyle The quote style (defaults to ENT_COMPAT).
     * @param string  $strCharset    An optional charset.
     *
     * @return string The decoded string
     */
    public static function decodeEntities($strString, $strQuoteStyle = ENT_COMPAT, $strCharset = null)
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::decodeEntities($strString, $strQuoteStyle, $strCharset);
        }
        return \Contao\String::decodeEntities($strString, $strQuoteStyle, $strCharset);
    }

    /**
     * Restore basic entities.
     *
     * @param string $strBuffer The string with the tags to be replaced.
     *
     * @return string The string with the original entities
     */
    public static function restoreBasicEntities($strBuffer)
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::restoreBasicEntities($strBuffer);
        }
        return \Contao\String::restoreBasicEntities($strBuffer);
    }

    /**
     * Generate an alias from a string.
     *
     * @param string $strString The string.
     *
     * @return string The alias
     */
    public static function generateAlias($strString)
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::generateAlias($strString);
        }
        return \Contao\String::generateAlias($strString);
    }

    /**
     * Censor a single word or an array of words within a string.
     *
     * @param string $strString  The string to censor.
     *
     * @param mixed  $varWords   A string or array or words to replace.
     *
     * @param string $strReplace An optional replacement string.
     *
     * @return string The cleaned string
     */
    public static function censor($strString, $varWords, $strReplace = '')
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::censor($strString, $varWords, $strReplace);
        }
        return \Contao\String::censor($strString, $varWords, $strReplace);
    }

    /**
     * Encode all e-mail addresses within a string.
     *
     * @param string $strString The string to encode.
     *
     * @return string The encoded string
     */
    public static function encodeEmail($strString)
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::encodeEmail($strString);
        }
        return \Contao\String::encodeEmail($strString);
    }

    /**
     * Split a friendly-name e-address and return name and e-mail as array.
     *
     * @param string $strEmail A friendly-name e-mail address.
     *
     * @return array An array with name and e-mail address
     */
    public static function splitFriendlyEmail($strEmail)
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::splitFriendlyEmail($strEmail);
        }
        return \Contao\String::splitFriendlyEmail($strEmail);
    }

    /**
     * Wrap words after a particular number of characters.
     *
     * @param string  $strString The string to wrap.
     *
     * @param integer $strLength The number of characters to wrap after.
     *
     * @param string  $strBreak  An optional break character.
     *
     * @return string The wrapped string
     */
    public static function wordWrap($strString, $strLength = 75, $strBreak = "\n")
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::wordWrap($strString, $strLength, $strBreak);
        }
        return \Contao\String::wordWrap($strString, $strLength, $strBreak);
    }

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
     */
    public static function highlight($strString, $strPhrase, $strOpeningTag = '<strong>', $strClosingTag = '</strong>')
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::highlight($strString, $strPhrase, $strOpeningTag, $strClosingTag);
        }
        return \Contao\String::highlight($strString, $strPhrase, $strOpeningTag, $strClosingTag);
    }

    /**
     * Split a string of comma separated values.
     *
     * @param string $strString    The string to split.
     *
     * @param string $strDelimiter An optional delimiter.
     *
     * @return array The string chunks
     */
    public static function splitCsv($strString, $strDelimiter = ',')
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::splitCsv($strString, $strDelimiter);
        }
        return \Contao\String::splitCsv($strString, $strDelimiter);
    }

    /**
     * Convert a string to XHTML.
     *
     * @param string $strString The HTML5 string.
     *
     * @return string The XHTML string
     */
    public static function toXhtml($strString)
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::toXhtml($strString);
        }
        return \Contao\String::toXhtml($strString);
    }

    /**
     * Convert a string to HTML5.
     *
     * @param string $strString The XHTML string.
     *
     * @return string The HTML5 string
     */
    public static function toHtml5($strString)
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::toHtml5($strString);
        }
        return \Contao\String::toHtml5($strString);
    }

    /**
     * Parse simple tokens that can be used to personalize newsletters.
     *
     * @param string $strString The string to be parsed.
     *
     * @param array  $arrData   The replacement data.
     *
     * @return string The converted string
     *
     * @throws \Exception If $strString cannot be parsed.
     */
    public static function parseSimpleTokens($strString, $arrData)
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::parseSimpleTokens($strString, $arrData);
        }
        return \Contao\String::parseSimpleTokens($strString, $arrData);
    }

    /**
     * Convert a UUID string to binary data.
     *
     * @param string $uuid The UUID string.
     *
     * @return string The binary data
     */
    public static function uuidToBin($uuid)
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::uuidToBin($uuid);
        }
        return \Contao\String::uuidToBin($uuid);
    }

    /**
     * Get a UUID string from binary data.
     *
     * @param string $data The binary data.
     *
     * @return string The UUID string
     */
    public static function binToUuid($data)
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::binToUuid($data);
        }
        return \Contao\String::binToUuid($data);
    }

    /**
     * Convert file paths inside "src" attributes to insert tags.
     *
     * @param string $data The markup string.
     *
     * @return string The markup with file paths converted to insert tags
     */
    public static function srcToInsertTag($data)
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::srcToInsertTag($data);
        }
        return \Contao\String::srcToInsertTag($data);
    }


    /**
     * Convert insert tags inside "src" attributes to file paths.
     *
     * @param string $data The markup string.
     *
     * @return string The markup with insert tags converted to file paths
     */
    public static function insertTagToSrc($data)
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::insertTagToSrc($data);
        }
        return \Contao\String::insertTagToSrc($data);
    }


    /**
     * Sanitize a file name.
     *
     * @param string $strName The file name.
     *
     * @return string The sanitized file name
     */
    public static function sanitizeFileName($strName)
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::sanitizeFileName($strName);
        }
        return \Contao\String::sanitizeFileName($strName);
    }


    /**
     * Resolve a flagged URL such as assets/js/core.js|static|10184084.
     *
     * @param string $url The URL.
     *
     * @return \stdClass The options object
     */
    public static function resolveFlaggedUrl(&$url)
    {
        if (self::isStringUtilAvailable()) {
            return StringUtil::resolveFlaggedUrl($url);
        }
        return \Contao\String::resolveFlaggedUrl($url);
    }


    /**
     * Check if the StringUtil class is available.
     *
     * @return bool
     */
    private static function isStringUtilAvailable()
    {
        return version_compare(VERSION . '.' . BUILD, '3.5.5', '>=');
    }
}
