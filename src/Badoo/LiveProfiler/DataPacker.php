<?php

/**
 * Class to prepare profiler data to dave in db
 * @maintainer Timur Shagiakhmetov <timur.shagiakhmetov@corp.badoo.com>
 */

namespace Badoo\LiveProfiler;

class DataPacker implements DataPackerInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function pack(array $data)
    {
        return json_encode($this->utf8ize($data));
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
                $mixed[$key] = $this->utf8ize($value);
            }
        } elseif (is_string($mixed)) {
            return mb_convert_encoding($mixed, "UTF-8", "UTF-8");
        }

        return $mixed;
    }
}
