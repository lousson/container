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
 *  Lousson\Context\Generic\GenericContextEntity class definition
 *
 *  @package    org.lousson.context
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Context\Generic;

/** Interfaces: */
use Lousson\Context\AnyContextContainer;
use Lousson\Context\AnyContextEnvelope;

/** Dependencies: */
use Lousson\Context\Generic\GenericContextEnvelope;

/** Exceptions: */
use Lousson\Context\Error\ContextRuntimeError;

/**
 *  An abstract context entity implementation
 *
 *  @since      lousson/Lousson_Context-0.1.0
 *  @package    org.lousson.context
 */
abstract class GenericContextEntity
{
    /**
     *  Aggregate a context envelope
     *
     *  The agg() method is used internally to aggregate an instance of the
     *  AnyContextEnvelope interface from the given $value.
     *
     *  If the given $value is a closure instance, this includes invoking
     *  it with the $container and $name as parameters (in that order) and
     *  using the return value to actually set up the envelope.
     *
     *  If the given $value is an envelope instance already, or if invoking
     *  it has returned such an object, it is left untouched and no further
     *  envelope layer is built.
     *
     *  @param  AnyContextContainer $container      The item's container
     *  @param  string              $name           The item's name
     *  @param  mixed               $value          The item's value
     *
     *  @return \Lousson\Context\AnyContextEnvelope
     *          A context envelope is returend on success
     *
     *  @throws \Lousson\Context\AnyContextException
     *          Raised in case aggregating the envelope has failed
     */
    protected function agg(AnyContextContainer $container, $name, $value)
    {
        if ($value instanceof \Closure) try {
            $value = $value($container, $name);
        }
        catch (\Lousson\Context\AnyContextException $error) {
            /* Nothing to do; should be allowed by the interfac - if any */
            throw $error;
        }
        catch (\Exception $error) {
            $class = get_class($error);
            $message = "Could not prepare \"$name\": Caught $class";
            $code = ContextRuntimeError::E_UNKNOWN;
            throw new ContextRuntimeError($message, $code, $error);
        }

        if (!$value instanceof AnyContextEnvelope) {
            $value = new GenericContextEnvelope($container, $name, $value);
        }

        return $value;
    }
}

