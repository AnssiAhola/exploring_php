<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class CollectionTest extends TestCase
{

    public function testCanCount(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $this->assertCount(5, $collection);
    }

    public function testCanIterateWithFor(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $result = 0;
        for ($index = 0; $index < count($collection); $index++) {
            $result += $collection[$index];
        }
        $this->assertEquals(15, $result);
    }

    public function testCanIterateWithForEach(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $result = 0;
        foreach ($collection as $value) {
            $result += $value;
        }
        $this->assertEquals(15, $result);
    }

    public function testCanIterateWithWhile(): void
    {
        $collection = new Collection([1, 2, 3, 4, 5]);
        $result = 0;
        while ($collection->valid()) {
            $result += $collection->current();
            $collection->next();
        }
        $this->assertEquals(15, $result);
    }

    public function testContains(): void
    {
        $item1 = new stdClass;
        $item2 = new stdClass;
        $collection = new Collection([$item1, $item2]);

        $this->assertTrue($collection->contains($item1));
    }

    public function testDoesNotContain(): void
    {
        $item1 = new stdClass;
        $item2 = new stdClass;
        $collection = new Collection([$item1]);

        $this->assertFalse($collection->contains($item2));
    }

    public function testFindWhereMatchesCondition(): void
    {
        $collection = new Collection([4, 10, 13, 27, 120]);

        $found = $collection->findWhere(fn (int $val) => $val % 10 == 0);
        $this->assertCount(2, $found);
        $this->assertEquals(10, $found[0]);
        $this->assertEquals(120, $found[1]);
    }

    public function testCanAddItemOfSameTypeToExistingCollection(): void
    {
        $item1 = new stdClass;
        $item2 = new stdClass;
        $collection = new Collection([$item1]);
        $collection->add($item2);

        $this->assertTrue($collection->contains($item2));
    }

    public function testCannotAddItemOfDifferentTypeToExistingCollection(): void
    {
        $this->expectException(TypeError::class);

        $item1 = new stdClass;
        $collection = new Collection([$item1]);
        $collection->add(0.123);
    }

    public function testCannotAddItemOfDifferentTypeToExistingCollectionWithArrayAccess(): void
    {
        $this->expectException(TypeError::class);

        $item1 = new stdClass;
        $collection = new Collection([$item1]);
        $collection[] = 0.123;
    }

    public function testCanAddItemOfSubTypeToExistingCollection(): void
    {
        $item1 = new stdClass;
        $collection = new Collection([$item1]);
        $collection->add(new MyStdClass);
        $this->assertCount(2, $collection);
    }

    public function testCanAddItemOfSameBaseTypeToExistingCollection(): void
    {
        $item1 = new MyStdClass;
        $collection = new Collection([$item1]);
        $collection->add(new stdClass);
        $this->assertCount(2, $collection);
    }

    public function testCanAddCollectionOfItemsToExistingCollection(): void
    {
        $item1 = new stdClass;
        $collection = new Collection([$item1]);
        $collection->addAll(new Collection([new stdClass, new stdClass, new stdClass]));
        $collection->addAll([new stdClass, new stdClass]);
        $this->assertCount(6, $collection);
    }

    public function testCanRemoveAnItemFromCollection(): void
    {
        $item = new stdClass;
        $collection = new Collection([new stdClass, new stdClass, new stdClass, $item]);
        $collection->remove($item);
        $this->assertCount(3, $collection);
        $this->assertNotContains($item, $collection);
        $this->assertFalse($collection->offsetExists(3));
    }

    public function testCanRemoveArrayOfItemsFromCollection(): void
    {
        $item1 = new stdClass;
        $item2 = new stdClass;
        $collection = new Collection([new stdClass, $item2, new stdClass, new stdClass, $item1]);
        $collection->removeAll([$item1, $item2]);
        $this->assertCount(3, $collection);
    }

    public function testCanRemoveCollectionOfItemsFromCollection(): void
    {
        $item1 = new stdClass;
        $item2 = new stdClass;
        $collection = new Collection([new stdClass, $item2, new stdClass, new stdClass, $item1]);
        $collection->removeAll(new Collection([$item1, $item2]));
        $this->assertCount(3, $collection);
    }

    public function testCanRemoveWhereMatchesCondition(): void
    {
        $item1 = new MyStdClass(baz: true);
        $item2 = new MyStdClass(baz: true);
        $collection = new Collection([new MyStdClass(), $item2, new MyStdClass(), new MyStdClass(), $item1]);
        $collection->removeWhere(fn (MyStdClass $obj) => $obj->hasBaz());
        $this->assertFalse($collection->contains($item2));
        $this->assertFalse($collection->contains($item1));
    }

    public function testCanCanClearCollection(): void
    {
        $collection = new Collection([new MyStdClass(), new MyStdClass(), new MyStdClass()]);
        $collection->clear();
        $this->assertTrue($collection->isEmpty());
    }
}

class MyStdClass extends stdClass
{
    public function __construct(private bool $baz = false)
    {
    }

    public function hasBaz(): bool
    {
        return $this->baz;
    }
}
