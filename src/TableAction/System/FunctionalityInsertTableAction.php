<?php

namespace SetBased\Abc\Core\TableAction\System;

use SetBased\Abc\Core\Page\System\FunctionalityInsertPage;
use SetBased\Abc\Core\TableAction\InsertItemTableAction;

/**
 * Table action for creating a functionality.
 */
class FunctionalityInsertTableAction extends InsertItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    $this->url = FunctionalityInsertPage::getUrl();

    $this->title = 'Create functionally';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
