<?php
declare(strict_types=1);

namespace QMS3\Brick\MailGenerator;

use QMS3\Brick\Exception\InvalidEmailException;
use QMS3\Brick\Form\Form;
use QMS3\Brick\Block\Block;
use QMS3\Brick\MailGenerator\MailGenerator;
use QMS3\Brick\MailSetting\MailSetting;
use QMS3\Brick\Util\MailAddressNormalizer;


class ThanksMailGenerator extends MailGenerator
{
    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string[]
     * @throws    InvalidEmailException
     */
    protected function tos(MailSetting $mail_setting, Form $form)
    {
        $normalizer = new MailAddressNormalizer();

        $tos = [];
        foreach ($form->fields as $field) {
            if ($field->thanks_to) {
                $tos = array_merge(
                    $tos,
                    $normalizer->normalize((string) $field->value)  // TODO: サンクスメール送信先のアドレスはエンドユーザーが入力するものなので厳格にチェックして例外飛ばしてはダメだね
                );
            }
        }

        return $tos;
    }

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    protected function from(MailSetting $mail_setting, Form $form)
    {
        return $mail_setting->from;
    }

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    protected function from_name(MailSetting $mail_setting, Form $form)
    {
        return $mail_setting->from_name;
    }

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    protected function subject(MailSetting $mail_setting, Form $form)
    {
        return $this->replace($mail_setting->subject_template, $form);
    }

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    protected function main_text(MailSetting $mail_setting, Form $form)
    {
        return $this->replace($mail_setting->main_text_template, $form);
    }

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    protected function post_result(MailSetting $mail_setting, Form $form)
    {
        // TODO: title とか hidden とか一切考慮できていないので実装見直す

        /** @var   array<string,Block> */
        $form_blocks = call_user_func_array([$form, "blocks"], $mail_setting->block_names);

        $blocks = [];
        foreach ($form_blocks as $block) {
            $rows = [];
            foreach ($block as $row) {
                // hidden フィールドはエンドユーザーには見せない
                $fields = array_filter($row->fields, function ($field) {
                    return $field->type !== "hidden";
                });

                if (empty($fields)) { continue; }

                $values = [];
                foreach ($fields as $field) {
                    $values[] = $field->render('plain');
                }

                $label = $row->label(/* $sanitize = */ true);
                $values = array_filter($values);
                $rows[$label] = join(" ", $values);
            }

            $lines = [];
            foreach ($rows as $label => $value) {
                $lines[] = "{$label}　：　{$value}";
            }

            $blocks[] = join("\n", $lines);
        }

        return join("\n\n", $blocks);
    }

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    protected function after_text(MailSetting $mail_setting, Form $form)
    {
        return $this->replace($mail_setting->after_text_template, $form);
    }

    /**
     * @param     MailSetting    $mail_setting
     * @param     Form           $form
     * @return    string
     */
    protected function signature(MailSetting $mail_setting, Form $form)
    {
        return $this->replace($mail_setting->signature_template, $form);
    }
}
