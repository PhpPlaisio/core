<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\Control;

use Plaisio\Form\Control\ComplexControl;

/**
 * A complex control with buttons.
 */
class CoreButtonControl extends ComplexControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtml(): string
  {
    $html = $this->prefix;
    $html .= '<table class="button">';
    $html .= '<tr>';

    foreach ($this->controls as $control)
    {
      $html .= '<td>';
      $html .= $control->getHtml();
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
