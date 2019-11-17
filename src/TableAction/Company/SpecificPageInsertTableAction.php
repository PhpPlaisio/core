<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\Company;

use Plaisio\Core\Page\Company\SpecificPageInsertPage;
use Plaisio\Core\TableAction\InsertItemTableAction;

/**
 * Table action inserting a company specific page that overrides a standard page.
 */
class SpecificPageInsertTableAction extends InsertItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $actCmpId The ID of the target company.
   */
  public function __construct(int $actCmpId)
  {
    $this->url = SpecificPageInsertPage::getUrl($actCmpId);

    $this->title = 'Add new page';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
