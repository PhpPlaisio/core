<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\System;

use Plaisio\Core\Page\System\PageUpdateFunctionalitiesPage;
use Plaisio\Core\TableAction\UpdateItemTableAction;

/**
 * Table action for modifying the functionalities that grant access to a target page.
 */
class PageUpdateFunctionalitiesTableAction extends UpdateItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $pagId The ID of the target page.
   */
  public function __construct(int $pagId)
  {
    $this->url = PageUpdateFunctionalitiesPage::getUrl($pagId);

    $this->title = 'Modify functionalities';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
