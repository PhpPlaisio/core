<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction\System;

use SetBased\Abc\Core\Page\System\ModuleUpdateCompaniesPage;
use SetBased\Abc\Core\TableAction\UpdateItemTableAction;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table action for granting or revoking a module to companies.
 */
class ModuleUpdateCompaniesTableAction extends UpdateItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $modId The ID of the module.
   */
  public function __construct($modId)
  {
    $this->url = ModuleUpdateCompaniesPage::getUrl($modId);

    $this->title = 'Modify companies';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
