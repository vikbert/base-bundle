<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 08.01.15
 * Time: 15:21
 */

namespace Wk\BaseBundle\Tests\Lib;

use Wk\BaseBundle\Lib\RequestParam;

/**
 * Class RequestParamTest
 *
 * @package Wk\BaseBundle\Tests\Lib
 */
class RequestParamTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider for testConvertType
     *
     * @return array
     */
    public function dataConvertType()
    {
        return array(
            array(12, 12),
            array(12, '12'),
            array(12.5, '12.5'),
            array(12.5, 12.5),
            array(array(12), array(12)),
            array(array(12), array('12')),
            array(array(12.5), array('12.5')),
            array(array(12.5), array(12.5)),
            array(array('1.5' => 12), array('1.5' => 12)),
            array(array('1.5' => 12), array('1.5' => '12')),
            array(array('1.5' => 12.5), array('1.5' => '12.5')),
            array(array('1.5' => 12.5), array('1.5' => 12.5)),
            array(array('key' => 12), array('key' => 12)),
            array(array('key' => 12), array('key' => '12')),
            array(array('key' => 12.5), array('key' => '12.5')),
            array(array('key' => 12.5), array('key' => 12.5)),
            array(new \stdClass(), new \stdClass()),
        );
    }

    /**
     * Tests the method convertType of the RequestParam class
     *
     * @param mixed $expected
     * @param mixed $variable
     *
     * @dataProvider dataConvertType
     * @group base
     */
    public function testConvertType($expected, $variable)
    {
        $this->assertEquals($expected, RequestParam::convertType($variable));
    }
}
