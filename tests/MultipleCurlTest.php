<?php

namespace Test;

use Exp\Brisk\MultipleCurl;
use PHPUnit\Framework\TestCase;

class MultipleCurlTest extends TestCase
{
    public function test()
    {
        $test = new MultipleCurl([
            ['url' => 'https://songcj.com/server_info.php'],
            ['url' => 'https://songcj.com/server_info.php'],
            ['url' => 'https://songcj.com/server_info.php'],
            ['url' => 'https://songcj.com/server_info.php'],
        ], 3);

        $test->get(function ($data) {
            var_dump(json_encode($data));
        });
        $this->assertIsString('');
    }
}