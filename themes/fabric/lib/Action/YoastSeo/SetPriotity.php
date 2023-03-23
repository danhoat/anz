<?php
namespace Fabric\Action\YoastSeo;


class SetPriotity
{
	/** @var    string */
	private $priotity;

	/**
	 * @param    string    $priotity
	 */
	public function __construct( string $priotity = 'low' )
	{
		$this->priotity = $priotity;
	}

	/**
	 * @param    string    $priority
	 * @return    string
	 */
	public function __invoke( string $priority ): string
	{
		return $this->priotity;
	}
}
