<?php

namespace Test\Reliese\Characterization\Support;

use Reliese\Support\Dumper;

class DumperTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Note that Dumper::export([0 => 'zero', 2 => 'two']) does not produce the keys.
     */
    public function testExport()
    {
        $this->assertEquals("'value'", Dumper::export('value'), 'Dumper does not keep string as string.');
        $this->assertEquals('1', Dumper::export(1));
        $this->assertContains("'Flarp Flarpelson'", Dumper::export(['Son of Flarp' => 'Flarp Flarpelson']));
        $this->assertContains("hello", Dumper::export(['hello']));

        // var_dump(Dumper::export([2 => 'prime', 3 => 'prime', 4 => 'not prime']));
    }
}
