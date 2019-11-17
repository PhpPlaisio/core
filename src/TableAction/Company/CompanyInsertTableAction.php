<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\Company;

use Plaisio\Core\Page\Company\CompanyInsertPage;
use Plaisio\Core\TableAction\InsertItemTableAction;

/**
 * Table action for inserting a company.
 */
class CompanyInsertTableAction extends InsertItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    $this->url = CompanyInsertPage::getUrl();

    $this->title = 'Create Company';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
