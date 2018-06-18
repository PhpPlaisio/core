<?php

namespace SetBased\Abc\Core\TableAction\System;

use SetBased\Abc\Core\Page\System\FunctionalityUpdateRolesPage;
use SetBased\Abc\Core\TableAction\UpdateItemTableAction;

/**
 * Table action for granting/revoking a functionality from/to roles.
 */
class FunctionalityUpdateRolesTableAction extends UpdateItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $funId The ID of the functionality.
   */
  public function __construct(int $funId)
  {
    $this->url = FunctionalityUpdateRolesPage::getUrl($funId);

    $this->title = 'Grant/revoke';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
