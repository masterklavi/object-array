<?php

class ObjectArrayTest extends PHPUnit_Framework_TestCase
{
    static protected $invalid = [];

    static function setUpBeforeClass()
    {
        require_once '../src/ObjectArray.php';
        ObjectArrayTest::$invalid = [
            0, 1.1, true, null, [2], 'string', fopen('php://stdin', 'r')
        ];
    }

    /**
     * @covers ObjectArray::offsetExists
     */
    public function testOffsetExists()
    {
        $array = new ObjectArray();

        foreach (ObjectArrayTest::$invalid as $value)
        {
            $this->assertFalse($array->offsetExists($value));
            $this->assertFalse(isset($array[$value]));
        }

        $d = new stdClass();
        $d->age = 36;
        $this->assertFalse($array->offsetExists($d));
        $this->assertFalse(isset($array[$d]));

        $array[$d] = 'Age: 36';
        $this->assertTrue($array->offsetExists($d));
        $this->assertTrue(isset($array[$d]));

        $e = new stdClass();
        $e->age = 36;
        $this->assertFalse($array->offsetExists($e));
        $this->assertFalse(isset($array[$e]));
    }

    /**
     * @covers ObjectArray::offsetGet
     */
    public function testOffsetGet()
    {
        $array = new ObjectArray();

        foreach (ObjectArrayTest::$invalid as $value)
        {
            $this->assertNull($array->offsetGet($value));
            $this->assertNull($array[$value]);
        }

        $d = new stdClass();
        $d->age = 36;
        $this->assertNull($array->offsetGet($d));
        $this->assertNull($array[$d]);

        $array[$d] = 'Age: 36';
        $this->assertNotNull($array->offsetGet($d));
        $this->assertNotNull($array[$d]);
        $this->assertEquals('Age: 36', $array->offsetGet($d));
        $this->assertEquals('Age: 36', $array[$d]);

        $e = new stdClass();
        $e->age = 36;
        $this->assertNull($array->offsetGet($e));
        $this->assertNull($array[$e]);
    }

    /**
     * @covers ObjectArray::offsetSet
     */
    public function testOffsetSet()
    {
        $array = new ObjectArray();

        $this->assertEquals(0, $array->size());
        foreach (ObjectArrayTest::$invalid as $value)
        {
            $array->offsetSet($value, 'a test value');
        }
        $this->assertEquals(0, $array->size());

        $this->assertEquals(0, $array->size());
        foreach (ObjectArrayTest::$invalid as $value)
        {
            $array[$value] = 'a test value';
        }
        $this->assertEquals(0, $array->size());

        $d = new stdClass();
        $d->age = 36;
        $this->assertFalse(isset($array[$d]));
        $this->assertNull($array[$d]);

        $array[$d] = 'Age: 36';
        $this->assertTrue(isset($array[$d]));
        $this->assertEquals('Age: 36', $array[$d]);

        $array[$d] = 'Age: 36+';
        $this->assertEquals('Age: 36+', $array[$d]);

        $array[$d] = 36;
        $this->assertEquals(36, $array[$d]);

        $e = new stdClass();
        $e->age = 36;
        $this->assertFalse(isset($array[$e]));
        $this->assertNull($array[$e]);

        foreach (ObjectArrayTest::$invalid as $value)
        {
            $array->offsetSet($e, $value);
            $this->assertEquals($value, $array->offsetGet($e));
        }
        foreach (ObjectArrayTest::$invalid as $value)
        {
            $array[$e] = $value;
            $this->assertEquals($value, $array[$e]);
        }
    }

    /**
     * @covers ObjectArray::offsetUnset
     */
    public function testOffsetUnset()
    {
        $array = new ObjectArray();

        $d = new stdClass();
        $d->age = 36;
        $array[$d] = 'Age: 36';

        $this->assertEquals(1, $array->size());
        foreach (ObjectArrayTest::$invalid as $value)
        {
            $array->offsetUnset($value);
            unset($array[$value]);
        }
        $this->assertEquals(1, $array->size());

        $e = new stdClass();
        $e->age = 36;
        $array->offsetUnset($e);
        unset($array[$e]);
        $this->assertEquals(1, $array->size());

        $array->offsetUnset($d);
        $this->assertEquals(0, $array->size());

        $array[$d] = 'Age: 36';
        $this->assertEquals(1, $array->size());

        unset($array[$d]);
        $this->assertEquals(0, $array->size());
    }

    /**
     * @covers ObjectArray::current
     * @covers ObjectArray::key
     * @covers ObjectArray::next
     * @covers ObjectArray::rewind
     * @covers ObjectArray::valid
     */
    public function test_Foreach()
    {
        $array = new ObjectArray();

        $a = new stdClass();
        $a->name = 'John';
        $array[$a] = 'Name: John';

        $b = new stdClass();
        $b->name = 'Nancy';
        $array[$b] = 'Name: Nancy';

        $c = new stdClass();
        $c->name = 'Smith';
        $array[$c] = 'Name: Smith';

        $names = [];
        $values = [];

        foreach ($array as $key => $value)
        {
            $names[] = $key->name;
            $values[] = $value;
        }

        $this->assertEquals(['John', 'Nancy', 'Smith'], $names);
        $this->assertEquals(['Name: John', 'Name: Nancy', 'Name: Smith'], $values);
    }

    /**
     * @covers ObjectArray::size
     */
    public function testSize()
    {
        $array = new ObjectArray();

        $this->assertEquals(0, $array->size());

        $d = new stdClass();
        $d->age = 36;
        $array[$d] = 'Age: 36';

        $this->assertEquals(1, $array->size());
    }
}
