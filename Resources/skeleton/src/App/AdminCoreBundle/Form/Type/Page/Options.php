<?php

namespace Parabol\AdminCoreBundle\Form\Type\Page;

/**
 * Options class
 */
class Options
{

	public function getPostOptions(array $fieldOptions, array $builderOptions = array())
	{
		$fieldOptions['choice_label'] = function ($post) { return $post->getTitle(); };
		return $fieldOptions;
	}
}
