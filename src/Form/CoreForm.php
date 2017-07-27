<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form;

use SetBased\Abc\Abc;
use SetBased\Abc\Core\Form\Control\CoreFieldSet;
use SetBased\Abc\Core\Form\Validator\MandatoryValidator;
use SetBased\Abc\Form\Control\ConstantControl;
use SetBased\Abc\Form\Control\Control;
use SetBased\Abc\Form\Control\HiddenControl;
use SetBased\Abc\Form\Control\InvisibleControl;
use SetBased\Abc\Form\Control\SubmitControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Form\Form;

//----------------------------------------------------------------------------------------------------------------------
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
   * {@inheritdoc}
   */
  public function __construct($name = '', $csrfCheckFlag = true)
  {
    parent::__construct($name, $csrfCheckFlag);

    $this->attributes['class']        = 'input_table';
    $this->attributes['autocomplete'] = false;

    $this->visibleFieldSet = $this->addFieldSet(new CoreFieldSet());
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
  public function addFormControl($control, $wrdId = null, $mandatory = false)
  {
    switch (true)
    {
      // Add hidden, constant, and invisible controls to the fieldset for hidden controls.
      case ($control instanceof HiddenControl):
      case ($control instanceof ConstantControl):
      case ($control instanceof InvisibleControl):
        $this->hiddenFieldSet->addFormControl($control);
        break;

      // Add all other controls to the visible fieldset.
      default:
        switch (true)
        {
          // Set the size of text controls.
          case ($control instanceof TextControl):
            $max_length = $control->getAttribute('maxlength');
            $size       = $control->getAttribute('size');
            if ($size===null)
            {
              $size = (isset($max_length)) ? min($max_length, self::$maxTextSize) : self::$maxTextSize;
              $control->setAttrSize($size);
            }
            break;
        }

        $this->visibleFieldSet->addFormControl($control);

        if (isset($wrdId))
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
   *
   * @return SubmitControl
   */
  public function addSubmitButton($wrdId, $method, $name = 'submit')
  {
    $control = $this->visibleFieldSet->addSubmitButton($wrdId, $name);
    $this->addSubmitHandler($control, $method);

    return $control;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the visible fieldset of this form.
   *
   * @return CoreFieldSet
   */
  public function getVisibleFieldSet()
  {
    return $this->visibleFieldSet;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title of this form.
   *
   * @param int $wrdId The wrd_id of the title.
   */
  public function setTitle($wrdId)
  {
    $this->visibleFieldSet->setTitle(Abc::$babel->getWord($wrdId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title of this form.
   *
   * @param string $title The title.
   */
  public function setTitleText($title)
  {
    $this->visibleFieldSet->setTitle($title);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
