<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\System;

use Plaisio\Core\Page\System\ModuleUpdateCompaniesPage;
use Plaisio\Core\TableAction\UpdateItemTableAction;

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
  public function __construct(int $modId)
  {
    $this->url = ModuleUpdateCompaniesPage::getUrl($modId);

    $this->title = 'Modify companies';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
