<?php

namespace Tests\Unit;

use LeaReift\OcrSpace\Support\Collection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Collection::class)]
class CollectionTest extends TestCase
{
    public function testConstructorWithNull(): void
    {
        $collection = new Collection(null);
        $this->assertEmpty($collection->toArray());
    }

    public function testConstructorWithArray(): void
    {
        $data = ['a' => 1, 'b' => 2];
        $collection = new Collection($data);
        $this->assertSame($data, $collection->toArray());
    }

    public function testMakeStaticMethod(): void
    {
        $data = ['c' => 3, 'd' => 4];
        $collection = Collection::make($data);
        $this->assertSame($data, $collection->toArray());
    }

    public function testIsEmpty(): void
    {
        $collection = new Collection();
        $this->assertTrue($collection->isEmpty());

        $collection = new Collection(['e' => 5]);
        $this->assertFalse($collection->isEmpty());
    }

    public function testIsNotEmpty(): void
    {
        $collection = new Collection();
        $this->assertFalse($collection->isNotEmpty());

        $collection = new Collection(['f' => 6]);
        $this->assertTrue($collection->isNotEmpty());
    }

    public function testGetIterator(): void
    {
        $data = ['g' => 7, 'h' => 8];
        $collection = new Collection($data);
        $iterator = $collection->getIterator();
        $this->assertInstanceOf(\ArrayIterator::class, $iterator);
    }

    public function testOffsetExists(): void
    {
        $data = ['i' => 9, 'j' => 10];
        $collection = new Collection($data);
        $this->assertTrue($collection->offsetExists('i'));
        $this->assertFalse($collection->offsetExists('k'));
        $this->assertFalse($collection->offsetExists(0)); // Non-existent numeric key
    }

    public function testGet(): void
    {
        $data = ['l' => 11, 0 => 12];
        $collection = new Collection($data);
        $this->assertSame(11, $collection->get('l'));
        $this->assertSame(12, $collection->get(0));
        $this->assertNull($collection->get('m'));
        $this->assertNull($collection->get(1));
    }

    public function testOffsetGet(): void
    {
        $data = ['n' => 13];
        $collection = new Collection($data);
        $this->assertSame(13, $collection->offsetGet('n'));
        $this->assertNull($collection->offsetGet('o'));
    }

    public function testOffsetSet(): void
    {
        $collection = new Collection();
        $collection->offsetSet('p', 14);
        $this->assertSame(['p' => 14], $collection->toArray());

        $collection->offsetSet(0, 15);
        $this->assertSame(['p' => 14, 0 => 15], $collection->toArray());

        $collection['q'] = 16;
        $this->assertSame(['p' => 14, 0 => 15, 'q' => 16], $collection->toArray());
    }

    public function testOffsetUnset(): void
    {
        $data = ['r' => 17, 0 => 18, 's' => 19];
        $collection = new Collection($data);
        unset($collection['r']);
        $this->assertSame([0 => 18, 's' => 19], $collection->toArray());

        $collection->offsetUnset(0);
        $this->assertSame(['s' => 19], $collection->toArray());

        $collection->offsetUnset('t'); // Unsetting non-existent key should not error
        $this->assertSame(['s' => 19], $collection->toArray());
    }

    public function testCount(): void
    {
        $collection = new Collection();
        $this->assertSame(0, $collection->count());

        $collection = new Collection(['u' => 20, 'v' => 21]);
        $this->assertSame(2, $collection->count());
    }

    public function testToArray(): void
    {
        $data = ['w' => 22, 'x' => 23];
        $collection = new Collection($data);
        $this->assertSame($data, $collection->toArray());
    }

    public function testKeys(): void
    {
        $data = ['y' => 24, 0 => 25, 'z' => 26];
        $collection = new Collection($data);
        $keysCollection = $collection->keys();
        $this->assertInstanceOf(Collection::class, $keysCollection);
        $this->assertSame(['y', 0, 'z'], $keysCollection->toArray());
    }

    public function testMap(): void
    {
        $data = [1, 2, 3];
        $collection = new Collection($data);
        $mappedCollection = $collection->map(fn ($value) => $value * 2);
        $this->assertInstanceOf(Collection::class, $mappedCollection);
        $this->assertSame([2, 4, 6], $mappedCollection->toArray());

        $dataWithKeys = ['a' => 1, 'b' => 2, 'c' => 3];
        $collectionWithKeys = new Collection($dataWithKeys);
        $mappedCollectionWithKeys = $collectionWithKeys->map(fn ($value, $key) => $value . $key);
        $this->assertSame(['1a', '2b', '3c'], $mappedCollectionWithKeys->toArray());
    }

    public function testFirst(): void
    {
        $collection = new Collection([27, 28, 29]);
        $this->assertSame(27, $collection->first());

        $collection = new Collection(['aa' => 30, 'bb' => 31]);
        $this->assertSame(30, $collection->first());

        $collection = new Collection();
        $this->assertNull($collection->first());
    }

    public function testMapIntoCollection(): void
    {
        $data = [['cc' => 32, 'dd' => 33], ['ee' => 34, 'ff' => 35]];
        $collection = new Collection($data);
        $mappedCollection = $collection->mapIntoCollection();
        $this->assertInstanceOf(Collection::class, $mappedCollection);
        $this->assertCount(2, $mappedCollection);
        $this->assertInstanceOf(Collection::class, $mappedCollection->get(0));
        $this->assertSame(['cc' => 32, 'dd' => 33], $mappedCollection->get(0)->toArray());
        $this->assertInstanceOf(Collection::class, $mappedCollection->get(1));
        $this->assertSame(['ee' => 34, 'ff' => 35], $mappedCollection->get(1)->toArray());
    }
}
