<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction\System;

use SetBased\Abc\Core\Page\System\FunctionalityUpdatePagesPage;
use SetBased\Abc\Core\TableAction\UpdateItemTableAction;

//----------------------------------------------------------------------------------------------------------------------
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
  public function __construct($funId)
  {
    $this->url = FunctionalityUpdatePagesPage::getUrl($funId);

    $this->title = 'Modify functionality';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
