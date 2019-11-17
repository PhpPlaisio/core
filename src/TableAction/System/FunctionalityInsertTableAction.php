<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\System;

use Plaisio\Core\Page\System\FunctionalityInsertPage;
use Plaisio\Core\TableAction\InsertItemTableAction;

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
