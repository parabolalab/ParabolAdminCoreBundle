<?php

namespace Parabol\AdminCoreBundle\Form\Type\Base\TextBlock;

use Admingenerated\AppAdminCoreBundle\Form\BaseTextBlockType\EditType as BaseEditType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * EditType
 */
class EditType extends BaseEditType
{
	use  \Parabol\BaseBundle\Form\Type\Base\BaseType;

  public function buildForm(FormBuilderInterface $builder, array $options)
  {

        $this->builder = $builder;
        parent::buildForm($this->builder, $options);

        $ext = new \Parabol\FilesUploadBundle\Form\Base\Extension\AdminBaseTypeExtension([],'dev');
        $ext->postBuild($this, $options);

  }
}