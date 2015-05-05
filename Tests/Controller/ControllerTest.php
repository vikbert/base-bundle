<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 28.08.14
 * Time: 11:42
 */

namespace Wk\BaseBundle\Tests\Controller;

use Wk\BaseBundle\Controller\Controller;

/**
 * Class ControllerTest
 *
 * @package Wk\BaseBundle\Tests\Controller
 */
class ControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider for testCreateBadRequestException
     *
     * @return array
     */
    public function dataCreateBadRequestException()
    {
        return array(
            array(null, 0),
            array('Invalid Argument', 400),
            array('No payload found', 403),
        );
    }

    /**
     * Tests the method createBadRequestException
     *
     * @param string $message
     * @param int    $code
     *
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @expectedException \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     * @dataProvider dataCreateBadRequestException
     */
    public function testCreateBadRequestException($message, $code)
    {
        $controller = new Controller();
        $method = new \ReflectionMethod(get_class($controller), 'createBadRequestException');
        $method->setAccessible(true);
        $exception = $method->invokeArgs($controller, array($message, null, $code));

        $this->assertEquals($message, $exception->getMessage(), "Exception message is not equal to $message");
        $this->assertEquals($code, $exception->getCode(), "Exception code is not equal to $code");

        throw $exception;
    }
}
 