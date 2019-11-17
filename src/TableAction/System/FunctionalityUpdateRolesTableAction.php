<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\System;

use Plaisio\Core\Page\System\FunctionalityUpdateRolesPage;
use Plaisio\Core\TableAction\UpdateItemTableAction;

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
