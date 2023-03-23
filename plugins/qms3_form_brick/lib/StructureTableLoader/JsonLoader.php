<?php
declare(strict_types=1);

namespace QMS3\Brick\StructureTableLoader;

use RuntimeException;
use QMS3\Brick\Util\HandleError;


class JsonLoader
{
    /**
     * @param     string    $filepath
     * @return    array<int,array>
     */
    public function load($filepath)
    {
        $json_str = HandleError::invoke("file_get_contents", $filepath);

        if ($json_str === false) {
            throw new RuntimeException("JSON ファイルを読み込めません。: \$filepath: {$filepath}");
        }

        $structure_table = json_decode($json_str, /* $assoc = */ true);

        if (!$structure_table) {
            // TODO: RuntimeException って適切？
            throw new RuntimeException("不正な形式の JSON ファイルです。");
        }

        return $structure_table;
    }
}
