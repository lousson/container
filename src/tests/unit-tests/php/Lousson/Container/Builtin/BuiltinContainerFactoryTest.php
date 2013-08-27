<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 textwidth=75: *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Copyright (c) 2013, The Lousson Project                               *
 *                                                                       *
 * All rights reserved.                                                  *
 *                                                                       *
 * Redistribution and use in source and binary forms, with or without    *
 * modification, are permitted provided that the following conditions    *
 * are met:                                                              *
 *                                                                       *
 * 1) Redistributions of source code must retain the above copyright     *
 *    notice, this list of conditions and the following disclaimer.      *
 * 2) Redistributions in binary form must reproduce the above copyright  *
 *    notice, this list of conditions and the following disclaimer in    *
 *    the documentation and/or other materials provided with the         *
 *    distribution.                                                      *
 *                                                                       *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS   *
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT     *
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS     *
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE        *
 * COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT,            *
 * INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES    *
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR    *
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)    *
 * HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT,   *
 * STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)         *
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED   *
 * OF THE POSSIBILITY OF SUCH DAMAGE.                                    *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

/**
 *  Lousson\Container\Builtin\BuiltinContainerFactoryTest definition
 *
 *  @package    org.lousson.container
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Container\Builtin;

/** Dependencies: */
use Lousson\Container\Builtin\BuiltinContainerFactory;
use PHPUnit_Framework_TestCase;

/**
 *  A test case for the BuiltinContainerFactory implementation
 *
 *  @since      lousson/Lousson_Container-0.1.0
 *  @package    org.lousson.container
 */
final class BuiltinContainerFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     *  Test the getContainer() method
     *
     *  The testGetContainer() method is a smoke test invoking the
     *  factory's getContainer() method without any arguments.
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case ab assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testGetContainer()
    {
        $expected = "Lousson\\Container\\Generic\\GenericContainer";
        $factory = new BuiltinContainerFactory();
        $container = $factory->getContainer();
        $this->assertInstanceOf($expected, $container);
    }

    /**
     *  Test the getContainer() method
     *
     *  The testGetContainerFromArray() method is a smoke test invoking
     *  the factory's getContainer() method with an array argument.
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case ab assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testGetContainerFromArray()
    {
        $expected = "Lousson\\Container\\Generic\\GenericContainer";
        $factory = new BuiltinContainerFactory();
        $container = $factory->getContainer(array("foo" => "bar"));
        $this->assertInstanceOf($expected, $container);
        $this->assertEquals("bar", $container->get("foo")->asIs());
    }

    /**
     *  Test the getContainer() method
     *
     *  The testGetContainerFromPimple() method is a smoke test invoking
     *  the factory's getContainer() method with an ArrayAccess argument.
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case ab assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testGetContainerFromPimple()
    {
        $expected = "Lousson\\Container\\Callback\\CallbackContainer";
        $factory = new BuiltinContainerFactory();
        $arrayAccess = $this->getMock("ArrayAccess");
        $arrayAccess
            ->expects($this->once())
            ->method("offsetGet")
            ->will($this->returnValue("bar"));

        $container = $factory->getContainer($arrayAccess);
        $this->assertInstanceOf($expected, $container);
        $this->assertEquals("bar", $container->get("foo")->asIs());
    }

    /**
     *  Test the getContainer() method
     *
     *  The testGetContainerFromClosure() method is a smoke test invoking
     *  the factory's getContainer() method with a Closure argument.
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case ab assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testGetContainerFromClosure()
    {
        $callback = function($container, $name) {
            return __FILE__;
        };

        $expected = "Lousson\\Container\\Callback\\CallbackContainer";
        $factory = new BuiltinContainerFactory();
        $container =  $factory->getContainer($callback);
        $this->assertInstanceOf($expected, $container);
        $this->assertEquals(__FILE__, $container->get("foo")->asIs());
    }

    /**
     *  Test the getContainer() method
     *
     *  The testGetContainerFromContainer() method is a smoke test invoking
     *  the factory's getContainer() method with a container argument.
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case ab assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testGetContainerFromContainer()
    {
        $factory = new BuiltinContainerFactory();
        $base = $this->getMock("Lousson\\Container\\AnyContainer");
        $container = $factory->getContainer($base);
        $this->assertSame($base, $container);
    }

    /**
     *  Test the getContainer() method
     *
     *  The testGetContainerFromConfig() method is a smoke test invoking
     *  the factory's getContainer() method with an AnyConfig instance.
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case ab assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testGetContainerFromConfig()
    {
        $expected[] = "Lousson\\Container\\Callback\\CallbackContainer";
        $expected[] = "Lousson\\Container\\AnyContainerAggregate";

        $methods = array("getOption");

        $config = $this->getMock("Lousson\\Config\\AnyConfig", $methods);
        $config
            ->expects($this->once())
            ->method("getOption")
            ->will($this->returnValue(null));

        $factory = new BuiltinContainerFactory();
        $container = $factory->getContainer($config);
        $this->assertInstanceOf($expected[0], $container);

        $aggregate = $container->get("foo.bar.baz");
        $this->assertInstanceOf($expected[1], $aggregate);
    }

    /**
     *  Test the getContainer() method
     *
     *  The testGetContainerFromNull() method is a smoke test invoking
     *  the factory's getContainer() method with a NULL argument.
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case ab assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testGetContainerFromNull()
    {
        $expected = "Lousson\\Container\\Generic\\GenericContainer";
        $factory = new BuiltinContainerFactory();
        $container = $factory->getContainer(null);
        $this->assertInstanceOf($expected, $container);
    }

    /**
     *  Test the getContainer() method
     *
     *  The testGetContainerTypeError() method verifies that the factory
     *  raises an exception if invoked with an unsupported type argument.
     *
     *  @expectedException  Lousson\Container\Error\ContainerArgumentError
     *
     *  @throws \Lousson\Container\Error\ContainerArgumentError
     *          Raised in case the test is successful
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testGetContainerTypeError()
    {
        $factory = new BuiltinContainerFactory();
        $factory->getContainer(STDERR);
    }

    /**
     *  Test the getContainer() method
     *
     *  The testGetContainerTypeError() method verifies that the factory
     *  raises an exception if invoked with an unsupported class argument.
     *
     *  @expectedException  Lousson\Container\Error\ContainerArgumentError
     *
     *  @throws \Lousson\Container\Error\ContainerArgumentError
     *          Raised in case the test is successful
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testGetContainerClassError()
    {
        $factory = new BuiltinContainerFactory();
        $factory->getContainer($this);
    }
}

