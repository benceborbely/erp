<?php

namespace Bence\Tests\Routing;

use Bence\Routing\Route;
use Bence\Routing\RouteResolver;

/**
 * Class RouteResolverTest
 *
 * @author Bence Borbély
 */
class RouteResolverTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param $queryParams
     * @param Route $expected
     *
     * @dataProvider routerDataProvider
     *
     * @covers Bence\Routing\RouteResolver::resolve
     */
    public function testResolve($queryParams, Route $expected)
    {
        $request = $this
            ->getMockBuilder('GuzzleHttp\Psr7\ServerRequest')
            ->disableOriginalConstructor()
            ->setMethods(['getQueryParams'])
            ->getMock();

        $request
            ->expects($this->once())
            ->method('getQueryParams')
            ->will($this->returnValue($queryParams));

        $resolver = new RouteResolver();

        $this->assertEquals($expected, $resolver->resolve($request));
    }

    /**
     * @return array
     */
    public function routerDataProvider()
    {
        return [
            $this->getDataIfRouteSetButTaskNot(),
            $this->getDataIfRouteAndTaskSetAsWell(),
            $this->getDataIfRouteNotSet(),
        ];
    }

    /**
     * @return array
     */
    public function getDataIfRouteSetButTaskNot()
    {
        $queryParams = [
            'route' => 'home',
        ];

        $expected = new Route();
        $expected
            ->setParameter($queryParams['route'])
            ->setTask('index')
            ->setControllerClass('Bence\Controller\HomeController')
            ->setActionMethod('indexAction');

        return [
            $queryParams,
            $expected,
        ];
    }

    /**
     * @return array
     */
    public function getDataIfRouteAndTaskSetAsWell()
    {
        $queryParams = [
            'route' => 'home',
            'task' => 'create',
        ];

        $expected = new Route();
        $expected
            ->setParameter($queryParams['route'])
            ->setTask('create')
            ->setControllerClass('Bence\Controller\HomeController')
            ->setActionMethod('createAction');

        return [
            $queryParams,
            $expected,
        ];
    }

    /**
     * @return array
     */
    public function getDataIfRouteNotSet()
    {
        $queryParams = [
            'keyword' => 'hdd',
        ];

        $expected = new Route();

        return [
            $queryParams,
            $expected,
        ];
    }

}
