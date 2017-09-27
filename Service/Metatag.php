<?php

namespace Parabol\AdminCoreBundle\Service;

/**
* Metatag
*/
class Metatag extends \Twig_Extension
{
		
	private $metatags;

	public function getName()
	{
		return 'metatag';
	}

	public function getFunctions()
	{
	    return array(
	        new \Twig_SimpleFunction('renderMetas', [$this, 'render'], [
	            'is_safe' => ['html']
	            ]
	        )
	    );
	}

	public function render()
	{
		$code = '';
		foreach((array)$this->metatags as $name => $value)
		{
			switch($name)
			{
				case 'title':
					$code .= '<title>'.$value.'</title>';
				default:
					$code .= '<meta property="'.$name.'" name="'.$name.'" content="'.$value.'">';
			}
		}

		return $code;
	}

	public function addMetatag($name, $value)
    {
    	if($value) $this->metatags[$name] = $value;
    }
}