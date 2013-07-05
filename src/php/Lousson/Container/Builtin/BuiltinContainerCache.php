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
 *  Lousson\Container\Builtin\BuiltinContainerCache class definition
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

/**
 *  A caching container decorator
 *
 *  The Lousson\Container\Builtin\BuiltinContainerCache is a so-called
 *  decorator for instances of the AnyContainer interface that caches all
 *  aggregates returned by the get() method - in order to ensure that
 *  each invocation produces the exact same result.
 *
 *  @since      lousson/Lousson_Container-0.1.0
 *  @package    org.lousson.container
 */
class BuiltinContainerCache implements AnyContainer
{
    /**
     *  Create a cache instance
     *
     *  The constructor requires the caller to provide the $container
     *  instance to retrieve the aggregates to cache from.
     *
     *  @param  AnyContainer        $container      The container decorated
     */
    public function __construct(AnyContainer $container)
    {
        assert($container !== $this);
        $this->container = $container;
    }

    /**
     *  Obtain a container aggregate
     *
     *  The get() method is used to obtain a container aggregate instance
     *  for the item with the given $name. This aggregate can then get used
     *  to fetch the actual value of the item.
     *
     *  @param  string              $name           The name of the item
     *
     *  @return \Lousson\Container\AnyContainerAggregate
     *          A container aggregate is returend on success
     *
     *  @throws \Lousson\Container\AnyContainerException
     *          Raised in case retrieving the item has failed
     */
    public function get($name)
    {
        $name = (string) $name;

        if (!isset($this->cache[$name])) {
            $aggregate = $this->container->get($name);
            $this->cache[$name] = $aggregate;
        }
        else {
            $aggregate = $this->cache[$name];
        }

        return $aggregate;
    }

    /**
     *  The decorated container
     *
     *  @var \Lousson\Container\AnyContainer
     */
    private $container;

    /**
     *  The cache for aggregates
     *
     *  @var array
     */
    private $cache = array();
}

