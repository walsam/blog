<?php


namespace App\Tests;


use PHPUnit\Framework\TestCase;

class SimplestTest extends TestCase
{
    public function testAddition(){
        $this->assertEquals(5,2+3, '5 was expected to equal 2+3');
    }

}