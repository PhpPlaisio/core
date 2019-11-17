<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\System;

use Plaisio\Core\Page\System\MenuInsertPage;
use Plaisio\Core\TableAction\InsertItemTableAction;

/**
 * Table action for inserting a menu entry.
 */
class MenuInsertTableAction extends InsertItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    $this->url = MenuInsertPage::getUrl();

    $this->title = 'Create menu entry';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
