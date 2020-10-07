<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\Control;

use Plaisio\Form\Control\ComplexControl;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\PushControl;
use Plaisio\Form\Control\SubmitControl;
use Plaisio\Helper\Html;
use Plaisio\Kernel\Nub;

/**
 * Fieldset for visible form controls in core form.
 */
class CoreFieldSet extends FieldSet
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The class used in the generated HTML code.
   *
   * @var string|null
   */
  public static ?string $class = 'input-table';

  /**
   * The complex form control holding the buttons of this fieldset.
   *
   * @var ComplexControl|null
   */
  private ?ComplexControl $buttonGroupControl = null;

  /**
   * The title of the in the header of the form of this field set.
   *
   * @var string|null
   */
  private ?string $htmlTitle = null;

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
   * @return PushControl
   */
  public function addSubmitButton($wrdId, string $name = 'submit'): PushControl
  {
    // If necessary create a button form control.
    if ($this->buttonGroupControl===null)
    {
      $this->buttonGroupControl = new ComplexControl();
      $this->addFormControl($this->buttonGroupControl);
    }

    $input = new SubmitControl($name);
    $input->setValue((is_int($wrdId)) ? Nub::$nub->babel->getWord($wrdId) : $wrdId);
    $this->buttonGroupControl->addFormControl($input);

    return $input;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtml(): string
  {
    $this->addClass(static::$class);

    $ret = $this->getHtmlStartTag();

    $ret .= Html::generateTag('table', $this->attributes);

    $childAttributes = ['class' => static::$class];

    if ($this->htmlTitle!==null)
    {
      $ret .= Html::generateTag('thead', $childAttributes);
      $ret .= Html::generateTag('tr', $childAttributes);
      $ret .= '<th colspan="2">'.$this->htmlTitle.'</th>';
      $ret .= '</tr>';
      $ret .= '</thead>';
    }

    $ret .= Html::generateTag('tbody', $childAttributes);
    foreach ($this->controls as $control)
    {
      if ($control!==$this->buttonGroupControl)
      {
        $ret       .= Html::generateTag('tr', $childAttributes);
        $ret       .= '<th>';
        $ret       .= Html::txt2Html($control->getAttribute('_plaisio_label'));
        $mandatory = $control->getAttribute('_plaisio_mandatory');
        if (!empty($mandatory)) $ret .= '<span class="mandatory">*</span>';
        $ret .= '</th>';

        $ret .= Html::generateTag('td', $childAttributes);
        $ret .= $control->getHtml();
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

    if ($this->buttonGroupControl!==null)
    {
      $ret .= Html::generateTag('tfoot', $childAttributes);
      $ret .= Html::generateTag('tr', $childAttributes);
      $ret .= '<td colspan="2" class="button-group">';
      $ret .= '<div class="button-group">';
      $ret .= $this->buttonGroupControl->getHtml();
      $ret .= '</div>';
      $ret .= '</tr>';
      $ret .= '</tfoot>';
    }

    $ret .= '</table>';

    $ret .= $this->getHtmlEndTag();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the title of the form of this field set.
   *
   * @param string|null $title
   */
  public function setTitle(?string $title): void
  {
    $this->htmlTitle = Html::txt2Html($title);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
