<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\Company;

use Plaisio\Core\Page\Company\ModuleUpdatePage;
use Plaisio\Core\TableAction\UpdateItemTableAction;

/**
 * Table action for setting the enabled modules of a company.
 */
class ModuleUpdateTableAction extends UpdateItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $cmpId The ID of the target company.
   */
  public function __construct(int $cmpId)
  {
    $this->url = ModuleUpdatePage::getUrl($cmpId);

    $this->title = 'Modify enabled modules';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
