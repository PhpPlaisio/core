<?php
declare(strict_types=1);

namespace Plaisio\Core\Html;

/**
 * Layout element for placing blocks in a horizontal layout.
 */
class HorizontalLayout extends Layout
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    $this->class = 'l-horizontal';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
