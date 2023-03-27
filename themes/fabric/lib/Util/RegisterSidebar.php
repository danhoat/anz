<?php
namespace Fabric\Util;


class RegisterSidebar
{
	/** @var    string */
	private $id;

	/** @var    string */
	private $name;

	public function __construct( string $id, string $name)
	{
		$this->id = $id;
		$this->name = $name;

		add_action( 'widgets_init', array( $this, 'register' ) );
	}

	public function register()
	{
		register_sidebar( array(
			'id' => $this->id,
			'name' => $this->name,
			'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<div class="c-widget__title -side">',
			'after_title' => '</div>',
		) );
	}
}
