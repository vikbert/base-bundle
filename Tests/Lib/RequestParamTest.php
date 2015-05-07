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
     * @return array (expected, variable, fixedType)
     */
    public function validConvertTypeProvider()
    {
        $stdClassObj = new \stdClass();
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
            array($stdClassObj, $stdClassObj),
            array(12, 12, 'int'),
            array(12, '12', 'int'),
            array(12, '12', 'int'),
            array(true, 'true', 'bool'),
            array(true, 1, 'bool'),
            array(true, 2, 'bool'),
            array(true, true, 'bool'),
            array(false, 'false', 'bool'),
            array(false, '0', 'bool'),
            array(0, '0', 'int'),
            array(false, null, 'bool'),
            array(false, false, 'bool'),
            array(0.0, 'abc', 'float'),
            array('12', '12', 'string'),
            array('12.5', '12.5', 'string'),
            array('12.5', 12.5, 'string'),
            array(array(12), array(12), 'int'),
            array(array('12'), array(12), array('string')),
            array(array(12.0), array(12), array('float')),
            array(array('12.5'), array('12.5'), array('string')),
            array(array(12), array('12.5'), array('int')),
            array(array(0), array('0.5'), array('int')),
            array(array('12.5'), array(12.5), array('string')),
            array(array('1.5' => '12'), array('1.5' => '12'), array('1.5' => 'string')),
            array(array('1.5' => 12.5), array('1.5' => '12.5'), array('1.5'=> 'float')),
            array(array('1.5' => 12.5), array('1.5' => 12.5), array('1.5' => 'float')),
            array(array('key' => 12), array('key' => 12), 'float'),
            array(array('key' => 12), array('key' => 12), array('float')),
            array(array('key' => 12), array('key' => 12), array('key' => 'int')),
            array(array('key' => 12), array('key' => '12'), array('key' => 'int')),
            array(array('key' => '12'), array('key' => '12'), array('key' => 'string')),
            array(array('key' => '12.5'), array('key' => '12.5'), array('key' => 'string')),
            array(array('key' => 12.5), array('key' => '12.5'), array('key' => 'float')),
            array(array('key' => true), array('key' => 'true'), array('key' => 'bool')),
            array(array('key' => false), array('key' => 'false'), array('key' => 'bool')),
            array(array('key' => false), array('key' => '0'), array('key' => 'bool')),
            array(array('key' => 0), array('key' => '0'), array('key' => 'int')),
            array(array('nested' => array('key' => 12)), array('nested' => array('key' => '12')), array('nested' => array('key' => 'int'))),
            array(array('nested' => array('key' => 12.0)), array('nested' => array('key' => '12')), array('nested' => array('key' => 'float'))),
            array(
                array('nested' => array('key' => 12.0, 'nonFixed' => 12)),
                array('nested' => array('key' => '12', 'nonFixed' => '12')),
                array('nested' => array('key' => 'float'))
            ),
            array(
                array('nested' => array('key' => 12, 'noFixedType' => 12)),
                array('nested' => array('key' => '12', 'noFixedType' => '12')),
                array('wontBeApplied' => array('key' => 'float'))
            ),
        );
    }

    /**
     * Tests the method convertType of the RequestParam class
     *
     * @param mixed $expected
     * @param mixed $variable
     * @param mixed $fixedType
     *
     * @dataProvider validConvertTypeProvider
     */
    public function testConvertType($expected, $variable, $fixedType = null)
    {
        $result = RequestParam::convertType($variable, $fixedType);
        $this->assertSame($expected, $result);
    }
}
