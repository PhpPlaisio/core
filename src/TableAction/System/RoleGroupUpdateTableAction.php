<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\System;

use Plaisio\Core\Page\System\RoleGroupUpdatePage;
use Plaisio\Core\TableAction\UpdateItemTableAction;

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
  public function __construct(int $rlgId)
  {
    $this->url = RoleGroupUpdatePage::getUrl($rlgId);

    $this->title = 'Edit role group';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
