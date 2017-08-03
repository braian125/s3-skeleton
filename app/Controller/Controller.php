<?php

namespace App\Controller;


class Controller
{
	
	protected $container;

	public function __construct($container)
	{
		$this->container = $container;
	}


	public function __get($property)
	{
		//var_dump($property);
		if($this->container->{$property}){
			return $this->container->{$property};
		}
	}
}


