<?php
declare(strict_types=1);

namespace QMS3\Brick\Test\StructureTableLoader;

use PHPUnit\Framework\TestCase;
use QMS3\Brick\StructureTableLoader\JsonLoader;

require_once("vendor/autoload.php");


class JsonLoaderTest extends TestCase
{
    public function test_JSON_形式の_Structure_Table_を読み込む()
    {
        $loader = new JsonLoader();
        $structure_table = $loader->load(__DIR__."/assets/valid_structure_table.json");

        $this->assertIsArray($structure_table);

        foreach ($structure_table as $structure_table_row) {
            $this->assertIsArray($structure_table_row);

            foreach ($structure_table_row as $key => $field) {
                $this->assertIsString($key);
            }
        }
    }

    public function test_存在していないファイルを読もうとすると例外が投げられる()
    {
        $this->expectException(\Exception::class);

        $loader = new JsonLoader();
        $structure_table = $loader->load(__DIR__."/assets/not_exists_structure_table.json");
    }
}
