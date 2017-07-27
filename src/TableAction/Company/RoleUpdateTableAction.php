<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction\Company;

use SetBased\Abc\Core\Page\Company\RoleUpdatePage;
use SetBased\Abc\Core\TableAction\UpdateItemTableAction;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table action for modifying the details to a role.
 */
class RoleUpdateTableAction extends UpdateItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $targetCmpId The ID of the target company of the role.
   * @param int $rolId       The ID of the role.
   */
  public function __construct($targetCmpId, $rolId)
  {
    $this->url = RoleUpdatePage::getUrl($targetCmpId, $rolId);

    $this->title = 'Modify role';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
