<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Form\Control;

use SetBased\Abc\Form\Control\ComplexControl;

//----------------------------------------------------------------------------------------------------------------------
/**
 * A complex control with buttons.
 */
class CoreButtonControl extends ComplexControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function generate()
  {
    $html = $this->prefix;
    $html .= '<table class="button">';
    $html .= '<tr>';

    foreach ($this->controls as $control)
    {
      $html .= '<td>';
      $html .= $control->generate();
      $html .= '</td>';
    }

    $html .= '</tr>';
    $html .= '</table>';
    $html .= $this->postfix;

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
