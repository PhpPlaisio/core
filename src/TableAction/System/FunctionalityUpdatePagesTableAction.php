<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\System;

use Plaisio\Core\Page\System\FunctionalityUpdatePagesPage;
use Plaisio\Core\TableAction\UpdateItemTableAction;

/**
 * Table action for updating the details of a functionality.
 */
class FunctionalityUpdatePagesTableAction extends UpdateItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $funId The ID of the functionality.
   */
  public function __construct(int $funId)
  {
    $this->url = FunctionalityUpdatePagesPage::getUrl($funId);

    $this->title = 'Modify functionality';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
