<?php

namespace Parabol\AdminCoreBundle\Form\Base;

/**
* 
*/
class AdminBaseTypeExtensions
{
	private $extensions;

	public function __construct()
	{
		$this->extensions = [];
	}

	public function addExtension($extension)
	{
		$this->extensions[] = $extension;
	}

	public function getExtensions()
	{
		return $this->extensions;
	}
}

