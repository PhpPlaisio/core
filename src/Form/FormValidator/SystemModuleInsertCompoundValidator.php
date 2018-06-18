<?php

namespace SetBased\Abc\Core\Form\FormValidator;

use SetBased\Abc\Form\Control\CompoundControl;
use SetBased\Abc\Form\Control\SimpleControl;
use SetBased\Abc\Form\Validator\CompoundValidator;

/**
 * Form validator for the form for inserting or updating a module.
 */
class SystemModuleInsertCompoundValidator implements CompoundValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function validate(CompoundControl $control): bool
  {
    $ret = true;

    $values = $control->getSubmittedValue();

    // Only and only one of mdl_name or wrd_id must be set.
    if (!isset($values['mdl_name']) && !$values['wrd_id'])
    {
      /** @var SimpleControl $input */
      $input = $control->getFormControlByName('wrd_id');
      $input->setErrorMessage('Mandatory field');

      /** @var SimpleControl $input */
      $input = $control->getFormControlByName('mdl_name');
      $input->setErrorMessage('Mandatory field');

      $ret = false;
    }

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
