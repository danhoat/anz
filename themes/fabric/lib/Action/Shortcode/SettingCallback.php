<?php
namespace Fabric\Action\Shortcode;


class SettingCallback
{
	/** @var    array<string,mixed> */
	private $setting;

	public function __construct()
	{
		$this->setting = require( __DIR__ . '/../../../setting.php' );
	}

	/**
	 * @param    array<string,mixed>    $atts
	 * @return    mixed
	 */
	public function __invoke( array $atts )
	{
		if ( ! isset( $atts[ 'name' ] ) ) {
			throw new \InvalidArgumentException();
		}

		$name = $atts[ 'name' ];

		if ( array_key_exists( $name, $this->setting ) ) {
			return $this->setting[ $name ];
		} elseif ( array_key_exists( 'default', $atts ) ) {
			return $atts[ 'default' ];
		} else {
			return '';
		}
	}
}
