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
 *  Lousson\Container\Generic\GenericContainerAggregateTest definition
 *
 *  @package    org.lousson.container
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Container\Generic;

/** Dependencies: */
use Lousson\Container\AbstractContainerAggregateTest;
use Lousson\Container\Generic\GenericContainerAggregate;

/** Exceptions: */
use Lousson\Container\Error\ContainerRuntimeError;
use DomainException;

/**
 *  A test case for the GenericContainerAggregate implementation
 *
 *  @since      lousson/Lousson_Container-0.1.0
 *  @package    org.lousson.container
 */
final class GenericContainerAggregateTest
    extends AbstractContainerAggregateTest
{
    /**
     *  Obtain the aggregate instance to test
     *
     *  The getAggregate() method returns the container aggregate instance used
     *  in the tests, representing the $item provided.
     *
     *  @param  mixed               $item           The item to represent
     *
     *  @return \Lousson\Container\Generic\GenericContainerAggregate
     *          A container aggregate instance is returned on success
     */
    public function getAggregate($item)
    {
        $container = $this->getMock(self::I_CONTAINER);
        $aggregate = new GenericContainerAggregate($container, "test", $item);

        return $aggregate;
    }

    /**
     *  Test the asArray() method
     *
     *  The testAsArray() method is a test case for the aggregate's
     *  asArray() method, verifying that exceptions raised by Traverable
     *  objects are handled correctly.
     *
     *  @expectedException  Lousson\Container\Error\ContainerRuntimeError
     *  @test
     *
     *  @throws \Lousson\Container\Error\ContainerRuntimeError
     *          Raised in case the test is successful
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testAsArrayError()
    {
        $object = $this->getMock("ArrayObject", array("getIterator"));
        $object
            ->expects($this->any())
            ->method("getIterator")
            ->will($this->throwException(new ContainerRuntimeError));

        $aggregate = $this->getAggregate($object);
        $aggregate->asArray();
    }

    /**
     *  Test the asArray() method
     *
     *  The testAsArray() method is a test case for the aggregate's
     *  asArray() method, verifying that exceptions raised by Traverable
     *  objects are handled correctly.
     *
     *  @expectedException  Lousson\Container\Error\ContainerRuntimeError
     *  @test
     *
     *  @throws \Lousson\Container\Error\ContainerRuntimeError
     *          Raised in case the test is successful
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testAsArrayException()
    {
        $object = $this->getMock("ArrayObject", array("getIterator"));
        $object
            ->expects($this->any())
            ->method("getIterator")
            ->will($this->throwException(new DomainException));

        $aggregate = $this->getAggregate($object);
        $aggregate->asArray();
    }

    /**
     *  Test the asObject() method
     *
     *  The testAsObject() method is a test case for the aggregate's
     *  asObject() method, verifying that requesting an unmatched class
     *  instance raises the appropriate exception.
     *
     *  @expectedException  Lousson\Container\Error\ContainerRuntimeError
     *  @test
     *
     *  @throws \Lousson\Container\Error\ContainerRuntimeError
     *          Raised in case the test is successful
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testAsObjectError()
    {
        $aggregate = $this->getAggregate($this);
        $aggregate->asObject("Exception");
    }
}

