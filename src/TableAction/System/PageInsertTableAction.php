<?php

namespace SetBased\Abc\Core\TableAction\System;

use SetBased\Abc\Core\Page\System\PageInsertPage;
use SetBased\Abc\Core\TableAction\InsertItemTableAction;

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
