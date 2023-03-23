<?php

declare(strict_types=1);

namespace QMS3\Brick\SubTask;

use Datetime;
use DateTimeZone;
use LogicException;
use RuntimeException;
use WP_Error;
use Detection\MobileDetect;
use QMS3\Brick\Param\Param;
use QMS3\Brick\Step\Step;
use QMS3\Brick\Structure\Structure;
use QMS3\Brick\Values\Values;
use QMS3\Brick\ValueContainer\ValueContainerInterface as ValueContainer;
use QMS3\Brick\ValueContainer\CheckboxValueContainer;
use QMS3\Brick\ValueContainer\DatepickerValueContainer;


/**
 * フォームから送信された値を WordPress の投稿として登録するするドライバ
 *
 * @since    1.5.2
 */
class WordPressPost implements SubTaskInterface
{
    /** @var    string */
    private $post_type;

    /** @var    array<string,mixed> */
    private $options;

    /**
     * @param    string                 $post_type
     * @param    array<string,mixed>    $options
     */
    public function __construct($post_type = "", array $options = [])
    {
        if (!function_exists("wp_insert_post")) {
            throw new RuntimeException("wp_insert_post() 関数が定義されていません。WordPress は正常に読み込まれていますか？");
        }

        if (!function_exists("post_type_exists")) {
            throw new RuntimeException("post_type_exists() 関数が定義されていません。WordPress は正常に読み込まれていますか？");
        }

        if (!function_exists("update_post_meta")) {
            throw new RuntimeException("update_post_meta() 関数が定義されていません。WordPress は正常に読み込まれていますか？");
        }

        $post_type = trim($post_type);
        if (!empty($post_type) && !post_type_exists($post_type)) {
            throw new RuntimeException("不明な \$post_type です: \$post_type: {$post_type}");
        }

        $this->post_type = $post_type;
        $this->options   = $options;
    }

    /**
     * @param     Structure       $structure
     * @param     Values          $values
     * @param     string          $form_type
     * @param     Param           $param
     * @param     Step            $step
     * @param     MobileDetect    $detect
     * @return    bool
     */
    public function process(
        Structure $structure,
        Values $values,
        $form_type,
        Param $param,
        Step $step,
        MobileDetect $detect
    )
    {
        if (!$step->is('submit')) { return; }

        if (empty($this->post_type) && !post_type_exists($form_type)) {
            throw new RuntimeException("\$post_type を指定してください。\$post_type が指定されてなかったため代わりに \$form_type を使うことを試みましたが、\$form_type に一致する投稿タイプは見つかりませんでした: \$form_type: {$form_type}");
        }
        $post_type = $this->post_type ?: $form_type;

        $post_title = $this->post_title(
            $structure,
            $values,
            $form_type,
            $param,
            $step,
            $detect
        );

        $post_id = wp_insert_post([
            "post_type"   => $post_type,
            "post_title"  => $post_title,
            "post_status" => "publish",
        ], /* $wp_error = */ true);

        if ($post_id instanceof WP_Error) {
            $messages = $post_id->get_error_messages();
            throw new RuntimeException(join("\n", $messages));
        }

        $raw_values = [];

        foreach ($form->fields as $name => $_) {
            $raw_values[$name] = $this->prepare($name, $form);
        }

        foreach ($this->options as $name => $_) {
            $raw_values[$name] = $this->prepare($name, $form);
        }

        $values = [];
        foreach ($raw_values as $name => $value) {
            if ($value instanceof CheckboxValueContainer) {
                $items = [];
                foreach ($value as $item) { $items[] = (string) $item; }

                $values[$name] = $items;
            } else if ($value instanceof DatepickerValueContainer) {
                $date = $value->date;
                $values[$name] = $date ? $date->format("Y-m-d") : null;
            } else if ($value instanceof ValueContainer) {
                $values[$name] = (string) $value;
            } else {
                $values[$name] = $value;
            }
        }

        $this->set_post_meta($post_id, $values);
    }

    /**
     * @param     Structure       $structure
     * @param     Values          $values
     * @param     string          $form_type
     * @param     Param           $param
     * @param     Step            $step
     * @param     MobileDetect    $detect
     * @return    string
     */
    private function post_title(
        Structure $structure,
        Values $values,
        $form_type,
        Param $param,
        Step $step,
        MobileDetect $detect
    )
    {
        if (isset($this->options["post_title"])) {
            return $this->options["post_title"]->get_value(
                $structure,
                $values,
                $form_type,
                $param,
                $step,
                $detect
            );
        } else {
            $tz = new DateTimeZone("Asia/Tokyo");
            $now = new DateTime("now", $tz);
            return $now->format("Y-m-d H:i:s");
        }
    }

    /**
     * @param     int                    $post_id
     * @param     array<string,mixed>    $values
     */
    private function set_post_meta($post_id, array $values)
    {
        $acf_enable = function_exists("update_field");

        foreach ($values as $name => $value) {
            if ($acf_enable) {
                update_field($name, $value, $post_id);
            } else {
                update_post_meta($post_id, $name, $value);
            }
        }
    }

    // ====================================================================== //

    /**
     * @param     string     $name
     * @param     Form       $form
     * @return    ValueContainer
     */
    private function prepare($name, Form $form)
    {
        if (!isset($this->options[$name])) {
            // 何もしない
            // $name = $name;
        } else if (is_string($this->options[$name])) {
            $name = trim($this->options[$name]);
        } else if (is_callable($this->options[$name])) {
            $callback = $this->options[$name];

            $values = [];
            foreach ($form->fields as $field) {
                $values[$field->name] = $field->value;
            }

            return call_user_func($callback, $values);
        }

        if (!isset($form->fields[$name])) {
            throw new LogicException("このフォームには name が \"$name\" のフォーム項目はありません");
        }

        return $form->fields[$name]->value;
    }
}
