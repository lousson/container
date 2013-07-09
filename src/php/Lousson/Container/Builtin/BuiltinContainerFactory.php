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
 *  Lousson\Container\Builtin\BuiltinContainerFactory class definition
 *
 *  @package    org.lousson.container
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Container\Builtin;

/** Interfaces: */
use Lousson\Container\AnyContainer;
use ArrayAccess;

/** Dependencies: */
use Lousson\Container\Callback\CallbackContainer;
use Lousson\Container\Generic\GenericContainer;

/** Exceptions: */
use Lousson\Container\Error\ContainerArgumentError;

/**
 *  A container factory
 *
 *  The Lousson\Container\Builtin\BuiltinContainerFactory is a utiltiy
 *  for creating container instances operating on various bases - e.g.
 *  associative arrays, Closure callbacks or other containers.
 *
 *  @since      lousson/Lousson_Container-0.1.0
 *  @package    org.lousson.container
 */
class BuiltinContainerFactory
{
    /**
     *  Create a factory instance
     *
     *  The constructor allows the caller to provide a $default container
     *  instance, to be returned by getContainer() if invoked without any
     *  parameter or NULL - instead of the builtin default.
     *
     *  @param  AnyContainer        $default        The default container
     */
    public function __construct(AnyContainer $default = null)
    {
        if (null === $default) {
            $default = new GenericContainer();
        }

        $this->default = $default;
    }

    /**
     *  Obtain a container instance
     *
     *  The getContainer() method is used to obtain a container instance
     *  for the given $base (if any), e.g.:
     *
     *- Arrays are used to create GenericContainer instances
     *
     *- Callbacks given as closures are used to create CallbackContainer
     *  instances. This, the Closure must implement the common container
     *  callback interface (accepting a container as first and the name
     *  of the requested object as second argument)
     *
     *- Bases that are instances of the AnyContainer interface already
     *  are left untouched and returned as they are
     *
     *- Instances of the ArrayAccess interface are wrapped and treaten
     *  like arrays
     *
     *- An absent or NULL value will result in the default container
     *  being returned
     *
     *  Further base types might get supported in the future.
     *
     *  @param  mixed               $base           The container base
     *
     *  @return \Lousson\Container\AnyContainer
     *          A container instance is returned on success
     *
     *  @throws \Lousson\Container\Error\ContainerArgumentError
     *          Raised in case the $base's type is not supported
     */
    public function getContainer($base = null)
    {
        if (is_array($base)) {
            $container = new GenericContainer($base);
        }
        else if (is_object($base)) {
            $container = $this->getContainerFromObject($base);
        }
        else if (null === $base) {
            $container = $this->default;
        }
        else {
            $type = gettype($base);
            $message = "Could not create container from $type";
            throw new ContainerArgumentError($message);
        }

        return $container;
    }

    /**
     *  Obtain a container based on any object
     *
     *  The getContainerFromObject() method is used internally to obtain
     *  a container instances based on arbitrary objects.
     *  In case the given $base is not an instance of any of the supported
     *  classes or interfaces, however, an exception is thrown.
     *
     *  @param  object              $base           The base object
     *
     *  @return \Lousson\Container\AnyContainer
     *          A container instance is returned on success
     *
     *  @throws \Lousson\Container\Error\ContainerArgumentError
     *          Raised in case the $base's type is not supported
     */
    private function getContainerFromObject($base)
    {
        if ($base instanceof \Closure) {
            $container = new CallbackContainer($base);
        }
        else if ($base instanceof AnyContainer) {
            $container = $base;
        }
        else if ($base instanceof ArrayAccess) {
            $container = $this->getContainerFromPimple($base);
        }
        else {
            $class = get_class($base);
            $message = "Could not create container from $class";
            throw new ContainerArgumentError($message);
        }

        return $container;
    }

    /**
     *  Create a container based on ArrayAccess instances
     *
     *  The getContainerFromPimple() method is used internally to create
     *  container instances based on Pimple-like objects.
     *  In fact, this should work with any instance of classes implementing
     *  the ArrayAccess interface.
     *
     *  @param  ArrayAccess         $pimple         The base object
     *
     *  @return \Lousson\Container\Callback\CallbackContainer
     *          A callback container instance is returned on success
     */
    private function getContainerFromPimple(ArrayAccess $pimple)
    {
        $callback = function($container, $name) use ($pimple) {
            $item = $pimple[(string) $name];
            return $item;
        };

        $container = new CallbackContainer($callback);
        return $container;
    }

    /**
     *  The default container instance
     *
     *  @var \Lousson\Container\AnyContainer
     */
    private $default;
}

