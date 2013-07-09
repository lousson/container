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
 *  Lousson\Container\Callback\CallbackContainerDecorator class definition
 *
 *  @package    org.lousson.container
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Container\Callback;

/** Interfaces: */
use Lousson\Container\AnyContainer;

/** Dependencies: */
use Lousson\Container\Generic\GenericContainerEntity;
use Closure;

/**
 *  A callback container decorator
 *
 *  The Lousson\Callback\CallbackContainerDecorator class is a so-called
 *  decorator for instances of the AnyContainer interface.
 *
 *  It operates with a callback closure (that must provide the same API as
 *  all container callbacks) provided at construction time, used to wrap
 *  the decorated container when the get() method is invoked - just as if
 *  the requested item is NULL and the callback would have been passed to
 *  the orFallback() method.
 *  This allows authors to implement item filtering, transformations and
 *  other aggregations in a very generic fashion; context-sensitive and at
 *  runtime.
 *
 *  @since      lousson/Lousson_Container-0.1.0
 *  @package    org.lousson.container
 */
class CallbackContainerDecorator
    extends GenericContainerEntity
    implements AnyContainer
{
    /**
     *  Create a decorator instance
     *
     *  Beside the $container to decorate, the constructor requires the
     *  caller to provide a Closure $callback for the decorator to invoke
     *  whenever an item is requested.
     *
     *  @param  AnyContainer        $container      The container decorated
     *  @param  Closure             $callback       The container callback
     */
    public function __construct(AnyContainer $container, Closure $callback)
    {
        assert($container !== $this);

        $this->container = $container;
        $this->callback = $callback;
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
        $aggregate = $this->aggregate(
            $this->container, $name, $this->callback
        );

        return $aggregate;
    }

    /**
     *  The decorated container
     *
     *  @var \Lousson\Container\AnyContainer
     */
    private $container;

    /**
     *  The decorator callback
     *
     *  @var \Closure
     */
    private $callback;
}

