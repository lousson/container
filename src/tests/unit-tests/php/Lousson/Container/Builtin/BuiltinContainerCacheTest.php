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
 *  Lousson\Container\Builtin\BuiltinContainerCacheTest definition
 *
 *  @package    org.lousson.container
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Container\Builtin;

/** Dependencies: */
use Lousson\Container\AbstractContainerTest;
use Lousson\Container\Builtin\BuiltinContainerCache;

/** Exceptions: */
use Lousson\Container\Error\ContainerRuntimeError;

/**
 *  A test case for the BuiltinContainerCache implementation
 *
 *  @since      lousson/Lousson_Container-0.1.0
 *  @package    org.lousson.container
 */
final class BuiltinContainerCacheTest extends AbstractContainerTest
{
    /**
     *  Obtain the cache instance to test
     *
     *  The getContainer() method returns the container cache instance
     *  used in the tests, representing the $items provided.
     *
     *  @param  array               $items          The items to represent
     *
     *  @return \Lousson\Container\Builtin\BuiltinContainerCache
     *          A container cache instance is returned on success
     */
    public function getContainer(array $items = array())
    {
        foreach ($items as $name => &$value) {
            $aggregate = $this->getMock(self::I_AGGREGATE);
            $aggregate
                ->expects($this->any())
                ->method("asIs")
                ->will($this->returnValue($value));
            $value = $aggregate;
        }

        $default = $this->getMock(self::I_AGGREGATE);
        $default
            ->expects($this->any())
            ->method("asIs")
            ->will($this->returnCallback(function() { return null; }));

        $callback = function($name) use ($items, $default) {
            $name = (string) $name;
            return isset($items[$name])? $items[$name]: $default;
        };

        $container = $this->getMock(self::I_CONTAINER, array("get"));
        $container
            ->expects($this->once())
            ->method("get")
            ->will($this->returnCallback($callback));

        $cache = new BuiltinContainerCache($container);
        return $cache;
    }

    /**
     *  Test the get() method
     *
     *  The testGetCache() method is a test case for the get() method
     *  provided by AnyContainer instances, operating with a $name that
     *  is associated with an existing item and verifying that each
     *  invocation of get() returns the exact same item, even if the
     *  underlying container has changed.
     *
     *  @param  array               $items          The items to set up
     *  @param  string              $name           The name to query for
     *  @param  mixed               $expected       The expected value
     *
     *  @dataProvider               provideGetPresentTestParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testGetCache(array $items, $name, $expected)
    {
        $inner = $this->getContainer($items);
        $outer = new BuiltinContainerCache($inner);

        $aggregate = $this->invokeGet($outer, $name);
        $constraint = sprintf(
            "An invocation of %s::get()->asIs() must return the expected ".
            "value if the requested item (\"%s\") is present",
            get_class($outer), $name
        );

        $this->assertEquals($expected, $aggregate->asIs(), $constraint);

        $aggregate = $this->invokeGet($outer, $name);
        $constraint = sprintf(
            "An invocation of %s::get()->asIs() must return the same ".
            "value for each invocation with \"%s\"",
            get_class($outer), $name
        );

        $this->assertEquals($expected, $aggregate->asIs(), $constraint);
    }
}

