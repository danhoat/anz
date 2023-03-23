<?php
declare(strict_types=1);

namespace QMS3\Brick\MailSetting;

use QMS3\Brick\Exception\InvalidEmailException;


class MailSettingOption
{
    /** @var   string */
    private $tos_str;

    /** @var   string */
    private $from;

    /** @var   string */
    private $from_name;

    /** @var   string */
    private $subject_template;

    /** @var   string */
    private $main_text_template;

    /** @var   string */
    private $block_filter;

    /** @var   string */
    private $post_result_template;

    /** @var   string */
    private $after_text_template;

    /** @var   string */
    private $signature_template;

    /**
     * @param    string    $tos_str
     * @param    string    $from
     * @param    string    $from_name
     * @param    string    $subject_template
     * @param    string    $main_text_template
     * @param    string    $block_filter
     * @param    string    $post_result_template
     * @param    string    $after_text_template
     * @param    string    $signature_template
     */
    public function __construct(
        $tos_str,
        $from,
        $from_name,
        $subject_template,
        $main_text_template,
        $block_filter,
        $post_result_template,
        $after_text_template,
        $signature_template
    )
    {
        $this->tos_str              = $tos_str;
        $this->from                 = $from;
        $this->from_name            = $from_name;
        $this->subject_template     = $subject_template;
        $this->main_text_template   = $main_text_template;
        $this->block_filter         = $block_filter;
        $this->post_result_template = $post_result_template;
        $this->after_text_template  = $after_text_template;
        $this->signature_template   = $signature_template;
    }

    // ====================================================================== //

    /**
     * @return    string[]
     */
    public function get_tos()
    {
        if (trim($this->tos_str) == false) { return []; }

        return $this->normalize_tos($this->tos_str);
    }

    /**
     * @return    string
     */
    public function get_from()
    {
        return $this->from;
    }

    /**
     * @return    string
     */
    public function get_from_name()
    {
        return $this->from_name;
    }

    /**
     * @return    string
     */
    public function get_subject_template()
    {
        return $this->subject_template;
    }

    /**
     * @return    string
     */
    public function get_main_text_template()
    {
        return $this->main_text_template;
    }

    /**
     * @return    string[]
     */
    public function get_block_names()
    {
        $block_names = preg_split("/[\s,]/u", $this->block_filter, -1, PREG_SPLIT_NO_EMPTY);
        $block_names = array_map("trim", $block_names);

        return $block_names;
    }

    /**
     * @return    string
     */
    public function get_post_result_template()
    {
        return $this->post_result_template;
    }

    /**
     * @return    string
     */
    public function get_after_text_template()
    {
        return $this->after_text_template;
    }

    /**
     * @return    string
     */
    public function get_signature_template()
    {
        return $this->signature_template;
    }

    // ====================================================================== //

    /**
     * @param     string      $tos_str
     * @return    string[]
     */
    private function normalize_tos($tos_str)
    {
        $lines = preg_split("/[\s,]+/", $tos_str);
        $lines = array_filter(array_map("trim", $lines));

        $tos = array();
        foreach ($lines as $line) {
            preg_match("/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/", $line, $matches);

            if (!$matches) {
                throw new InvalidEmailException("不正な形式のメールアドレスです: $line");
            }

            $tos[] = $matches[0];
        }

        return $tos;
    }
}
