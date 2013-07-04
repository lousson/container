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
 *  Lousson\Container\AbstractContainerEntityTest class definition
 *
 *  @package    org.lousson.container
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Container;

/** Dependencies: */
use PHPUnit_Framework_TestCase;

/**
 *  Abstract test case for implementations of Lousson\Container interfaces
 *
 *  @since      lousson/Lousson_Container-0.1.0
 *  @package    org.lousson.container
 */
abstract class AbstractContainerEntityTest
    extends PHPUnit_Framework_TestCase
{
    /**
     *  The fully qualified name of the container container interface
     *
     *  @var string
     */
    const I_CONTAINER = "Lousson\\Container\\AnyContainer";

    /**
     *  The fully qualified name of the container aggregate interface
     *
     *  @var string
     */
    const I_AGGREGATE = "Lousson\\Container\\AnyContainerAggregate";

    /**
     *  The fully qualified name of the container exception interface
     *
     *  @var string
     */
    const I_EXCEPTION = "Lousson\\Container\\AnyContainerException";

    /**
     *  Obtain a container callback mock
     *
     *  The getCallbackMock() method returns a mocking Closure that
     *  offers (and validates) the common container callback API and, if
     *  invoked correctly, returns the $value provided.
     *
     *  @param  mixed               $value          The value to return
     *
     *  @return \Closure
     *          A closure instance is returned on success
     */
    protected function getCallbackMock($value)
    {
        $value = $value;
        $test = $this;
        $callback = function($container, $name) use ($test, $value) {
            $interface = AbstractContainerEntityTest::I_CONTAINER;
            $test->assertInstanceOf($interface, $container);
            $test->assertInternalType("string", $name);
            return $value;
        };

        return $callback;
    }
}

