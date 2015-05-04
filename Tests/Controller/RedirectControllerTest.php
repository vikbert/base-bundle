<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 11.09.14
 * Time: 21:41
 */

namespace Wk\BaseBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Wk\BaseBundle\Controller\RedirectController;

/**
 * Class RedirectControllerTest
 *
 * @package Wk\BaseBundle\Tests\Controller
 */
class RedirectControllerTest extends WebTestCase
{
    /**
     * Data provider for testRedirect
     *
     * @return array
     */
    public function dataRedirect()
    {
        $route = '/path/after/redirect';

        return array(
            array('/origin/path?parameter=value', $route, "$route?parameter=value"),
            array('/origin/path?parameter=value', $route, $route, array(), true),
            array('/origin/path?p1=v1&p2=v2', $route, "$route?p1=v1", array(), array('p2')),
            array('/origin/path?p1=v1&p2=v2', "$route?param=val", "$route?param=val&p2=v2", array(), array('p1')),
        );
    }

    /**
     * Tests redirection target URI
     *
     * @param string $uri
     * @param string $routeUri
     * @param string $expectedUri
     * @param array  $attributes
     * @param bool   $ignoreQueryString
     *
     * @group base
     * @dataProvider dataRedirect
     */
    public function testRedirect($uri, $routeUri, $expectedUri, array $attributes = array(), $ignoreQueryString = false)
    {
        $router = $this->getMockBuilder('Symfony\Bundle\FrameworkBundle\Routing\Router')
                       ->disableOriginalConstructor()
                       ->setMethods(array('generate'))
                       ->getMock();

        $router->expects($this->any())
               ->method('generate')
               ->willReturn($routeUri);

        static::createClient();
        $container = self::$kernel->getContainer();
        $container->set('router', $router);

        $request = Request::create($uri);
        $request->attributes = new ParameterBag(array('_route_params' => $attributes));

        $controller = new RedirectController();
        $controller->setContainer($container);
        $response = $controller->redirectAction($request, 'route_name', true, false, $ignoreQueryString);

        $this->assertEquals($expectedUri, $response->getTargetUrl());
    }
}
