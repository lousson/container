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
 *  Lousson\Context\AbstractContextEnvelopeTest class definition
 *
 *  @package    org.lousson.context
 *  @copyright  (c) 2013, The Lousson Project
 *  @license    http://opensource.org/licenses/bsd-license.php New BSD License
 *  @author     Mathias J. Hennig <mhennig at quirkies.org>
 *  @filesource
 */
namespace Lousson\Context;

/** Dependencies: */
use Lousson\Context\AbstractContextTest;

/**
 *  Abstract test case for AnyContextEnvelope implementations
 *
 *  @since      lousson/Lousson_Context-0.1.0
 *  @package    org.lousson.context
 */
abstract class AbstractContextEnvelopeTest extends AbstractContextTest
{
    /**
     *  Obtain the envelope instance to test
     *
     *  The getEnvelope() method returns the context envelope instance used
     *  in the tests, representing the $item provided.
     *
     *  @param  mixed               $item           The item to represent
     *
     *  @return \Lousson\Context\AnyContextEnvelope
     *          A context envelope instance is returned on success
     */
    abstract public function getEnvelope($item);

    /**
     *  Data provider to the testAsIs() method
     *
     *  The provideTestAsIsParameters() method is a data provider for
     *  testAsIs(), delivering hard-coded test data by default.
     *
     *  @return array
     *          A list of test parameters is returned on success
     */
    public function provideTestAsIsParameters()
    {
        $parms[] = array(true, true);
        $parms[] = array(123, 123);
        $parms[] = array(-3.14, -3.14);
        $parms[] = array("foobar", "foobar");
        $parms[] = array(array("baz"), array("baz"));
        $parms[] = array(STDERR, STDERR);

        return $parms;
    }

    /**
     *  Data provider to the testAsBool() method
     *
     *  The provideTestAsBoolParameters() method is a data provider for
     *  testAsBool(), delivering hard-coded test data by default.
     *
     *  @return array
     *          A list of test parameters is returned on success
     */
    public function provideTestAsBoolParameters()
    {
        $parms[] = array(true, true);
        $parms[] = array(false, false);
        $parms[] = array(123, true);
        $parms[] = array(-321, true);
        $parms[] = array(0, false);
        $parms[] = array(0.1234, true);
        $parms[] = array(-3.14, true);
        $parms[] = array("test", true);

        return $parms;
    }

    /**
     *  Data provider to the testAsInt() method
     *
     *  The provideTestAsIntParameters() method is a data provider for
     *  testAsInt(), delivering hard-coded test data by default.
     *
     *  @return array
     *          A list of test parameters is returned on success
     */
    public function provideTestAsIntParameters()
    {
        $parms[] = array(true, 1);
        $parms[] = array(false, 0);
        $parms[] = array(123, 123);
        $parms[] = array(-321, -321);
        $parms[] = array(0, 0);
        $parms[] = array(0.1234, 0);
        $parms[] = array(-3.14, -3);
        $parms[] = array("test", 0);

        return $parms;
    }

    /**
     *  Data provider to the testAsFloat() method
     *
     *  The provideTestAsFloatParameters() method is a data provider for
     *  testAsFloat(), delivering hard-coded test data by default.
     *
     *  @return array
     *          A list of test parameters is returned on success
     */
    public function provideTestAsFloatParameters()
    {
        $parms[] = array(true, 1.0);
        $parms[] = array(false, 0.0);
        $parms[] = array(123, 123.0);
        $parms[] = array(-321, -321.0);
        $parms[] = array(0, 0.0);
        $parms[] = array(0.1234, 0.1234);
        $parms[] = array(-3.14, -3.14);
        $parms[] = array("test", 0.0);

        return $parms;
    }

    /**
     *  Data provider to the testAsString() method
     *
     *  The provideTestAsStringParameters() method is a data provider for
     *  testAsString(), delivering hard-coded test data by default.
     *
     *  @return array
     *          A list of test parameters is returned on success
     */
    public function provideTestAsStringParameters()
    {
        $parms[] = array(true, "1");
        $parms[] = array(false, "");
        $parms[] = array(123, "123");
        $parms[] = array(-321, "-321");
        $parms[] = array(0, "0");
        $parms[] = array(0.1234, "0.1234");
        $parms[] = array(-3.14, "-3.14");
        $parms[] = array("test", "test");

        $object = $this->getMock("StdClass", array("__toString"));
        $object
            ->expects($this->any())
            ->method("__toString")
            ->will($this->returnValue(__METHOD__));

        $parms[] = array($object, __METHOD__);
        return $parms;
    }

    /**
     *  Data provider to the testAsArray() method
     *
     *  The provideTestAsArrayParameters() method is a data provider for
     *  testAsArray(), delivering hard-coded test data by default.
     *
     *  @return array
     *          A list of test parameters is returned on success
     */
    public function provideTestAsArrayParameters()
    {
        $parms[] = array(array(), array());
        $parms[] = array(array(1, 2, 3), array(1, 2, 3));
        $parms[] = array(array("foo" => "bar"), array("foo" => "bar"));
        $parms[] = array(new \ArrayIterator(array("baz")), array("baz"));

        return $parms;
    }

    /**
     *  Data provider to the testAsObject() method
     *
     *  The provideTestAsObjectParameters() method is a data provider for
     *  testAsObject(), delivering hard-coded test data by default.
     *
     *  @return array
     *          A list of test parameters is returned on success
     */
    public function provideTestAsObjectParameters()
    {
        $object = new \StdClass();
        $parms[] = array($object, $object, "StdClass");

        $parms[] = array($this, $this, "PHPUnit_Framework_TestCase");
        $parms[] = array($this, $this, get_class($this));

        return $parms;
    }

    /**
     *  Data provider to the testAsResource() method
     *
     *  The provideTestAsResourceParameters() method is a data provider for
     *  testAsResource(), delivering hard-coded test data by default.
     *
     *  @return array
     *          A list of test parameters is returned on success
     */
    public function provideTestAsResourceParameters()
    {
        $parms[] = array(STDERR, STDERR);
        return $parms;
    }

    /**
     *  Data provider to the testOrFallback() method
     *
     *  The provideTestOrFallbackParameters() method is a data provider for
     *  testOrFallback(), delivering hard-coded test data by default.
     *
     *  @return array
     *          A list of test parameters is returned on success
     */
    public function provideTestOrFallbackParameters()
    {
        $data = $this->provideTestAsIsParameters();
        $parms = array();

        foreach ($data as $proto) {
            $parms[] = array(null, $proto[0], $proto[1]);
        }

        return $parms;
    }

    /**
     *  Data provider to the testOrNull() method
     *
     *  The provideTestOrNullParameters() method is a data provider for
     *  testOrNull(), delivering hard-coded test data by default.
     *
     *  @return array
     *          A list of test parameters is returned on success
     */
    public function provideTestOrNullParameters()
    {
        $parms[] = array(null, "asIs");
        $parms[] = array(null, "asBool");
        $parms[] = array(null, "asInt");
        $parms[] = array(null, "asFloat");
        $parms[] = array(null, "asString");
        $parms[] = array(null, "asArray");
        $parms[] = array(null, "asResource");

        return $parms;
    }

    /**
     *  Test the asIs() method
     *
     *  The testAsIs() method is a test case for the envelope's
     *  asIs() method.
     *
     *  @param  mixed               $item           The input item
     *  @param  mixed               $expected       The expected value
     *
     *  @dataProvider               provideTestAsIsParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testAsIs($item, $expected)
    {
        $envelope = $this->getEnvelope($item);
        $this->invokeAs($envelope, "asIs", $expected);
    }

    /**
     *  Test the asBool() method
     *
     *  The testAsBool() method is a test case for the envelope's
     *  asBool() method.
     *
     *  @param  mixed               $item           The input item
     *  @param  bool                $expected       The expected value
     *
     *  @dataProvider               provideTestAsBoolParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testAsBool($item, $expected)
    {
        $envelope = $this->getEnvelope($item);
        $this->invokeAs($envelope, "asBool", $expected);
    }

    /**
     *  Test the asInt() method
     *
     *  The testAsInt() method is a test case for the envelope's
     *  asInt() method.
     *
     *  @param  mixed               $item           The input item
     *  @param  int                 $expected       The expected value
     *
     *  @dataProvider               provideTestAsIntParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testAsInt($item, $expected)
    {
        $envelope = $this->getEnvelope($item);
        $this->invokeAs($envelope, "asInt", $expected);
    }

    /**
     *  Test the asFloat() method
     *
     *  The testAsFloat() method is a test case for the envelope's
     *  asFloat() method.
     *
     *  @param  mixed               $item           The input item
     *  @param  float               $expected       The expected value
     *
     *  @dataProvider               provideTestAsFloatParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testAsFloat($item, $expected)
    {
        $envelope = $this->getEnvelope($item);
        $this->invokeAs($envelope, "asFloat", $expected);
    }

    /**
     *  Test the asString() method
     *
     *  The testAsString() method is a test case for the envelope's
     *  asString() method.
     *
     *  @param  mixed               $item           The input item
     *  @param  string              $expected       The expected value
     *
     *  @dataProvider               provideTestAsStringParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testAsString($item, $expected)
    {
        $envelope = $this->getEnvelope($item);
        $this->invokeAs($envelope, "asString", $expected);
    }

    /**
     *  Test the asArray() method
     *
     *  The testAsArray() method is a test case for the envelope's
     *  asArray() method.
     *
     *  @param  mixed               $item           The input item
     *  @param  array               $expected       The expected value
     *
     *  @dataProvider               provideTestAsArrayParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testAsArray($item, array $expected)
    {
        $envelope = $this->getEnvelope($item);
        $this->invokeAs($envelope, "asArray", $expected);
    }

    /**
     *  Test the asObject() method
     *
     *  The testAsObject() method is a test case for the envelope's
     *  asObject() method.
     *
     *  @param  mixed               $item           The input item
     *  @param  resource            $expected       The expected value
     *  @param  string              $class          The class name
     *
     *  @dataProvider               provideTestAsObjectParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testAsObject($item, $expected, $class)
    {
        $envelope = $this->getEnvelope($item);
        $this->invokeAs($envelope, "asObject", $expected);

        $object = $envelope->asObject($class);
        $constraint = sprintf(
            "The %s::asObject() method must return the expected object ".
            "instance", get_class($envelope)
        );

        $this->assertEquals($expected, $object, $constraint);
    }

    /**
     *  Test the asResource() method
     *
     *  The testAsResource() method is a test case for the envelope's
     *  asResource() method.
     *
     *  @param  mixed               $item           The input item
     *  @param  resource            $expected       The expected value
     *
     *  @dataProvider               provideTestAsResourceParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testAsResource($item, $expected)
    {
        $envelope = $this->getEnvelope($item);
        $this->invokeAs($envelope, "asResource", $expected);
    }

    /**
     *  Test the orFallback() method
     *
     *  The testOrFallback() method is a test case for the envelope's
     *  orFallback() method.
     *
     *  @param  mixed               $item           The input item
     *  @param  mixed               $fallback       The fallback
     *  @param  mixed               $expected       The expected value
     *
     *  @dataProvider               provideTestOrFallbackParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testOrFallback($item, $fallback, $expected)
    {
        $envelope = $this->getEnvelope($item);
        $value = $envelope->orFallback($fallback)->asIs();
        $constraint = sprintf(
            "The invocation of %s::orFallback()->asIs() must return ".
            "the expected fallback value", get_class($envelope)
        );

        $this->assertEquals($expected, $value, $constraint);
    }

    /**
     *  Test the orNull() method
     *
     *  The testOrNull() method is a test case for the envelope's
     *  orNull() method.
     *
     *  @param  mixed               $item           The input item
     *  @param  string              $method         The method to invoke
     *
     *  @dataProvider               provideTestOrNullParameters
     *  @test
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    public function testOrNull($item, $method)
    {
        $envelope = $this->getEnvelope($item);
        $value = $envelope->orNull()->$method();
        $constraint = sprintf(
            "The invocation %s::orNull()->%s() must return NULL",
            get_class($envelope), $method
        );

        $this->assertNull($value, $constraint);
    }

    /**
     *  Invoke an as*() method
     *
     *  The invokeAs() method is used internally to invoke the $method
     *  provided by the $envelope implementation, verifying the result
     *  matches the $expected value (if any).
     *
     *  @param  AnyContextEnvelope  $envelope       The envelope to use
     *  @param  string              $method         The method to invoke
     *  @param  mixed               $expected       The expected value
     *
     *  @throws \PHPUnit_Framework_AssertionFailedError
     *          Raised in case an assertion has failed
     *
     *  @throws \Exception
     *          Raised in case of an internal error
     */
    protected function invokeAs(
        AnyContextEnvelope $envelope,
        $method,
        $expected = null
    ) {
        $value = $envelope->$method();

        if (3 <= func_num_args()) {
            $this->assertEquals(
                $expected, $value, sprintf(
                "The %s::%s() method must return the expected value",
                get_class($envelope), $method
            ));
        }

        return $value;
    }
}

