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
 *  Lousson\Container\Generic\GenericContainer class definition
 *
 *  @package    org.lousson.container
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Container\Generic;

/** Interfaces: */
use Lousson\Container\AnyContainer;
use Lousson\Container\AnyContainerAggregate;

/** Dependencies: */
use Lousson\Container\Generic\GenericContainerAggregate;
use Lousson\Container\Generic\GenericContainerEntity;

/** Exceptions: */
use Lousson\Container\Error\ContainerRuntimeError;

/**
 *  A generic container implementation
 *
 *  @since      lousson/Lousson_Container-0.1.0
 *  @package    org.lousson.container
 */
class GenericContainer
    extends GenericContainerEntity
    implements AnyContainer
{
    /**
     *  Create a container instance
     *
     *  The constructor allows the caller to provide an array of default
     *  $data for the newly created container instance, where each item is
     *  treaten as if it would have been assigned using the set() method.
     *
     *  @param  array               $data           The default data
     */
    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     *  Update a container association
     *
     *  The set() method is used to setup the $value provided to be
     *  associated with the given $name. Note that some $value types will
     *  trigger special behavior:
     *
     *- Closures
     *  Any Closure callback is assumed to be some sort of factory for the
     *  item to aggregate when get() is invoked. It is expected to support
     *  being provided with the container itself as first, the passed name
     *  as second argument and will get invoked whenever the get() method
     *  is invoked with the associated $name
     *
     *- Lousson\Container\AnyContainerAggregate
     *  Any aggregation instance is assumed to be final. When retrieved
     *  via get(), it won't get wrapped in another aggregate
     *
     *  @param  string              $name           The name of the item
     *  @param  mixed               $value          The value to assign
     */
    public function set($name, $value)
    {
        $name = (string) $name;
        $this->data[$name] = $value;
    }

    /**
     *  Update a container association
     *
     *  A Closure $value assigned using the share() method is executed
     *  only during the first invocation of get() using the same $name.
     *  Afterwards, get() will always return an aggregate of the value
     *  returned by the closure during that first invocation.
     *
     *  All other $value types, however, are handled exactly like if they
     *  would have been provided via set().
     *
     *  @param  string              $name           The name of the item
     *  @param  mixed               $value          The value to share
     */
    public function share($name, $value)
    {
        if ($value instanceof \Closure) {
            $value = function($self, $name) use ($value) {
                $value = $value($self, $name);
                $agg = new GenericContainerAggregate($self, $name, $value);
                $self->set($name, $agg);
                return $agg;
            };
        }

        $this->set($name, $value);
    }

    /**
     *  Update a container association
     *
     *  The protect() method is much like set(), except that any special
     *  treatment of e.g. Closures or aggregates is disabled. This allows
     *  the provisioning of raw object aggregations, without the use of
     *  any builtin feature.
     *
     *  @param  string              $name           The name of the item
     *  @param  mixed               $value          The value to protect
     */
    public function protect($name, $value)
    {
        if ($value instanceof \Closure ||
                $value instanceof AnyContainerAggregate) {
            $value = new GenericContainerAggregate($this, $name, $value);
        }

        $this->set($name, $value);
    }

    /**
     *  Alias a container item
     *
     *  @param  string              $name           The name of the item
     *  @param  array               $aliases        The aliases to apply
     */
    public function alias($name, array $aliases)
    {
        $name = (string) $name;

        $callback = function($container, $void) use ($name) {
            return $container->get($name);
        };

        foreach ($aliases as $value) {
            $this->set($value, $callback);
        }
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
        $value = @$this->data[$name];

        if (!isset($value)) {
            $value = $this->aggregate($this, $name, null);
            $this->data[$name] = $value;
        }
        elseif ($value instanceof \Closure) {
            $value = $this->aggregate($this, $name, $value);
        }
        else if (!$value instanceof AnyContainerAggregate) {
            $value = $this->aggregate($this, $name, $value);
            $this->data[$name] = $value;
        }

        return $value;
    }

    /**
     *  The container's data, if any
     *
     *  @var array
     */
    private $data;
}

