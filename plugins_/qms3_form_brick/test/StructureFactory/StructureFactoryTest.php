<?php
declare(strict_types=1);

namespace QMS3\Brick\Test\StructureFactory;

use PHPUnit\Framework\TestCase;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\StructureFactory\StructureFactory;

require_once("vendor/autoload.php");


class StructureFactoryTest extends TestCase
{
    public function test_valid_な_Structure_Table_を_load_する()
    {
        $structure_table = require(__DIR__."/assets/valid_structure_table.php");

        $factory = new StructureFactory();
        $structure = $factory->create($structure_table);

        $this->assertInstanceOf(Structure::class, $structure);
    }

    public function test_invalid_な_Structure_Table_を_load_すると例外が投げられる()
    {
        $this->expectException(\RuntimeException::class);

        $structure_table = require(__DIR__."/assets/invalid_structure_table.php");

        $factory = new StructureFactory();
        $structure = $factory->create($structure_table);
    }
}
