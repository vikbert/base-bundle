<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 28.08.14
 * Time: 11:42
 */

namespace Wk\BaseBundle\Tests\Controller;

use PHPUnit_Framework_MockObject_MockObject;
use Wk\BaseBundle\Document\Document;

/**
 * Class CrudControllerTest
 *
 * @package Wk\BaseBundle\Tests\Controller
 */
class CrudControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Mock object of CRUD controller
     *
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $controller;

    /**
     * Set up
     */
    public function setUp()
    {
        $this->controller = $this->getMockForAbstractClass('Wk\BaseBundle\Controller\CrudController');
    }

    /**
     * Tests the private method findDocumentById
     *
     * @group base
     */
    public function testFindDocumentById()
    {
        $identifier = '12345';

        /** @var Document $document */
        $document = $this->getMockForAbstractClass('Wk\BaseBundle\Document\Document');
        $document->setId($identifier);

        $repository = $this->getMockBuilder('Doctrine\ODM\MongoDB\DocumentRepository')
                           ->disableOriginalConstructor()
                           ->getMock();

        $repository->expects($this->any())
                   ->method('find')
                   ->willReturn($document);

        $reflector = new \ReflectionProperty(get_class($this->controller), 'repository');
        $reflector->setAccessible(true);
        $reflector->setValue($this->controller, $repository);

        $class = new \ReflectionClass(get_class($this->controller));
        $method = $class->getMethod('findDocumentById');
        $method->setAccessible(true);
        $result = $method->invoke($this->controller, $identifier);

        $this->assertEquals($document, $result);
    }
}
 