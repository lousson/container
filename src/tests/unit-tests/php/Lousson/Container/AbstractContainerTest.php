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
 *  Lousson\Container\AbstractContainerTest class definition
 *
 *  @package    org.lousson.container
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Container;

/** Dependencies: */
use Lousson\Container\AbstractContainerEntityTest;

/**
 *  Abstract test case for AnyContainer implementations
 *
 *  @since      lousson/Lousson_Container-0.1.0
 *  @package    org.lousson.container
 */
abstract class AbstractContainerTest extends AbstractContainerEntityTest
{
    /**
     *  Obtain the container instance to test
     *
     *  The getContainer() method returns the container instance used
     *  in the tests, representing the $items provided.
     *
     *  @param  array               $items          The items to represent
     *
     *  @return \Lousson\Container\AnyContainer
     *          A container instance is returned on success
     */
    abstract public function getContainer(array $items = array());

    /**
     *  Obtain arbitrary container data to test
     *
     *  The getContainerData() method is used to obtain an associative
     *  array of arbitrary data for the getContainer() method. It is used
     *  in various data providers to aggregate test input.
     *
     *  @return array
     *          A map of test data is returned on success
     */
    public function getContainerData()
    {
        return array(
            "null" => null,
            "an.arbitrary.string" => "foo. bar? baz!",
            "e-x-o-t-i-c-a-r-r-a-y-k-e-y" => array("key"),
            "object" => (object) array(),
            "scalar/bool" => true,
            "scalar/int" => 123,
            "scalar/float" => 321.31,
            "resource" => STDERR,
        );
    }

    /**
     *  Data provider for the testGetPresent() method
     *
     *  The provideGetPresentTestParameters() method is a data provider for
     *  testGetPresent(), by default based on getContainerData().
     *
     *  @return array
     *          A list of test parameters is returned on success
     */
    public function provideGetPresentTestParameters()
    {
        $data = $this->getContainerData();
        $parameters = array();

        foreach ($data as $name => $value) {
            $parameters[] = array($data, $name, $value);
        }

        return $parameters;
    }

    /**
     *  Data provider for the testGetAbsent() method
     *
     *  The provideGetAbsentTestParameters() method is a data provider for
     *  testGetAbsent(), by default based on getContainerData().
     *
     *  @return array
     *          A list of test parameters is returned on success
     */
    public function provideGetAbsentTestParameters()
    {
        $data = $this->getContainerData();
        $parameters = array();

        foreach ($data as $name => $value) {
            $parameters[] = array($data, md5("$name#salt"));
            $parameters[] = array(array(), $name);
        }

        return $parameters;
    }

    /**
     *  Test the get() method
     *
     *  The testGetPresent() method is a test case for the get() method
     *  provided by AnyContainer instances, operating with a $name
     *  that is associated with an existing item.
     *
     *  The $items map is used to set up the container, and the retun value
     *  of the get() method is validated against the $expected value.
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
    public function testGetPresent(array $items, $name, $expected)
    {
        $container = $this->getContainer($items);
        $aggregate = $this->invokeGet($container, $name);
        $constraint = sprintf(
            "An invocation of %s::get()->asIs() must return the expected ".
            "value if the requested item (\"%s\") is present",
            get_class($container), $name
        );

        $this->assertEquals($expected, $aggregate->asIs(), $constraint);
    }

    /**
     *  Test the get() method
     *
     *  The testGetPresent() method is a test case for the get() method
     *  provided by AnyContainer instances, operating with a $name
     *  that is not associated with an item.
     *
     *  The $items map is used to set up the container, and the retun value
     *  of the get() method is validated against the $expected value.
     *
     *  @param  array               $items          The items to set up
     *  @param  string              $name           The name to query for
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
    public function testGetAbsent(array $items, $name, $expected = null)
    {
        $container = $this->getContainer($items);
        $aggregate = $this->invokeGet($container, $name);
        $constraint = sprintf(
            "An invocation of %s::get()->asIs() must return the expected ".
            "value if the requested item (\"%s\") is absent",
            get_class($container), $name
        );

        $this->assertEquals($expected, $aggregate->asIs(), $constraint);
    }

    /**
     *  Invoke the get() method
     *
     *  The invokeGet() method is used internally to invoke the get()
     *  method provided by implementations of the AnyContainer
     *  interface.
     *
     *  It is a wrapper that ensures correct behavior in general, e.g.
     *  that the return value is an instance of the AnyContainerAggregate
     *  interface.
     *
     *  @param  AnyContainer        $container      The container to invoke
     *  @param  string              $name           The name to pass on
     *
     *  @return \Lousson\Container\AnyContainerAggregate
     *          A container aggregate instance is returned on success
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an implementation error
     */
    protected function invokeGet(AnyContainer $container, $name)
    {
        $constraint = sprintf(
            "The %s::get() method must return an instance of the %s ".
            "interface (invoked with: \"%s\")",
            get_class($container), self::I_AGGREGATE, $name
        );

        $aggregate = $container->get($name);
        $this->assertInstanceOf(self::I_AGGREGATE, $aggregate, $constraint);

        return $aggregate;
    }
}

