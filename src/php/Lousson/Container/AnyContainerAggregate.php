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
 *  Lousson\Container\AnyContainerAggregate interface definition
 *
 *  @package    org.lousson.container
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Container;

/**
 *  An interface for container aggregates
 *
 *  The Lousson\Container\AnyContainerAggregate interface declares an API
 *  for container aggregates: Wrappers around container items.
 *  Those are used to perform type verification and conversion, as well as
 *  providing easy to use fallback mechanisms.
 *
 *  @since      lousson/Lousson_Container-0.1.0
 *  @package    org.lousson.container
 */
interface AnyContainerAggregate
{
    /**
     *  Obtain the plain item value
     *
     *  The asIs() method is used to obtain the plain value of the item
     *  held by the container aggregate, without any further validation.
     *
     *  @return mixed
     *          The plain item value is returned on success
     */
    public function asIs();

    /**
     *  Force a boolean value
     *
     *  The asBool() method attempts to convert the held item into a
     *  boolean value before returning it - unless it is a bool already.
     *
     *  @return bool
     *          A boolean value is returned on success
     */
    public function asBool();

    /**
     *  Force an interger value
     *
     *  The asInt() method attempts to convert the held item into an
     *  integer balue before returning it - unless it is an int already.
     *
     *  @return int
     *          An integer is returned on success
     *
     *  @throws \Lousson\Container\AnyContainerException
     *          Raised in case the held item is not a scalar
     */
    public function asInt();

    /**
     *  Force a float value
     *
     *  The asFloat() method attempts to convert the held item into a
     *  float value before returning it - unless it is a float already.
     *
     *  @return float
     *          A floating point number is returned on success
     *
     *  @throws \Lousson\Container\AnyContainerException
     *          Raised in case the held item is not a scalar
     */
    public function asFloat();

    /**
     *  Force a string value
     *
     *  The asString() method attempts to convert the held item into a
     *  string value before returning it - unless it is a string already.
     *
     *  @return string
     *          A string is returned on success
     *
     *  @throws \Lousson\Container\AnyContainerException
     *          Raised in case the string conversion has failed
     */
    public function asString();

    /**
     *  Force an array value
     *
     *  The asArray() method attempts to convert the held item into an
     *  array before returning it - unless it is an array already.
     *
     *  @return array
     *          An array is returned on success
     *
     *  @throws \Lousson\Container\AnyContainerException
     *          Raised in case the array conversion has failed
     */
    public function asArray();

    /**
     *  Force an object value or class instance
     *
     *  The asObject() method ensures that the held item is an instance
     *  of the given $class or, in case $class is NULL, is an object, at
     *  least.
     *
     *  @param  string              $class          The requested class
     *
     *  @return object
     *          An object instance is returned on success
     *
     *  @throws \Lousson\Container\AnyContainerException
     *          Raised in case the helt item is not an object or not an
     *          instance of the requested $class
     */
    public function asObject($class = null);

    /**
     *  Force a resource value
     *
     *  The asResource() method ensures that the held item is a resource
     *  descriptor before returning it.
     *
     *  @return resource
     *          A resource is returned on success
     *
     *  @throws \Lousson\Container\AnyContainerException
     *          Raised in case the held item is not a resource descriptor
     */
    public function asResource();

    /**
     *  Provide a specific item
     *
     *  @param  string              $name           The name of the item
     *
     *  @return \Lousson\Container\AnyContainerAggregate
     *           A dependency aggregate is returned on success
     */
    public function orGet($name);

    /**
     *  Provide a fallback item
     *
     *  The orFallback() method either returns the container aggregate
     *  instance it has been invoked on (in case the held item is not NULL)
     *  or some instance that represents the $fallback provided.
     *
     *  Note that the $fallback will be evaluated like any other container
     *  item: Closures will get invoked, provided with with the anchestor
     *  container as parameter, whilst aggregates will get passed through.
     *
     *  @param  mixed               $fallback           The fallback item
     *
     *  @return \Lousson\Container\AnyContainerAggregate
     *          A dependenvy aggregate is returned on success
     *
     *  @throws \Exception
     *          The $fallback, if invoked, may raise any exception
     */
    public function orFallback($fallback);

    /**
     *  Permit a NULL item
     *
     *  The orNull() method either returns the container aggregate instance
     *  it has been invoked on, in case the held item is not NULL, or some
     *  instance that returns NULL when an as*() method is invoked.
     *
     *  @return \Lousson\Container\AnyContainerAggregate
     *          A container aggregate is returend on success
     */
    public function orNull();
}

