<?php

namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\TabPage;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\Company\CompanyInsertTableAction;
use SetBased\Abc\Core\TableColumn\Company\CompanyTableColumn;
use SetBased\Abc\Core\TableColumn\Company\CompanyUpdateIconTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

/**
 * Page with an overview of all companies.
 */
class CompanyOverviewPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @return string
   */
  public static function getUrl(): string
  {
    return self::putCgiId('pag', C::PAG_ID_COMPANY_OVERVIEW, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function echoMainContent(): void
  {
    TabPage::echoMainContent();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $companies = Abc::$DL->abcCompanyGetAll();

    $table = new CoreOverviewTable();

    // Add table action for creating a new Company.
    $table->addTableAction('default', new CompanyInsertTableAction());

    // Show company ID and abbreviation of the company.
    $column = new CompanyTableColumn(C::WRD_ID_COMPANY);
    $column->setSortOrder(1);
    $table->addColumn($column);

    // Show label of the company.
    $table->addColumn(new TextTableColumn('Label', 'cmp_label'));

    // Add link to the update the company.
    $table->addColumn(new CompanyUpdateIconTableColumn());

    echo $table->getHtmlTable($companies);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

