<?php
declare(strict_types=1);

namespace QMS3\Brick\StructureTableLoader;

use RuntimeException;
use QMS3\Brick\Enum\StructureFormat;
use QMS3\Brick\Param\Param;
use QMS3\Brick\StructureTableLoader\CsvLoader;
use QMS3\Brick\StructureTableLoader\JsonLoader;
use QMS3\Brick\StructureTableLoader\PhpLoader;
use QMS3\Brick\StructureTableLoader\WordPressLoader;


class StructureTableLoader
{
    /**
     * @param     string    $form_type
     * @param     Param     $param
     * @return    array<int,array>
    */
    public function load($form_type, Param $param)
    {
        assert(
            trim($form_type) != false,
            "\$form_type は空文字であってはいけません"
        );

        $filename  = $param->structure_name;
        $ext       = $param->structure_ext;
        $filepath  = "{$param->structure_dir}/{$filename}.{$ext}";

        switch ($param->structure_format->value()) {
            case StructureFormat::CSV      : return $this->create_from_csv($filepath);
            case StructureFormat::JSON     : return $this->create_from_json($filepath);
            case StructureFormat::PHP      : return $this->create_from_php($filepath);
            case StructureFormat::WORDPRESS: return $this->create_from_wordpress($form_type);
            default: throw new RuntimeException("不明な structure_format が指定されています。structure_format は JSON, PHP, CSV のいずれかである必要があります");
        }
    }

    /**
     * @param     string    $filepath
     * @return    array<string,array>
     */
    public function create_from_csv($filepath)
    {
        $loader = new CsvLoader();
        return $loader->load($filepath);
    }

    /**
     * @param     string    $filepath
     * @return    array<string,array>
     */
    public function create_from_json($filepath)
    {
        $loader = new JsonLoader();
        return $loader->load($filepath);
    }

    /**
     * @param     string    $filepath
     * @return    array<string,array>
     */
    public function create_from_php($filepath)
    {
        $loader = new PhpLoader();
        return $loader->load($filepath);
    }

    /**
     * @param     string    $slug
     * @return    array<string,array>
     */
    public function create_from_wordpress($slug)
    {
        $loader = new WordPressLoader();
        return $loader->load($slug);
    }
}
