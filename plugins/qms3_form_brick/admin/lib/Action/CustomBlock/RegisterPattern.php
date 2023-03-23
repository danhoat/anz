<?php
declare(strict_types=1);

namespace QMS3\BrickAdmin\Action\CustomBlock;


class RegisterPattern
{
    /**
     * @return    void
     */
    public function __invoke()
    {
        register_block_pattern(
            'brick/form-set',
            array(
                'title' => 'フォームセット',
                'content' => '
                    <!-- wp:group {"className":"l-form-container"} -->
                    <div class="wp-block-group l-form-container"><!-- wp:brick/flow {"className":"wp-block-brick-flow"} /-->
                    <!-- wp:group {"className":"l-form-container__inner"} -->
                    <div class="wp-block-group l-form-container__inner"><!-- wp:brick/form {"postId":null,"className":"wp-block-brick-form"} -->
                    <!-- wp:paragraph -->
                    <p>ご入力いただきました個人情報の取り扱いに関しては、<a href="#" target="_blank" rel="noreferrer noopener">「プライバシーポリシー」</a>の元に、適切に管理を行っております。</p>
                    <!-- /wp:paragraph -->
                    <!-- /wp:brick/form --></div>
                    <!-- /wp:group --></div>
                    <!-- /wp:group -->
                ',
            )
        );
    }
}
