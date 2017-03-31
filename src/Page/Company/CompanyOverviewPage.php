<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\Company\CompanyInsertTableAction;
use SetBased\Abc\Core\TableColumn\Company\CompanyDetailsIconTableColumn;
use SetBased\Abc\Core\TableColumn\Company\CompanyUpdateIconTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
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
  public static function getUrl()
  {
    return self::putCgiId('pag', C::PAG_ID_COMPANY_OVERVIEW, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function echoMainContent()
  {
    CorePage::echoMainContent();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $companies = Abc::$DL->abcCompanyGetAll();

    $table = new CoreOverviewTable();

    // Add table action for creating a new Company.
    $table->addTableAction('default', new CompanyInsertTableAction());

    // Show Company ID.
    $table->addColumn(new NumericTableColumn('ID', 'cmp_id'));

    // Show word group name.
    $col = $table->addColumn(new TextTableColumn('Company abbreviation', 'cmp_abbr'));
    $col->setSortOrder(1);

    // Show total words in the word group.
    $table->addColumn(new TextTableColumn('Label', 'cmp_label'));

    // Add link to the details of the Company.
    $table->addColumn(new CompanyDetailsIconTableColumn());

    // Add link to the update the Company.
    $table->addColumn(new CompanyUpdateIconTableColumn());

    echo $table->getHtmlTable($companies);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

