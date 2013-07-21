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
 *  Lousson\Container\Builtin\BuiltinContainerLoader class definition
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

/** Dependencies: */
use Lousson\Container\Generic\GenericContainerDecorator;
use Lousson\Container\Generic\GenericContainer;

/** Exceptions: */
use Lousson\Container\Error\ContainerRuntimeError;

/**
 *  A container loader
 *
 *  The Lousson\Container\Builtin\BuiltinContainerLoader is a utiltiy
 *  for the creation and provisioning of generic container instances via
 *  PHP files.
 *
 *  @since      lousson/Lousson_Container-0.1.0
 *  @package    org.lousson.container
 */
class BuiltinContainerLoader
{
    /**
     *  Load a container
     *
     *  The loadContainer() method creates a GenericContainer instance and
     *  includes each file matching the given $glob - passing a $container
     *  variable to reference the container object.
     *
     *  The optional $base parameter can be used to trigger the creation of
     *  a GenericContainerDecorator instead of the plain container.
     *
     *  @param  string              $glob           The pattern to include
     *  @param  AnyContainer        $base           The base container
     *
     *  @return \Lousson\Container\Generic\GenericContainer
     *          A generic container instance is returned on success
     *
     *  @throws \Lousson\Container\Error\ContainerRuntimeError
     *          Raised in case provisioning the container has failed
     */
    public function loadContainer($glob, AnyContainer $base = null)
    {
        $flags = GLOB_NOSORT | GLOB_NOCHECK | GLOB_NOESCAPE | GLOB_BRACE;
        $files = glob($glob, $flags);
        $files = array_map("realpath", $files);
        $files = array_filter($files, "is_file");
        $files = array_filter($files, "is_readable");
        $files = array_unique($files);

        if (isset($base)) {
            $container = new GenericContainerDecorator($base);
        }
        else {
            $container = new GenericContainer;
        }

        foreach ($files as $path) try {
            $this->bindContainer($path, $container);
        }
        catch (\Exception $error) {
            $class = get_class($error);
            $message = "Could not load container: Caught $class";
            $code = ContainerRuntimeError::E_UNKNOWN;
            throw new ContainerRuntimeError($message, $code, $error);
        };

        return $container;
    }

    /**
     *  Bind a container
     *
     *  The bindContainer() method is used internally as a closure to
     *  include the file at the given $path.
     *
     *  @param  string              $path           The path to include
     *  @param  GenericContainer    $container      The container to set up
     *
     *  @throws \Exception
     *          Raised in case something went wrong
     */
    private function bindContainer($path, GenericContainer $container)
    {
        return include $path;
    }
}

