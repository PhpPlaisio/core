<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\System;

use Plaisio\Core\Page\System\PageUpdatePage;
use Plaisio\Core\TableAction\UpdateItemTableAction;

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
