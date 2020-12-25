<?php
declare(strict_types=1);

namespace Plaisio\Core\Form\Control;

use Plaisio\Form\Control\IntegerControl;

/**
 * Control for weights (for sorting).
 */
class WeightControl extends IntegerControl
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|null $name The name of this form control.
   */
  public function __construct(?string $name)
  {
    parent::__construct($name);

    $this->setAttrMin(-10000);
    $this->setAttrMax(10000);
    $this->setAttrMaxLength(6);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
