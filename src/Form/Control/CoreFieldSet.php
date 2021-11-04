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
   * @param Control         $control   The form control added.
   * @param int|string|null $label     The label of the form control.
   * @param bool            $mandatory Whether the form control is mandatory.
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
    $struct = ['tag'   => 'fieldset',
               'attr'  => ['class' => $this->renderWalker->getClasses('fieldset-visible')],
               'inner' => ['tag'   => 'table',
                           'attr'  => ['class' => $this->renderWalker->getClasses('table')],
                           'inner' => [$this->getStructHead(),
                                       $this->getStructBody($walker),
                                       $this->getStructFoot()]]];

    return Html::generateNested($struct);
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
  /**
   * Returns the structure of the table body.
   *
   * @param RenderWalker $walker The render walker for form controls.
   *
   * @return array
   */
  private function getStructBody(RenderWalker $walker): array
  {
    return ['tag'   => 'body',
            'attr'  => ['class' => $this->renderWalker->getClasses('body')],
            'inner' => $this->getStructRows($walker)];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the structure of the error messages of a form control.
   *
   * @param Control $control the form control.
   *
   * @return array|null
   */
  private function getStructErrors(Control $control): ?array
  {
    $errors = $control->getErrorMessages();

    if (empty($errors)) return null;

    $messages = [];
    foreach ($errors as $error)
    {
      $messages[] = ['tag'  => 'span',
                     'attr' => ['class' => $this->renderWalker->getClasses('error-message')],
                     'text' => $error];
    }

    return ['tag'   => 'div',
            'attr'  => ['class' => $this->renderWalker->getClasses('error-messages')],
            'inner' => $messages];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the structure for the table header.
   *
   * @return array|null
   */
  private function getStructFoot(): ?array
  {
    if ($this->buttonGroupControl===null) return null;

    return ['tag'   => 'tfoot',
            'attr'  => ['class' => $this->renderWalker->getClasses('buttons-foot')],
            'inner' => ['tag'   => 'tr',
                        'attr'  => ['class' => $this->renderWalker->getClasses('buttons-row')],
                        'inner' => ['tag'   => 'td',
                                    'attr'  => ['class'   => $this->renderWalker->getClasses('buttons-cell'),
                                                'colspan' => 2],
                                    'inner' => ['tag'  => 'div',
                                                'attr' => ['class' => $this->renderWalker->getClasses('button-cell-wrapper')],
                                                'html' => $this->buttonGroupControl->getHtml($this->renderWalker)]]]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the structure for the table footer.
   *
   * @return array|null
   */
  private function getStructHead(): ?array
  {
    if ($this->htmlTitle===null) return null;

    return ['tag'   => 'thead',
            'attr'  => ['class' => $this->renderWalker->getClasses('title-head')],
            'inner' => ['tag'   => 'tr',
                        'attr'  => ['class' => $this->renderWalker->getClasses('title-row')],
                        'inner' => ['tag'  => 'th',
                                    'attr' => ['class'   => $this->renderWalker->getClasses('title-cell'),
                                               'colspan' => 2],
                                    'html' => $this->htmlTitle]]];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return the structure of the table rows.
   *
   * @param RenderWalker $walker The render walker for form controls.
   *
   * @return array
   */
  private function getStructRows(RenderWalker $walker): array
  {
    $struct = [];

    $key = 0;
    foreach ($this->controls as $control)
    {
      if ($control!==$this->buttonGroupControl)
      {
        $classes = [];
        if (!empty($this->addendum[$key]['mandatory']))
        {
          $classes[] = Control::$isMandatoryClass;
        }
        if ($control->isError())
        {
          $classes[] = Control::$isErrorClass;
        }

        $struct[] = ['tag'   => 'tr',
                     'attr'  => ['class' => $this->renderWalker->getClasses('row')],
                     'inner' => [['tag'   => 'th',
                                  'attr'  => ['class' => $this->renderWalker->getClasses('header')],
                                  'inner' => ['tag'  => 'label',
                                              'attr' => ['class' => $this->renderWalker->getClasses('label', $classes)],
                                              'text' => $this->addendum[$key]['label']]],
                                 ['tag'   => 'td',
                                  'attr'  => ['class' => $this->renderWalker->getClasses('cell')],
                                  'inner' => [['html' => $control->getHtml($walker)],
                                              $this->getStructErrors($control)]]]];

        $key++;
      }
    }

    return $struct;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
