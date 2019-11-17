<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\System;

use Plaisio\Core\Page\System\PageInsertPage;
use Plaisio\Core\TableAction\InsertItemTableAction;

/**
 * Table action for inserting a page.
 */
class PageInsertTableAction extends InsertItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    $this->url = PageInsertPage::getUrl();

    $this->title = 'Create page';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
