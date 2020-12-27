<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\Control;

use Plaisio\Form\Control\ComplexControl;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\PushControl;
use Plaisio\Form\Control\SubmitControl;
use Plaisio\Form\Walker\PrepareWalker;
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

    $childAttributes   = ['class' => static::$class];
    $errorAttributes   = ['class' => [static::$class, static::$class.'-error']];
    $errorsAttributes  = ['class' => [static::$class, static::$class.'-errors']];
    $buttonAttributes  = ['class' => [static::$class, static::$class.'-button']];
    $buttonAttributes2 = ['class' => [static::$class, static::$class.'-button'], 'colspan' => 2];

    $ret = $this->getHtmlStartTag();
    $ret .= Html::generateTag('table', $this->attributes);

    if ($this->htmlTitle!==null)
    {
      $ret .= Html::generateTag('thead', $childAttributes);
      $ret .= Html::generateTag('tr', $childAttributes);
      $ret .= '<th colspan="2">';
      $ret .= $this->htmlTitle;
      $ret .= '</th>';
      $ret .= '</tr>';
      $ret .= '</thead>';
    }

    $ret .= Html::generateTag('tbody', $childAttributes);
    foreach ($this->controls as $control)
    {
      if ($control!==$this->buttonGroupControl)
      {
        $id        = $control->getId();
        $mandatory = !empty($control->getAttribute('_plaisio_mandatory'));
        $errors    = $control->getErrorMessages(true);

        $labelAttributes = ['for' => $id, 'class' => []];
        if ($mandatory) $labelAttributes['class'][] = 'mandatory';
        if (!empty($errors)) $labelAttributes['class'][] = 'error';

        $ret .= Html::generateTag('tr', $childAttributes);
        $ret .= Html::generateTag('th', $childAttributes);
        $ret .= Html::generateTag('label', $labelAttributes);
        $ret .= Html::txt2Html($control->getAttribute('_plaisio_label'));
        $ret .= '</label>';
        $ret .= '</th>';

        $ret .= Html::generateTag('td', $childAttributes);
        $ret .= $control->getHtml();

        if (!empty($errors))
        {
          $ret .= Html::generateTag('div', $errorsAttributes);
          foreach ($errors as $error)
          {
            $ret .= Html::generateTag('span', $errorAttributes);
            $ret .= Html::txt2Html($error);
            $ret .= '</span>';
          }
          $ret .= '</div>';
        }
        $ret .= '</td>';
        $ret .= '</tr>';
      }
    }
    $ret .= '</tbody>';

    if ($this->buttonGroupControl!==null)
    {
      $ret .= Html::generateTag('tfoot', $buttonAttributes);
      $ret .= Html::generateTag('tr', $buttonAttributes);
      $ret .= Html::generateTag('td', $buttonAttributes2);
      $ret .= Html::generateTag('div', $buttonAttributes);
      $ret .= $this->buttonGroupControl->getHtml();
      $ret .= '</div>';
      $ret .= '</td>';
      $ret .= '</tr>';
      $ret .= '</tfoot>';
    }

    $ret .= '</table>';

    $ret .= $this->getHtmlEndTag();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Prepares this form complex control for HTML code generation or loading submitted values.
   *
   * @param PrepareWalker $walker The object for walking the control tree.
   *
   * @since 1.0.0
   * @api
   */
  public function prepare(PrepareWalker $walker): void
  {
    $walker->setModuleClass(self::$class);

    parent::prepare($walker);
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
