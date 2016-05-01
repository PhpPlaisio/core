<?php

namespace SetBased\Abc\Core\Form\Validator;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Validator for validating that a form control has been filled out.
 */
class MandatoryValidator extends \SetBased\Abc\Form\Validator\MandatoryValidator
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The text ID (txt_id) of the error message.
   *
   * @var int
   */
  private $txtId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $txtId The text ID of the error message.
   */
  public function __construct($txtId)
  {
    $this->txtId = $txtId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function validate($control)
  {
    $valid = parent::validate($control);

    if (!$valid)
    {
      // @todo Improve validator error messages.
      $errmsg = 'Verplicht veld.';
      $control->setErrorMessage($errmsg);
    }

    return $valid;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
