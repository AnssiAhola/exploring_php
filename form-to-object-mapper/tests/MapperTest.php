<?php

use PHPUnit\Framework\TestCase;

final class MapperTest extends TestCase
{
    private Mapper $mapper;

    protected function setUp(): void
    {
        $builder = new MapperBuilder();
        $this->mapper = $builder
            ->configureWith(MapperConfiguration::makeDefault())
            ->build();
    }

    public function testMapperReturnsInstanceWithPropertiesPopulated(): void
    {
        $expected = new MyClass(123, "Lorem Ipsum", false, 0.0001);
        $actual = $this->mapper
            ->mapFrom([
                "foo" => 123,
                "bar" => "Lorem Ipsum",
                "baz" => false,
                "foofloat" => 0.0001,
            ])->mapTo(MyClass::class);

        $this->assertEquals($expected, $actual);
    }

    public function testMapperPopulatesExistingObjectWithValues(): void
    {
        $expected = new MyClass();
        $actual = $this->mapper
            ->mapFrom([
                "foo" => 42,
                "bar" => "Lorem Ipsum",
                "baz" => true,
                "foofloat" => 43.123,
            ])->mapTo($expected);

        $this->assertSame($expected, $actual);
        $this->assertSame(42, $actual->getFoo());
        $this->assertSame("Lorem Ipsum", $actual->getBar());
        $this->assertSame(true, $actual->getBaz());
        $this->assertSame(43.123, $actual->getFooFloat());
    }

    public function testMapperCastsAndSanitizes(): void
    {
        $expected = new MyClass(9001, "bar", true, 64.3940385);
        $actual = $this->mapper
            ->mapFrom([
                "foo" => "9001",
                "bar" => "<script>bar</script>",
                "baz" => 1,
                "foofloat" => "64.3940385",
            ])->mapTo(MyClass::class);

        $this->assertEquals($expected, $actual);
    }


    public function testMapFromGlobals(): void
    {
        $_POST = [
            "foo" => 9001.00001,
            "bar" => "<script>bar</script>",
            "baz" => "FALSE",
            "foofloat" => 64,
        ];

        $expected = new MyClass(9001, "bar", false, 64.0);
        $actual = $this->mapper
            ->mapFromGlobals()
            ->mapTo(MyClass::class);

        $this->assertEquals($expected, $actual);
    }


    public function testMapFromGlobalsWithSpecificKeysOnly(): void
    {
        $_POST = [
            "id" => PHP_INT_MAX,
            "foo" => "9001",
            "baz" => 1,
            "bar" => "bob",
            "some_other_field" => fn () => null,
            "foofloat" => 64.3940385,
            "irrelevant" => new stdClass,
        ];

        $expected = new MyClass(9001, "bob", true, 64.3940385);
        $actual = $this->mapper
            ->mapFromGlobals(
                only: ["foo", "bar", "baz", "foofloat"]
            )->mapTo(MyClass::class);

        $this->assertEquals($expected, $actual);
    }
}
