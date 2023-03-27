<?php
namespace Fabric\Util;


/**
 * ブロックスタイルを登録する
 */
class RegisterBlockStyle
{
	/** @var    string */
	private $block_name;

	/** @var    string */
	private $style_name;

	/** @var    string */
	private $label;

	/**
	 * @param    string    $block_name    対象のブロック名
	 * @param    string    $style_name
	 * @param    string    $label
	 */
	public function __construct(
		string $block_name,
		string $style_name,
		string $label
	)
	{
		$this->block_name = $block_name;
		$this->style_name = $style_name;
		$this->label = $label;

		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * @return    void
	 */
	public function register()
	{
		register_block_style(
			$this->block_name,
			array(
				'name' => $this->style_name,
				'label' => $this->label,
				'inline_style' => '',
			)
		);
	}
}
