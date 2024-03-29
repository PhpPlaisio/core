<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\Company\ModuleUpdateTableAction;
use Plaisio\Core\TableColumn\System\ModuleTableColumn;
use Plaisio\Kernel\Nub;

/**
 * Page with an overview of the enabled modules of a company.
 */
class ModuleOverviewPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @param int $targetCmpId The ID of the target company.
   *
   * @return string
   */
  public static function getUrl(int $targetCmpId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_COMPANY_MODULE_OVERVIEW, 'pag');
    $url .= Nub::$nub->cgi->putId('cmp', $targetCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function htmlTabContent(): ?string
  {
    $modules = Nub::$nub->DL->abcCompanyModuleGetAllEnabled($this->targetCmpId, $this->lanId);

    $table = new CoreOverviewTable();

    // Add table action for modifying the enabled modules of the target company.
    $table->addTableAction('default', new ModuleUpdateTableAction($this->targetCmpId));

    // Show the ID and the name of the module.
    $column = new ModuleTableColumn('Module');
    $column->setSortOrder1(1);
    $table->addColumn($column);

    // Generate the HTML code for the table.
    return $table->htmlTable($modules);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
