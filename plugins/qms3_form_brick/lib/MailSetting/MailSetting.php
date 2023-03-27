<?php
declare(strict_types=1);

namespace QMS3\Brick\MailSetting;

use QMS3\Brick\MailSetting\MailSettingOption;
use QMS3\Brick\Util\ReadonlyProps;


/**
 * @property-read    string[]    $tos
 * @property-read    string      $from
 * @property-read    string      $from_name
 * @property-read    string      $subject_template
 * @property-read    string      $main_text_template
 * @property-read    string[]    $block_names
 * @property-read    string      $post_result_template
 * @property-read    string      $after_text_template
 * @property-read    string      $signature_template
 */
class MailSetting
{
    use ReadonlyProps;

    /** @var    array<int,array> */
    private $options = [];

    /**
     * @todo    $priority が指定されたときの動作を実装
     *
     * @param     MailSettingOption    $option
     * @param     int                  $priority    $option が参照されるときの優先順位。値が小さいほど優先順位が高く、値が大きいほど優先順位が低い
     * @return    MailSetting
     */
    public function add(MailSettingOption $option, $priority = 10)
    {
        $this->options[] = $option;

        return $this;
    }

    /**
     * @return    array<int,MailSettingOption>
     */
    private function options()
    {
        return $this->options;
    }

    // ====================================================================== //

    /**
     * @return    string[]
     */
    protected function _tos()
    {
        foreach ($this->options() as $option) {
            $tos = $option->get_tos();

            if (!empty($tos)) { return $tos; }
        }

        return [];
    }

    /**
     * @return    string
     */
    protected function _from()
    {
        foreach ($this->options() as $option) {
            $from = $option->get_from();

            if (!empty($from)) { return $from; }
        }

        return "";
    }

    /**
     * @return    string
     */
    protected function _from_name()
    {
        foreach ($this->options() as $option) {
            $from_name = $option->get_from_name();

            if (!empty($from_name)) { return $from_name; }
        }

        return "";
    }

    /**
     * @return    string
     */
    protected function _subject_template()
    {
        foreach ($this->options() as $option) {
            $subject_template = $option->get_subject_template();

            if (!empty($subject_template)) { return $subject_template; }
        }

        return "";
    }

    /**
     * @return    string
     */
    protected function _main_text_template()
    {
        foreach ($this->options() as $option) {
            $main_text_template = $option->get_main_text_template();

            if (!empty($main_text_template)) { return $main_text_template; }
        }

        return "";
    }

    /**
     * @return    string[]
     */
    protected function _block_names()
    {
        // この foreach ループは高々1回しか実行されないが、間違いではない
        // $this->options() が少なくとも1つの要素を持っていれば最初の要素だけを参照して、
        // $this->options() が空配列ならば、(foreach の外の) return [] を実行したい
        foreach ($this->options() as $option) {
            return $option->get_block_names();
        }

        return [];
    }

    /**
     * @return    string
     */
    protected function _post_result_template()
    {
        // この foreach ループは高々1回しか実行されないが、間違いではない
        // $this->options() が少なくとも1つの要素を持っていれば最初の要素だけを参照して、
        // $this->options() が空配列ならば、(foreach の外の) return [] を実行したい
        foreach ($this->options() as $option) {
            return $option->get_post_result_template();
        }

        return [];
    }

    /**
     * @return    string
     */
    protected function _after_text_template()
    {
        foreach ($this->options() as $option) {
            $after_text_template = $option->get_after_text_template();

            if (!empty($after_text_template)) { return $after_text_template; }
        }

        return "";
    }

    /**
     * @return    string
     */
    protected function _signature_template()
    {
        foreach ($this->options() as $option) {
            $signature_template = $option->get_signature_template();

            if (!empty($signature_template)) { return $signature_template; }
        }

        return "";
    }
}
