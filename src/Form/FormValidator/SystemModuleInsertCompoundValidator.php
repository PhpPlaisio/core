<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\FormValidator;

use Plaisio\Form\Control\CompoundControl;
use Plaisio\Form\Control\SimpleControl;
use Plaisio\Form\Validator\CompoundValidator;

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
