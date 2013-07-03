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
 *  Lousson\Context\Callback\CallbackContextContainer class definition
 *
 *  @package    org.lousson.context
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Context\Callback;

/** Interfaces: */
use Lousson\Context\AnyContextContainer;
use Lousson\Context\AnyContextEnvelope;

/** Dependencies: */
use Lousson\Context\Generic\GenericContextEntity;
use Lousson\Context\Generic\GenericContextEnvelope;
use Closure;

/**
 *  A callback context container implementation
 *
 *  @since      lousson/Lousson_Context-0.1.0
 *  @package    org.lousson.context
 */
class CallbackContextContainer
    extends GenericContextEntity
    implements AnyContextContainer
{
    /**
     *  Create a container instance
     *
     *  The constructor requires the caller to provide a Closure $callback
     *  for the container to invoke whenever an item is requested that does
     *  not have been requested before.
     *
     *  @param  Closure             $callback       The container callback
     */
    public function __construct(Closure $callback)
    {
        $this->callback = $callback;
    }

    /**
     *  Obtain a context envelope
     *
     *  The get() method is used to obtain a context envelope instance
     *  for the item with the given $name. This envelope can then get used
     *  to fetch the actual value of the item.
     *
     *  @param  string              $name           The name of the item
     *
     *  @return \Lousson\Context\AnyContextEnvelope
     *          A context envelope is returend on success
     *
     *  @throws \Lousson\Context\AnyContextException
     *          Raised in case retrieving the item has failed
     */
    public function get($name)
    {
        $name = (string) $name;

        if (!isset($this->data[$name])) {
            $envelope = $this->agg($this, $name, $this->callback);
            $this->data[$name] = $envelope;
        }

        return $envelope;
    }

    /**
     *  The container's callback
     *
     *  @var \Closure
     */
    private $callback;

    /**
     *  The container's data
     *
     *  @var array
     */
    private $data = array();
}

