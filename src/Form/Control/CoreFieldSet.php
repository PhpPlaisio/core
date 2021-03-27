<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\Control;

use Plaisio\Form\Control\ComplexControl;
use Plaisio\Form\Control\Control;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\PushControl;
use Plaisio\Form\Control\SubmitControl;
use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;
use Plaisio\Kernel\Nub;

/**
 * Fieldset for visible form controls in core form.
 *
 * @property-read RenderWalker $renderWalker The render walker.
 */
class CoreFieldSet extends FieldSet
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Holds additional information about form controls.
   *
   * @var array
   */
  protected array $addendum;

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
   * Object constructor.
   *
   * @param string|null $name The name of this form control.
   */
  public function __construct(?string $name = '')
  {
    parent::__construct($name);

    $this->renderWalker = new RenderWalker('it');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a form control to this complex form control.
   *
   * @param Control     $control   The from control added.
   * @param string|null $label     The label of the form control.
   * @param bool        $mandatory Whether the form control is mandatory.
   *
   * @return $this
   */
  public function addFormControl(Control $control, $label = null, bool $mandatory = false): self
  {
    $this->addendum[] = ['label'     => $label,
                         'mandatory' => $mandatory];

    return parent::addFormControl($control);
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
  public function getHtml(RenderWalker $walker): string
  {
    $this->addClasses($this->renderWalker->getClasses());

    $buttonAttributes  = ['class' => $this->renderWalker->getClasses('button')];
    $buttonAttributes2 = ['class' => $this->renderWalker->getClasses('button'), 'colspan' => 2];
    $childAttributes   = ['class' => $this->renderWalker->getClasses()];
    $childAttributes2  = ['class' => $this->renderWalker->getClasses(), 'colspan' => 2];
    $errorAttributes   = ['class' => $this->renderWalker->getClasses('error')];
    $errorsAttributes  = ['class' => $this->renderWalker->getClasses('errors')];
    $headerAttributes  = ['class' => $this->renderWalker->getClasses('header')];
    $inputAttributes   = ['class' => $this->renderWalker->getClasses('input')];
    $rowAttributes     = ['class' => $this->renderWalker->getClasses('row')];

    $ret = $this->getHtmlStartTag();
    $ret .= Html::generateTag('table', $this->attributes);

    if ($this->htmlTitle!==null)
    {
      $ret .= Html::generateTag('thead', $childAttributes);
      $ret .= Html::generateTag('tr', $childAttributes);
      $ret .= Html::generateTag('th', $childAttributes2);
      $ret .= $this->htmlTitle;
      $ret .= '</th>';
      $ret .= '</tr>';
      $ret .= '</thead>';
    }

    $ret .= Html::generateTag('tbody', $childAttributes);
    $key = 0;
    foreach ($this->controls as $control)
    {
      if ($control!==$this->buttonGroupControl)
      {
        $labelAttributes = ['class' => $this->renderWalker->getClasses()];
        if (!empty($this->addendum[$key]['mandatory']))
        {
          $labelAttributes['class'][] = Control::$isMandatoryClass;
        }
        if ($control->isError())
        {
          $labelAttributes['class'][] = Control::$isErrorClass;
        }

        $ret .= Html::generateTag('tr', $rowAttributes);
        $ret .= Html::generateTag('th', $headerAttributes);
        $ret .= Html::generateTag('label', $labelAttributes);
        $ret .= Html::txt2Html($this->addendum[$key]['label']);
        $ret .= '</label>';
        $ret .= '</th>';

        $ret .= Html::generateTag('td', $inputAttributes);
        $ret .= $control->getHtml($walker);

        $errors = $control->getErrorMessages();
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

        $key++;
      }
    }
    $ret .= '</tbody>';

    if ($this->buttonGroupControl!==null)
    {
      $ret .= Html::generateTag('tfoot', $buttonAttributes);
      $ret .= Html::generateTag('tr', $buttonAttributes);
      $ret .= Html::generateTag('td', $buttonAttributes2);
      $ret .= Html::generateTag('div', $buttonAttributes);
      $ret .= $this->buttonGroupControl->getHtml($this->renderWalker);
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
