<?php
declare(strict_types=1);

namespace QMS3\Brick\Test\OptionsParser;

use PHPUnit\Framework\TestCase;
use QMS3\Brick\OptionsParser\CheckboxOptionsParser;
use QMS3\Brick\OptionsParser\CheckboxOptionsParseResult;


class CheckboxOptionsParserTest extends TestCase
{
    public function test_基本的な_CheckboxOption_をパーズする()
    {
        $src = "
            value1=>label1
            value2 => label2
            label3
            =>label4
        ";

        $parser = new CheckboxOptionsParser();

        $parse_results = $parser->parse($src);


        foreach ($parse_results as $result) {
            $this->assertInstanceOf(CheckboxOptionsParseResult::class, $result);
        }

        $this->assertEquals(
            [
                [
                    "label"             => "label1",
                    "value"             => "value1",
                    "figure"            => null,
                    "extra_input_type"  => null,
                    "extra_input_name"  => null,
                    "extra_input_value" => null,
                ],
                [
                    "label"             => "label2",
                    "value"             => "value2",
                    "figure"            => null,
                    "extra_input_type"  => null,
                    "extra_input_name"  => null,
                    "extra_input_value" => null,
                ],
                [
                    "label"             => "label3",
                    "value"             => "label3",
                    "figure"            => null,
                    "extra_input_type"  => null,
                    "extra_input_name"  => null,
                    "extra_input_value" => null,
                ],
                [
                    "label"             => "label4",
                    "value"             => "",
                    "figure"            => null,
                    "extra_input_type"  => null,
                    "extra_input_name"  => null,
                    "extra_input_value" => null,
                ]
            ],
            array_map(function ($res) {
                return $res->to_array();
            }, $parse_results)
        );
    }

    public function test_extra_input_text_を含む_CheckboxOption_をパーズする()
    {
        $src = "
            value1=>label1[name1]
            value2 => label2 [ name2 ]
            label3[name3]
            =>label4[name4]
        ";

        $parser = new CheckboxOptionsParser();

        $parse_results = $parser->parse($src);


        foreach ($parse_results as $result) {
            $this->assertInstanceOf(CheckboxOptionsParseResult::class, $result);
        }

        $this->assertEquals(
            [
                [
                    "label"             => "label1",
                    "value"             => "value1",
                    "figure"            => null,
                    "extra_input_type"  => "TEXT",
                    "extra_input_name"  => "name1",
                    "extra_input_value" => null,
                ],
                [
                    "label"             => "label2",
                    "value"             => "value2",
                    "figure"            => null,
                    "extra_input_type"  => "TEXT",
                    "extra_input_name"  => "name2",
                    "extra_input_value" => null,
                ],
                [
                    "label"             => "label3",
                    "value"             => "label3",
                    "figure"            => null,
                    "extra_input_type"  => "TEXT",
                    "extra_input_name"  => "name3",
                    "extra_input_value" => null,
                ],
                [
                    "label"             => "label4",
                    "value"             => "",
                    "figure"            => null,
                    "extra_input_type"  => "TEXT",
                    "extra_input_name"  => "name4",
                    "extra_input_value" => null,
                ],
            ],
            array_map(function ($res) {
                return $res->to_array();
            }, $parse_results)
        );
    }

    public function test_extra_input_textarea_を含む_CheckboxOption_をパーズする()
    {
        $src = "
            value1=>label1[[name1]]
            value2 => label2 [[ name2 ]]
            label3[[name3]]
            =>label4[[name4]]
        ";

        $parser = new CheckboxOptionsParser();

        $parse_results = $parser->parse($src);


        foreach ($parse_results as $result) {
            $this->assertInstanceOf(CheckboxOptionsParseResult::class, $result);
        }

        $this->assertEquals(
            [
                [
                    "label"             => "label1",
                    "value"             => "value1",
                    "figure"            => null,
                    "extra_input_type"  => "TEXTAREA",
                    "extra_input_name"  => "name1",
                    "extra_input_value" => null,
                ],
                [
                    "label"             => "label2",
                    "value"             => "value2",
                    "figure"            => null,
                    "extra_input_type"  => "TEXTAREA",
                    "extra_input_name"  => "name2",
                    "extra_input_value" => null,
                ],
                [
                    "label"             => "label3",
                    "value"             => "label3",
                    "figure"            => null,
                    "extra_input_type"  => "TEXTAREA",
                    "extra_input_name"  => "name3",
                    "extra_input_value" => null,
                ],
                [
                    "label"             => "label4",
                    "value"             => "",
                    "figure"            => null,
                    "extra_input_type"  => "TEXTAREA",
                    "extra_input_name"  => "name4",
                    "extra_input_value" => null,
                ],
            ],
            array_map(function ($res) {
                return $res->to_array();
            }, $parse_results)
        );
    }

    public function test_figure_を含む_CheckboxOption_をパーズする()
    {
        $src = "
            value1=>[label1](figure1)
            value2 => [ label2 ] ( figure2 )
            [label3](figure3)
            =>[label4](figure4)
        ";

        $parser = new CheckboxOptionsParser();

        $parse_results = $parser->parse($src);


        foreach ($parse_results as $result) {
            $this->assertInstanceOf(CheckboxOptionsParseResult::class, $result);
        }

        $this->assertEquals(
            [
                [
                    "label"             => "label1",
                    "value"             => "value1",
                    "figure"            => "figure1",
                    "extra_input_type"  => null,
                    "extra_input_name"  => null,
                    "extra_input_value" => null,
                ],
                [
                    "label"             => "label2",
                    "value"             => "value2",
                    "figure"            => "figure2",
                    "extra_input_type"  => null,
                    "extra_input_name"  => null,
                    "extra_input_value" => null,
                ],
                [
                    "label"             => "label3",
                    "value"             => "label3",
                    "figure"            => "figure3",
                    "extra_input_type"  => null,
                    "extra_input_name"  => null,
                    "extra_input_value" => null,
                ],
                [
                    "label"             => "label4",
                    "value"             => "",
                    "figure"            => "figure4",
                    "extra_input_type"  => null,
                    "extra_input_name"  => null,
                    "extra_input_value" => null,
                ],
            ],
            array_map(function ($res) {
                return $res->to_array();
            }, $parse_results)
        );
    }


    public function test_src_の中の空行・空文字は無視される()
    {
        $src1 = "
            value1=>label1
            label2
            value3=>label3[name3]
            label4[name4]
            value5=>[label5](figure5)
            [label6](figure6)
            =>label7
        ";

        $src2 = "
            value1    =>    label1

            label2


            value3 =>label3          [name3]
            label4  [  name4]




            value5=> [label5  ](figure5  )

            [label6 ]           (  figure6)

                            =>                         label7
        ";


        $parser = new CheckboxOptionsParser();

        $parse_results1 = $parser->parse($src1);
        $parse_results2 = $parser->parse($src2);

        $this->assertEquals($parse_results1, $parse_results2);
    }
}
