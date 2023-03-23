<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Settings;

use RuntimeException;
use ReflectionMethod;
use WP_Query;
use QMS3\BrickAdmin\Exception\UnknownKeyException;


/**
 * @property    array<int,array>    $form_structure
 */
class FormStructureSettings
{
    /** @var    int */
    private $post_id;

    /** @var    array<int,array> */
    private $form_structure;

    /**
     * @param    int                 $post_id
     * @param    array<int,array>    $form_structure
     */
    private function __construct($post_id, $form_structure)
    {
        $this->post_id        = $post_id;
        $this->form_structure = $form_structure;
    }

    /**
     * @param     string    $name
     * @return    mixed
     */
    public function __get($name)
    {
        $method_name = "_get__{$name}";

        if (!method_exists($this, $method_name)) {
            throw new UnknownKeyException($name);
        }

        $method_ref = new ReflectionMethod($this, $method_name);
        if (!$method_ref->isProtected()) {
            throw new UnknownKeyException($name);
        }

        return call_user_func([$this, $method_name]);
    }

    /**
     * @param     string    $name
     * @param     mixed     $value
     * @return    void
     */
    public function __set($name, $value)
    {
        $method_name = "_set__{$name}";

        if (!method_exists($this, $method_name)) {
            throw new UnknownKeyException($name);
        }

        $method_ref = new ReflectionMethod($this, $method_name);
        if (!$method_ref->isProtected()) {
            throw new UnknownKeyException($name);
        }

        call_user_func([$this, $method_name], $value);
    }

    // ====================================================================== //

    /**
     * @return    array[]
     */
    protected function _get__form_structure()
    {
        return $this->form_structure;
    }

    /**
     * @param     mixed    $value
     * @return    void
     */
    protected function _set__form_structure($value)
    {
        if (is_null($value)) { $value = self::default_form_structure(); }

        $this->form_structure = $this->sanitize($value);
    }

    // ====================================================================== //

    /**
     * @param     int|string    $post_id
     * @return    FormStructureSettings
     */
    public static function get($post_id)
    {
        if (!function_exists("get_post_meta")) {
            throw new RuntimeException("WordPress が読み込まれていません。");
        }

        if (!is_numeric($post_id)) { $post_id = self::find_post_id($post_id); }


        $form_structure = get_post_meta($post_id, "form_structure", /* $single = */ true);

        if ($form_structure === "") { $form_structure = self::default_form_structure(); }

        return new self($post_id, $form_structure);
    }

    /**
     * @return    array<string,bool>
     */
    public function save()
    {
        $results = [];

        $results["form_structure"] = update_post_meta(
            $this->post_id,
            "form_structure",
            $this->form_structure
        );

        return $results;
    }

    // ====================================================================== //

    /**
     * @return    array[]
     */
    private static function default_form_structure()
    {
        return [];
    }

    /**
     * @param     string    $slug
     * @return    int
     */
    private static function find_post_id($slug)
    {
        if (!class_exists("WP_Query")) {
            throw new RuntimeException("WordPress が読み込まれていません。");
        }

        if (trim($slug) == false) {
            throw new RuntimeException("不正な \$slug です。 \$slug は空文字ではいけません。");
        }


        $query = new WP_Query([
            "post_type"   => "brick",
            "post_status" => "publish",
            "name"        => trim($slug),
            "fields"      => "ids",
        ]);

        if (!$query->found_posts) {
            throw new RuntimeException("フォーム設定が見つかりません: \$slug: $slug");
        }

        return $query->posts[0];
    }

    /**
     * @param     array[]    $form_structure_table
     * @return    array[]
     */
    private function sanitize($form_structure_table)
    {
        $sanitized = [];
        foreach ($form_structure_table as $row) {
            if (trim($row["label"]) == false && trim($row["name"]) == false) {
                continue;
            }

            $sanitized[] = array_merge(
                $row,
                [
                    "block"      => preg_replace("/[\s　]+/u", "", $row["block"]),
                    "label"      => trim($row["label"]),
                    "type"       => preg_replace("/[\s　]+/u", "", $row["type"]),
                    "name"       => preg_replace("/[\s　]+/u", "", $row["name"]),
                    "attributes" => trim($row["attributes"]),
                ]
            );
        }

        return $sanitized;
    }
}
