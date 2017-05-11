<?php
/*!
* Hybridauth
* https://hybridauth.github.io | https://github.com/hybridauth/hybridauth
*  (c) 2017 Hybridauth authors | https://hybridauth.github.io/license.html
*/

namespace Hybridauth\Data;

/**
 * Parser
 *
 * This class is used to parse plain text into objects. It's used by hybriauth adapters to converts
 * providers api responses to a more 'manageable' format.
 */
final class Parser
{
    /**
    * Decodes a string into an object.
    *
    * This method will first attempt to parse data as a JSON string (since most providers use this format)
    * then parse_str.
    *
    * @param string $raw
    *
    * @return mixed
    */
    public function parse($raw = null)
    {
        $data = $this->parseJson($raw);

        if (! $data) {
            $data = $this->parseQueryString($raw);
        }

        return $data;
    }

    /**
    * Decodes a JSON string
    *
    * @param $result
    *
    * @return mixed
    */
    public function parseJson($result)
    {
        return json_decode($result);
    }

    /**
    * Parses a string into variables
    *
    * @param $result
    *
    * @return \StdClass
    */
    public function parseQueryString($result)
    {
        parse_str($result, $output);

        if (! is_array($output)) {
            return $result;
        }

        $result = new \StdClass();

        foreach ($output as $k => $v) {
            $result->$k = $v;
        }

        return $result;
    }

    public function parseBirthday($birthday, $separator = '/', $monthFirst = true)
    {
        if (empty($birthday)) {
            return [null, null, null];
        }

        $components = explode($separator, $birthday);

        $count = count($components);

        $components += [null, null, null];

        if ($count === 1) {
            return $components;
        }

        if ($monthFirst && $count >= 2) {
            list($components[0], $components[1]) = [$components[1], $components[0]];
        }

        return array_reverse($components);
    }
}
