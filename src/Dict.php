<?php


namespace App\Common;


class Dict
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function get($k, $default = null)
    {
        if (isset($this->data[$k])) {
            return $this->data[$k];
        }
        return $default;
    }
}
