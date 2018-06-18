<?php

namespace SetBased\Abc\Core\TableAction\System;

use SetBased\Abc\Core\Page\System\PageUpdatePage;
use SetBased\Abc\Core\TableAction\UpdateItemTableAction;

/**
 * Table action for updating the details of a page.
 */
class PageUpdateTableAction extends UpdateItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $pagId The ID of the target page.
   */
  public function __construct(int $pagId)
  {
    $this->url = PageUpdatePage::getUrl($pagId);

    $this->title = 'Modify page';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
