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
 *  Lousson\Container\Builtin\BuiltinContainerLoaderTest definition
 *
 *  @package    org.lousson.container
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Container\Builtin;

/** Dependencies: */
use Lousson\Container\Builtin\BuiltinContainerLoader;
use PHPUnit_Framework_TestCase;

/**
 *  A test case for the BuiltinContainerLoader implementation
 *
 *  @since      lousson/Lousson_Container-0.1.0
 *  @package    org.lousson.container
 */
final class BuiltinContainerLoaderTest extends PHPUnit_Framework_TestCase
{
    /**
     *  Test the loadContainer() method
     *
     *  The testLoadContainer() method is a smoke test for the builtin
     *  container loader's loadContainer() method.
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testLoadContainer()
    {
        file_put_contents(
            $this->file, '<?php $container->set("foo", "bar");'
        );

        $loader = new BuiltinContainerLoader();
        $container = $loader->loadContainer($this->file);
        $value = $container->get("foo")->asString();

        $this->assertEquals("bar", $value);
    }

    /**
     *  Test the loadContainer() method
     *
     *  The testLoadContainerDecorator() method checks whether the builtin
     *  container loader's loadContainer() method applies a decorator in
     *  case a $base container has been provided.
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testLoadContainerDecorator()
    {
        $loader = new BuiltinContainerLoader();
        $inner = $this->getMock("Lousson\\Container\\AnyContainer");
        $container = $loader->loadContainer("nonexistentpath", $inner);
        $this->assertInstanceOf(
            "Lousson\\Container\\Generic\\GenericContainerDecorator",
            $container
        );
    }

    /**
     *  Test the loadContainer() method
     *
     *  The testLoadContainerDecorator() method checks whether the builtin
     *  container loader's loadContainer() method does not violate the API
     *  declaration in case an exception is thrown by a file included.
     *
     *  @expectedException  Lousson\Container\Error\ContainerRuntimeError
     *  @test
     *
     *  @throws \Lousson\Container\Error\ContainerRuntimeError
     *          Raised in case the test is successful
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testLoadContainerError()
    {
        file_put_contents($this->file, '<?php throw new Exception;');

        $loader = new BuiltinContainerLoader();
        $loader->loadContainer($this->file);
    }

    /**
     *  Set up a temporary file name
     *
     *  The setUp() method is called internally, before each test. It
     *  populates the $file member with a random file name.
     */
    public function setUp()
    {
        $this->file = tempnam(sys_get_temp_dir(), "lousson-test-");
    }

    /**
     *  Delete the temporary file, if any
     *
     *  The teadDown() method is called internally, after each test. It
     *  unlink()s the temporary file tested, if any.
     */
    public function tearDown()
    {
       file_exists($this->file) && unlink($this->file);
    }

    /**
     *  A temporary file name
     *
     *  @var string
     */
    private $file;
}

