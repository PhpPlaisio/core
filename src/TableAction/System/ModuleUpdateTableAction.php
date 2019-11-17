<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\System;

use Plaisio\Core\Page\System\ModuleUpdatePage;
use Plaisio\Core\TableAction\UpdateItemTableAction;

/**
 * Table action for updating the details of a module.
 */
class ModuleUpdateTableAction extends UpdateItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $mdlId The ID of the module.
   */
  public function __construct($mdlId)
  {
    $this->url = ModuleUpdatePage::getUrl($mdlId);

    $this->title = 'Modify module';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
