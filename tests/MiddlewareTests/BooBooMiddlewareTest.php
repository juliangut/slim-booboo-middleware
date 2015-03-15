<?php
/**
 * Slim Framework BooBoo middleware (https://github.com/juliangut/slim-booboo-middleware)
 *
 * @link https://github.com/juliangut/slim-booboo-middleware for the canonical source repository
 * @license https://raw.githubusercontent.com/juliangut/slim-booboo-middleware/master/LICENSE
 */

namespace Jgut\Slim\MiddlewareTests;

use Jgut\Slim\Middleware\BooBooMiddleware;
use League\BooBoo\Formatter\CommandLineFormatter;

/**
 * @covers Jgut\Slim\Middleware\BooBooMiddleware
 */
class BooBooMiddlewareTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers Jgut\Slim\Middleware\BooBooMiddleware::addFormatter
     * @covers Jgut\Slim\Middleware\BooBooMiddleware::createFormatter
     * @covers Jgut\Slim\Middleware\BooBooMiddleware::getFormatters
     * @covers Jgut\Slim\Middleware\BooBooMiddleware::emptyFormatters
     */
    public function testFormatterCreation()
    {
        $middleware = new BooBooMiddleware();

        $this->assertEquals(0, count($middleware->getFormatters()));

        $middleware->addFormatter('command-line');
        $middleware->addFormatter('html');
        $middleware->addFormatter('html-table');
        $middleware->addFormatter('json');
        $middleware->addFormatter('\League\BooBoo\Formatter\NullFormatter');
        $this->assertEquals(5, count($middleware->getFormatters()));
        $this->assertInstanceOf('\League\BooBoo\Formatter\CommandLineFormatter', $middleware->getFormatters()[0]);
        $this->assertInstanceOf('\League\BooBoo\Formatter\NullFormatter', $middleware->getFormatters()[4]);

        $middleware->emptyFormatters();
        $this->assertEquals(0, count($middleware->getFormatters()));
    }

    /**
     * @covers Jgut\Slim\Middleware\BooBooMiddleware::addFormatter
     * @covers Jgut\Slim\Middleware\BooBooMiddleware::getFormatters
     */
    public function testFormatterAddition()
    {
        $middleware = new BooBooMiddleware();

        $middleware->addFormatter(new CommandLineFormatter(), E_ERROR | E_WARNING | E_USER_ERROR | E_USER_WARNING);
        $this->assertEquals(1, count($middleware->getFormatters()));
        $this->assertInstanceOf('\League\BooBoo\Formatter\CommandLineFormatter', $middleware->getFormatters()[0]);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFomatterCreationError()
    {
        $middleware = new BooBooMiddleware();

        $middleware->addFormatter('non-existent');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testFomatterAdditionError()
    {
        $middleware = new BooBooMiddleware();

        $formatter = [];

        $middleware->addFormatter($formatter);
    }

    /**
     * @covers Jgut\Slim\Middleware\BooBooMiddleware::addHandler
     * @covers Jgut\Slim\Middleware\BooBooMiddleware::getHandlers
     * @covers Jgut\Slim\Middleware\BooBooMiddleware::emptyHandlers
     */
    public function testHandlerAddition()
    {
        $middleware = new BooBooMiddleware();

        $this->assertEquals(0, count($middleware->getHandlers()));

        $handler = $this->getMock('League\\BooBoo\\Handler\\HandlerInterface', array(), array(), '', false);
        $middleware->addHandler($handler);
        $this->assertEquals(1, count($middleware->getHandlers()));
        $this->assertInstanceOf('\League\BooBoo\Handler\HandlerInterface', $middleware->getHandlers()[0]);

        $middleware->emptyHandlers();
        $this->assertEquals(0, count($middleware->getHandlers()));
    }

    /**
     * @covers Jgut\Slim\Middleware\BooBooMiddleware::register
     */
    public function testEmptyRegistration()
    {
        $middleware = new BooBooMiddleware();

        $middleware->register();
    }

    /**
     * @covers Jgut\Slim\Middleware\BooBooMiddleware::addFormatter
     * @covers Jgut\Slim\Middleware\BooBooMiddleware::addHandler
     * @covers Jgut\Slim\Middleware\BooBooMiddleware::register
     */
    public function testRegistration()
    {
        $middleware = new BooBooMiddleware();

        $formatter = $this->getMock('League\\BooBoo\\Formatter\\NullFormatter', array(), array(), '', false);
        $middleware->addFormatter($formatter);

        $handler = $this->getMock('League\\BooBoo\\Handler\\HandlerInterface', array(), array(), '', false);
        $middleware->addHandler($handler);

        $middleware->register();
    }
}
