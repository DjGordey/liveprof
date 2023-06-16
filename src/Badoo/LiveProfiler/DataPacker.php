<?php

/**
 * Class to prepare profiler data to dave in db
 * @maintainer Timur Shagiakhmetov <timur.shagiakhmetov@corp.badoo.com>
 */

namespace Badoo\LiveProfiler;

use JsonException;

class DataPacker implements DataPackerInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function pack(array $data)
    {
        $decode = $this->utf8ize($data);

        try {
            $result = json_encode($decode, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $tmp = $e;

            return '';
        }

        return $result;
    }

    /**
     * @param string $data
     * @return array
     */
    public function unpack($data)
    {
        return json_decode($data, true);
    }

    /* Use it for json_encode some corrupt UTF-8 chars
     * useful for = malformed utf-8 characters possibly incorrectly encoded by json_encode
     */
    private function utf8ize($mixed)
    {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                unset($mixed[$key]);
                $mixed[$this->utf8ize($key)] = $this->utf8ize($value);
            }
        } elseif (is_string($mixed)) {
            return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
        }

        return $mixed;
    }
}
