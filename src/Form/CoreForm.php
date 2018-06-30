<?php

namespace SetBased\Abc\Core\Form;

use SetBased\Abc\Abc;
use SetBased\Abc\Core\Form\Control\CoreFieldSet;
use SetBased\Abc\Core\Form\Validator\MandatoryValidator;
use SetBased\Abc\Form\Control\Control;
use SetBased\Abc\Form\Control\PushControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\Form;

/**
 * Form class for all forms in the core of ABC.
 */
class CoreForm extends Form
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The maximum size of a text control. The maximum text length can be larger.
   *
   * @var int
   */
  public static $maxTextSize = 80;

  /**
   * FieldSet for all form control elements not of type "hidden".
   *
   * @var CoreFieldSet
   */
  protected $visibleFieldSet;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function __construct(string $name = '', bool $csrfCheckFlag = true)
  {
    parent::__construct($name, $csrfCheckFlag);

    $this->attributes['class']        = 'input_table';
    $this->attributes['autocomplete'] = false;

    $this->visibleFieldSet = new CoreFieldSet();
    $this->addFieldSet($this->visibleFieldSet);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a form control to thi form.
   *
   * @param Control         $control   The from control
   * @param int|string|null $wrdId     Depending on the type:
   *                                   <ul>
   *                                   <li>int:    The wrd_id of the legend of the form control.
   *                                   <li>string: The legend of the form control.
   *                                   <li>null:   The form control has no legend.
   *                                   </ul>
   * @param bool            $mandatory If true the form control is mandatory.
   */
  public function addFormControl(Control $control, $wrdId = null, bool $mandatory = false)
  {
    if ($control->isHidden())
    {
      // Add hidden, constant, and invisible controls to the fieldset for hidden controls.
      $this->hiddenFieldSet->addFormControl($control);
    }
    else
    {
      // Add all other controls to the visible fieldset.
      if ($control instanceof TextControl)
      {
        $maxLength = $control->getAttribute('maxlength');
        $size      = $control->getAttribute('size');
        if ($size===null)
        {
          $size = (isset($maxLength)) ? min($maxLength, self::$maxTextSize) : self::$maxTextSize;
          $control->setAttrSize($size);
        }
      }

      $this->visibleFieldSet->addFormControl($control);

      if ($wrdId!==null)
      {
        $control->setFakeAttribute('_abc_label', (is_int($wrdId)) ? Abc::$babel->getWord($wrdId) : $wrdId);
      }

      if ($mandatory)
      {
        $control->addValidator(new MandatoryValidator(0));
        $control->setFakeAttribute('_abc_mandatory', true);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a submit button to this form.
   *
   * @param int|string $wrdId     Depending on the type:
   *                              <ul>
   *                              <li>int:    The ID of the word of the button text.
   *                              <li>string: The text of the button.
   *                              </ul>
   * @param string     $method    The name of method for handling the form submit.
   * @param string     $name      The name of the submit button.
   * @param string     $class     The class(es) of the submit button.
   *
   * @return PushControl
   */
  public function addSubmitButton($wrdId,
                                  string $method,
                                  string $name = 'submit',
                                  ?string $class = 'btn btn-success'): PushControl
  {
    $control = $this->visibleFieldSet->addSubmitButton($wrdId, $name);
    $control->addClass($class);
    $this->addSubmitHandler($control, $method);

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the visible fieldset of this form.
   *
   * @return CoreFieldSet
   */
  public function getVisibleFieldSet(): CoreFieldSet
  {
    return $this->visibleFieldSet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title of this form.
   *
   * @param int $wrdId The wrd_id of the title.
   */
  public function setTitle(int $wrdId): void
  {
    $this->visibleFieldSet->setTitle(Abc::$babel->getWord($wrdId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title of this form.
   *
   * @param string|null $title The title.
   */
  public function setTitleText(?string $title): void
  {
    $this->visibleFieldSet->setTitle($title);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
