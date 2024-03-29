<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Core\Page\CoreCorePage;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\Company\CompanyInsertTableAction;
use Plaisio\Core\TableColumn\Company\CompanyTableColumn;
use Plaisio\Core\TableColumn\Company\CompanyUpdateIconTableColumn;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableColumn\TextTableColumn;

/**
 * Page with an overview of all companies.
 */
class CompanyOverviewPage extends CoreCorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @return string
   */
  public static function getUrl(): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_COMPANY_OVERVIEW, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function htmlTabContent(): ?string
  {
    $companies = Nub::$nub->DL->abcCompanyGetAll();

    $table = new CoreOverviewTable();

    // Add table action for creating a new Company.
    $table->addTableAction('default', new CompanyInsertTableAction());

    // Show company ID and abbreviation of the company.
    $column = new CompanyTableColumn(C::WRD_ID_COMPANY);
    $column->setSortOrder1(1);
    $table->addColumn($column);

    // Show label of the company.
    $table->addColumn(new TextTableColumn('Label', 'cmp_label'));

    // Add link to the update the company.
    $table->addColumn(new CompanyUpdateIconTableColumn());

    return $table->htmlTable($companies);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * CompanyOverviewPage constructor.
   */
  protected function structSecondaryMenu(): ?array
  {
    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

