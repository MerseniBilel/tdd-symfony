<?php
declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Dinosaur;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class DinosaurTest extends TestCase
{

    #[dataProvider('sizeDescriptionProvider')]
    function testDinosaurSizeDescription(int $length, string $expectedDescription ): void
    {
        $dino = new Dinosaur(name:'Testasaurus', length: $length);
        $this->assertSame($expectedDescription, $dino->getSizeDescription(), 'Dino suppose to be a large dinosaur');
    }


    public static function sizeDescriptionProvider(): \Iterator
    {
        yield '4 Meter Small Dino' => [4, 'Small'];
        yield '6 Meter Medium Dino' => [6, 'Medium'];
        yield '15 Meter Large Dino' => [15, 'Large'];
    }

}