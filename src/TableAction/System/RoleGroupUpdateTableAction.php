<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction\System;

use SetBased\Abc\Core\Page\System\RoleGroupInsertPage;
use SetBased\Abc\Core\Page\System\RoleGroupUpdatePage;
use SetBased\Abc\Core\TableAction\InsertItemTableAction;
use SetBased\Abc\Core\TableAction\UpdateItemTableAction;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table action for updating a role group.
 */
class RoleGroupUpdateTableAction extends UpdateItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $rlgId The ID of the role group.
   */
  public function __construct($rlgId)
  {
    $this->url = RoleGroupUpdatePage::getUrl($rlgId);

    $this->title = 'Edit role group';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
