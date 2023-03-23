<?php
declare(strict_types=1);

namespace QMS3\Brick\StructureTableLoader;

use SplFileObject;
use SplTempFileObject;
use QMS3\Brick\StructureTableLoader\CsvLoader\CsvHeaderDetector;


class CsvLoader
{
    /**
     * @param     string     $filepath
     * @return    array<int,array>
     */
    public function load($filepath)
    {
        $rows = $this->load_csv($filepath);

        $detector = new CsvHeaderDetector();
        $header = $detector->detect($rows[0]);

        array_shift($rows);
        return $this->assoc_keys($header, $rows);
    }

    /**
     * @param     string     $filepath
     * @return    array[]
     */
    private function load_csv($filepath)
    {
        $file = new SplFileObject($filepath, "rb");
        $temp = new SplTempFileObject();

        $bom = hex2bin("EFBBBF");

        // 入力文字列の文字コードをいったん SJIS に変換する
        // SplFileObject::READ_CSV で CSV をパースするとき、
        // 入力が UTF-8 だとカンマ区切りの分割が正常に行われないことがある
        //
        // この処理によって入力文字列の文字コードが UTF-8 でも SJIS でも
        // 統一的に取り扱えるようになる
        while (!$file->eof()) {
            $line = $file->fgets();

            if ($line === false) { continue; }

            $line = preg_replace("/^{$bom}/", "", $line);
            $line = mb_convert_encoding($line, "SJIS-win", ["UTF-8", "SJIS-win"]);

            $temp->fwrite($line);
        }

        $temp->seek(0);
        $temp->setFlags(SplFileObject::READ_CSV);

        $rows = [];
        foreach ($temp as $row) {
            // 空行を読み飛ばす
            $filtered_row = array_filter(array_map("trim", $row));
            if (empty($filtered_row)) { continue; }

            $converted = [];
            foreach ($row as $field) {
                $converted[] = mb_convert_encoding($field, "UTF-8", "SJIS-win");
            }

            $rows[] = $converted;
        }

        return $rows;
    }

    /**
     * @param     string[]    $keys
     * @param     array[]     $rows
     * @return    array[]
     */
    private function assoc_keys(array $keys, array $rows)
    {
        $assoced = [];
        foreach ($rows as $row) {
            $assoced[] = array_combine($keys, $row);
        }

        return $assoced;
    }
}
