<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\Company;

use Plaisio\Core\Page\Company\CompanyUpdatePage;
use Plaisio\Core\TableAction\UpdateItemTableAction;

/**
 * Table action for updating the details of a company.
 */
class CompanyUpdateTableAction extends UpdateItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $cmpId The ID of the target company.
   */
  public function __construct(int $cmpId)
  {
    $this->url = CompanyUpdatePage::getUrl($cmpId);

    $this->title = 'Modify Company';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
