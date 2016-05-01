<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\Company;

use SetBased\Abc\Core\Page\Company\CompanyUpdatePage;
use SetBased\Abc\Core\TableColumn\UpdateIconTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column with icon linking to page for updating the details of a company.
 */
class CompanyUpdateIconTableColumn extends UpdateIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getUrl($row)
  {
    return CompanyUpdatePage::getUrl($row['cmp_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
