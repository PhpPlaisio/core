<?php

namespace SetBased\Abc\Core\Form\Control;

use SetBased\Abc\Abc;
use SetBased\Abc\Form\Control\ComplexControl;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\PushMeControl;
use SetBased\Abc\Form\Control\ResetControl;
use SetBased\Abc\Form\Control\SubmitControl;
use SetBased\Abc\Helper\Html;

/**
 * Fieldset for visible form controls in core form.
 */
class CoreFieldSet extends FieldSet
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The complex form control holding the buttons of this fieldset.
   *
   * @var ComplexControl
   */
  private $buttonControl;

  /**
   * The title of the in the header of the form of this field set.
   *
   * @var string
   */
  private $htmlTitle;

  //--------------------------------------------------------------------------------------------------------------------

  /**
   * Adds a button control to this fieldset.
   *
   * @param string      $submitButtonText The text of the submit button.
   * @param string|null $resetButtonText  The text of the reset button. If null no reset button will be created.
   * @param string      $submitName       The name of the submit button.
   * @param string      $resetName        The name of the reset button.
   *
   * @return ComplexControl
   */
  public function addButton(string $submitButtonText = 'OK',
                            ?string $resetButtonText = null,
                            string $submitName = 'submit',
                            string $resetName = 'reset'): ComplexControl
  {
    $this->buttonControl = new CoreButtonControl();

    // Create submit button.
    $submit = new SubmitControl($submitName);
    $submit->setValue($submitButtonText);
    $this->buttonControl->addFormControl($submit);

    // Create reset button.
    if ($resetButtonText)
    {
      $reset = new ResetControl($resetName);
      $reset->setValue($resetButtonText);
      $this->buttonControl->addFormControl($reset);
    }

    $this->addFormControl($this->buttonControl);

    return $this->buttonControl;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a submit button to this fieldset.
   *
   * @param int|string $wrdId Depending on the type:
   *                          <ul>
   *                          <li>int: The ID of the word of the button text.
   *                          <li>string: The text of the button.
   *                          </ul>
   * @param string     $name  The name of the submit button.
   *
   * @return PushMeControl
   */
  public function addSubmitButton($wrdId, string $name = 'submit'): PushMeControl
  {
    // If necessary create a button form control.
    if (!$this->buttonControl)
    {
      $this->buttonControl = new CoreButtonControl();
      $this->addFormControl($this->buttonControl);
    }

    $input = new SubmitControl($name);
    $input->setValue((is_int($wrdId)) ? Abc::$babel->getWord($wrdId) : $wrdId);
    $this->buttonControl->addFormControl($input);

    return $input;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function generate(): string
  {
    $ret = $this->generateStartTag();

    $ret .= '<div class="input_table">';
    $ret .= '<table>';

    if ($this->htmlTitle)
    {
      $ret .= '<thead>';
      $ret .= '<tr>';
      $ret .= '<th colspan="2">'.$this->htmlTitle.'</th>';
      $ret .= '</tr>';
      $ret .= '</thead>';
    }

    if ($this->buttonControl)
    {
      $ret .= '<tfoot class="button">';
      $ret .= '<tr>';
      $ret .= '<td colspan="2">';
      $ret .= $this->buttonControl->generate();
      $ret .= '</td>';
      $ret .= '</tr>';
      $ret .= '</tfoot>';
    }

    $ret .= '<tbody>';
    foreach ($this->controls as $control)
    {
      if ($control!==$this->buttonControl)
      {
        $ret       .= '<tr>';
        $ret       .= '<th>';
        $ret       .= Html::txt2Html($control->getAttribute('_abc_label'));
        $mandatory = $control->getAttribute('_abc_mandatory');
        if (!empty($mandatory)) $ret .= '<span class="mandatory">*</span>';
        $ret .= '</th>';

        $ret .= '<td>';
        $ret .= $control->generate();
        $ret .= '</td>';

        $messages = $control->getErrorMessages(true);
        if ($messages)
        {
          $ret   .= '<td class="error">';
          $first = true;
          foreach ($messages as $err)
          {
            if ($first) $first = false;
            else        $ret .= '<br/>';
            $ret .= Html::txt2Html($err);
          }
          $ret .= '</td>';
        }

        $ret .= '</tr>';
      }
    }

    $ret .= '</tbody>';
    $ret .= '</table>';
    $ret .= '</div>';

    $ret .= $this->generateEndTag();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title of the form of this field set.
   *
   * @param string $title
   */
  public function setTitle(?string $title): void
  {
    $this->htmlTitle = Html::txt2Html($title);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
