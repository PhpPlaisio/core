<?php

namespace SetBased\Abc\Core\TableAction\Company;

use SetBased\Abc\Core\Page\Company\RoleUpdateFunctionalitiesPage;
use SetBased\Abc\Core\TableAction\UpdateItemTableAction;

/**
 * Table action for modifying the granted functionalities to a role.
 */
class RoleUpdateFunctionalitiesTableAction extends UpdateItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $targetCmpId The ID of the target company of the role.
   * @param int $rolId       The ID of the role.
   */
  public function __construct(int $targetCmpId, int $rolId)
  {
    $this->url = RoleUpdateFunctionalitiesPage::getUrl($targetCmpId, $rolId);

    $this->title = 'Modify functionalities';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
