<?php

namespace SetBased\Abc\Core\TableAction\Company;

use SetBased\Abc\Core\Page\Company\CompanyUpdatePage;
use SetBased\Abc\Core\TableAction\UpdateItemTableAction;

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
