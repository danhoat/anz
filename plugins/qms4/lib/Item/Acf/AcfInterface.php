<?php

namespace QMS4\Item\Acf;


interface AcfInterface
{
	/**
	 * @param    array<string,mixed>    $args
	 * @return    mixed
	 */
	public function invoke( array $args );
}
