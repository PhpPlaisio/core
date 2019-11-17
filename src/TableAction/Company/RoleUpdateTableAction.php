<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\Company;

use Plaisio\Core\Page\Company\RoleUpdatePage;
use Plaisio\Core\TableAction\UpdateItemTableAction;

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
  public function __construct(int $targetCmpId, int $rolId)
  {
    $this->url = RoleUpdatePage::getUrl($targetCmpId, $rolId);

    $this->title = 'Modify role';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
