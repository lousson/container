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
 *  Lousson\Context\Generic\GenericContextEnvelopeTest class definition
 *
 *  @package    org.lousson.context
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Context\Generic;

/** Dependencies: */
use Lousson\Context\AbstractContextEnvelopeTest;
use Lousson\Context\Generic\GenericContextEnvelope;

/** Exceptions: */
use Lousson\Context\Error\ContextRuntimeError;
use DomainException;

/**
 *  A test case for the GenericContextEnvelope implementation
 *
 *  @since      lousson/Lousson_Context-0.1.0
 *  @package    org.lousson.context
 */
final class GenericContextEnvelopeTest
    extends AbstractContextEnvelopeTest
{
    /**
     *  Obtain the envelope instance to test
     *
     *  The getEnvelope() method returns the context envelope instance used
     *  in the tests, representing the $item provided.
     *
     *  @param  mixed               $item           The item to represent
     *
     *  @return \Lousson\Context\Generic\GenericContextEnvelope
     *          A context envelope instance is returned on success
     */
    public function getEnvelope($item)
    {
        $container = $this->getMock(self::I_CONTAINER);
        $envelope = new GenericContextEnvelope($container, "test", $item);

        return $envelope;
    }

    /**
     *  Test the asArray() method
     *
     *  The testAsArray() method is a test case for the envelope's
     *  asArray() method, verifying that exceptions raised by Traverable
     *  objects are handled correctly.
     *
     *  @expectedException      Lousson\Context\Error\ContextRuntimeError
     *  @test
     *
     *  @throws \Lousson\Context\Error\ContextRuntimeError
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
            ->will($this->throwException(new ContextRuntimeError));

        $envelope = $this->getEnvelope($object);
        $envelope->asArray();
    }

    /**
     *  Test the asArray() method
     *
     *  The testAsArray() method is a test case for the envelope's
     *  asArray() method, verifying that exceptions raised by Traverable
     *  objects are handled correctly.
     *
     *  @expectedException      Lousson\Context\Error\ContextRuntimeError
     *  @test
     *
     *  @throws \Lousson\Context\Error\ContextRuntimeError
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

        $envelope = $this->getEnvelope($object);
        $envelope->asArray();
    }

    /**
     *  Test the asObject() method
     *
     *  The testAsObject() method is a test case for the envelope's
     *  asObject() method, verifying that requesting an unmatched class
     *  instance raises the appropriate exception.
     *
     *  @expectedException      Lousson\Context\Error\ContextRuntimeError
     *  @test
     *
     *  @throws \Lousson\Context\Error\ContextRuntimeError
     *          Raised in case the test is successful
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testAsObjectError()
    {
        $envelope = $this->getEnvelope($this);
        $envelope->asObject("Exception");
    }
}

