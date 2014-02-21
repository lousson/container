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
 *  Lousson\Container\Generic\GenericContainerEntity class definition
 *
 *  @package    org.lousson.container
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Container\Generic;

/** Interfaces: */
use Lousson\Container\AnyContainerAggregate;
use Lousson\Container\AnyContainer;

/** Dependencies: */
use Lousson\Container\Generic\GenericContainerAggregate;

/** Exceptions: */
use Lousson\Container\Error\ContainerRuntimeError;

/**
 *  An abstract container entity implementation
 *
 *  @since      lousson/Lousson_Container-0.1.0
 *  @package    org.lousson.container
 */
abstract class GenericContainerEntity
{
    /**
     *  Aggregate a container aggregate
     *
     *  The aggregate() method is used internally to create an instance
     *  of the AnyContainerAggregate interface from the given $value.
     *
     *  If the given $value is a closure instance, this includes invoking
     *  it with the $container and $name as parameters (in that order) and
     *  using the return value to actually set up the aggregate.
     *
     *  If the given $value is an aggregate instance already, or if it's a
     *  closure and returns such an object, no further layer is built.
     *
     *  @param  AnyContainer        $container      The item's container
     *  @param  string              $name           The item's name
     *  @param  mixed               $value          The item's value
     *
     *  @return \Lousson\Container\AnyContainerAggregate
     *          A container aggregate is returend on success
     *
     *  @throws \Lousson\Container\AnyContainerException
     *          Raised in case aggregating the aggregate has failed
     */
    protected function aggregate(AnyContainer $container, $name, $value)
    {
        $name = (string) $name;

        if ($value instanceof \Closure) try {
            $value = $value($container, $name);
        }
        catch (\Lousson\Container\AnyContainerException $error) {
            /* Nothing to do; should be allowed by the interface */
            throw $error;
        }
        catch (\Exception $error) {
            $message = "Could not prepare \"$name\": Caught $error";
            $code = ContainerRuntimeError::E_UNKNOWN;
            throw new ContainerRuntimeError($message, $code, $error);
        }

        if (!$value instanceof AnyContainerAggregate) {
            $value = new GenericContainerAggregate(
                $container, $name, $value
            );
        }

        return $value;
    }
}

