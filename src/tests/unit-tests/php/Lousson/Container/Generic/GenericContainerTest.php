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
 *  Lousson\Container\Generic\GenericContainerTest class definition
 *
 *  @package    org.lousson.container
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Container\Generic;

/** Dependencies: */
use Lousson\Container\AbstractContainerTest;
use Lousson\Container\Generic\GenericContainer;

/** Exceptions: */
use Lousson\Container\Error\ContainerRuntimeError;
use DomainException;

/**
 *  A test case for the GenericContainer implementation
 *
 *  @since      lousson/Lousson_Container-0.1.0
 *  @package    org.lousson.container
 */
final class GenericContainerTest
    extends AbstractContainerTest
{
    /**
     *  Obtain the container instance to test
     *
     *  The getContainer() method returns the container container instance
     *  used in the tests, representing the $items provided.
     *
     *  @param  array               $items          The items to represent
     *
     *  @return \Lousson\Container\Generic\GenericContainerr
     *          A container container instance is returned on success
     */
    public function getContainer(array $items = array())
    {
        $container = new GenericContainer($items);
        return $container;
    }

    /**
     *  Test the set() method
     *
     *  The testSetPresent() method is a test case for the set() method
     *  provided by GenericContainer instances, operating with a $name
     *  that is associated with an existing item.
     *
     *  The $items map is used to set up the container, and the retun value
     *  of the get() method (after set() has been invoked) is validated
     *  against the $expected value.
     *
     *  @param  array               $items          The items to set up
     *  @param  string              $name           The name to manipulate
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
    public function testSetPresent(array $items, $name, $expected)
    {
        $container = $this->getContainer($items);
        $container->set($name, $expected);

        $aggregate = $this->invokeGet($container, $name);
        $this->assertEquals(
            $expected, $aggregate->asIs(),
            "The aggregate returned by GenericContainer::get() ".
            "hold the item set() before"
        );
    }

    /**
     *  Test the set() method
     *
     *  The testSetPresent() method is a test case for the set() method
     *  provided by GenericContainer instances, operating with a $name
     *  that is not associated with an item.
     *
     *  The $items map is used to set up the container, and the retun value
     *  of the get() method (after set() has been invoked) is validated
     *  against the $expected value.
     *
     *  @param  array               $items          The items to set up
     *  @param  string              $name           The name manipulate
     *  @param  mixed               $expected       The expected value
     *
     *  @dataProvider               provideGetAbsentTestParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testSetAbsent(array $items, $name, $expected = null)
    {
        $container = $this->getContainer($items);
        $container->set($name, $expected);

        $aggregate = $this->invokeGet($container, $name);
        $this->assertEquals(
            $expected, $aggregate->asIs(),
            "The aggregate returned by GenericContainer::get() ".
            "hold the item set() before"
        );
    }

    /**
     *  Test the get() method
     *
     *  The testGetCallback() method is a test case for the get() method
     *  provided by GenericContainer instances, operating much like
     *  testGetPresent() but using closures to wrap the $items.
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
    public function testGetCallback(array $items, $name, $expected)
    {
        foreach ($items as &$value) {
            $value = $this->getCallbackMock($value);
        }

        $this->testGetPresent($items, $name, $expected);
    }

    /**
     *  Test the get() method
     *
     *  The testGetException() method is a test case for the get() method,
     *  verifying that exceptions beside those implementing the container
     *  exception interface are handled properly.
     *
     *  @param  array               $items          The items to set up
     *  @param  string              $name           The name to query for
     *  @param  mixed               $expected       The expected value
     *
     *  @dataProvider       provideGetPresentTestParameters
     *  @expectedException  Lousson\Container\Error\ContainerRuntimeError
     *  @test
     *
     *  @throws \Lousson\Container\Error\ContainerRuntimeError
     *          Raised in case the test is successful
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testGetException(array $items, $name, $expected)
    {
        $items[$name] = function($container, $name) {
            throw new DomainException("Foo? Bar: $name");
        };

        $this->testGetPresent($items, $name, $expected);
    }

    /**
     *  Test the get() method
     *
     *  The testGetError() method is a test case for the get() method,
     *  verifying that container errors are handled properly.
     *
     *  @param  array               $items          The items to set up
     *  @param  string              $name           The name to query for
     *  @param  mixed               $expected       The expected value
     *
     *  @dataProvider       provideGetPresentTestParameters
     *  @expectedException  Lousson\Container\Error\ContainerRuntimeError
     *  @test
     *
     *  @throws \Lousson\Container\Error\ContainerRuntimeError
     *          Raised in case the test is successful
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    public function testGetError(array $items, $name, $expected)
    {
        $items[$name] = function($container, $name) {
            throw new ContainerRuntimeError("Foo: $name");
        };

        $this->testGetPresent($items, $name, $expected);
    }
}

