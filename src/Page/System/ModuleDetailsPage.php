<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Page\TabPage;
use Plaisio\Core\Table\CoreDetailTable;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\System\ModuleUpdateCompaniesTableAction;
use Plaisio\Core\TableAction\System\ModuleUpdateTableAction;
use Plaisio\Core\TableColumn\Company\CompanyTableColumn;
use Plaisio\Core\TableColumn\Company\FunctionalityTableColumn;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableRow\IntegerTableRow;
use Plaisio\Table\TableRow\TextTableRow;

/**
 * Page with the details of a module.
 */
class ModuleDetailsPage extends TabPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the functionality.
   *
   * @var array
   */
  private $details;

  /**
   * The ID of the functionality.
   *
   * @var int
   */
  private $mdlId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->mdlId = Nub::$cgi->getManId('mdl', 'mdl');

    $this->details = Nub::$DL->abcSystemModuleGetDetails($this->mdlId, $this->lanId);

    Nub::$assets->appendPageTitle($this->details['mdl_name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $mdlId The ID of the module.
   *
   * @return string
   */
  public static function getUrl(int $mdlId): string
  {
    $url = Nub::$cgi->putLeader();
    $url .= Nub::$cgi->putId('pag', C::PAG_ID_SYSTEM_MODULE_DETAILS, 'pag');
    $url .= Nub::$cgi->putId('mdl', $mdlId, 'mdl');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $this->showDetails();

    $this->showFunctionalities();

    $this->showCompanies();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos an overview table with all companies that are granted this module.
   */
  private function showCompanies(): void
  {
    $functions = Nub::$DL->abcSystemModuleGetGrantedCompanies($this->mdlId);

    $table = new CoreOverviewTable();

    // Add table action for granting this module to companies.
    $table->addTableAction('default', new ModuleUpdateCompaniesTableAction($this->mdlId));

    // Show ID and abbreviation of the company
    $table->addColumn(new CompanyTableColumn(C::WRD_ID_COMPANY));

    echo $table->getHtmlTable($functions);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the details the module.
   */
  private function showDetails(): void
  {
    $table = new CoreDetailTable();

    // Add table action for updating the module details.
    $table->addTableAction('default', new ModuleUpdateTableAction($this->mdlId));

    // Add row for the ID of the module.
    IntegerTableRow::addRow($table, 'ID', $this->details['mdl_id']);

    // Add row for the name of the module.
    TextTableRow::addRow($table, 'Module', $this->details['mdl_name']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos an overview table with all functionalities provides by the module.
   */
  private function showFunctionalities(): void
  {
    $functions = Nub::$DL->abcSystemModuleGetFunctions($this->mdlId, $this->lanId);

    $table = new CoreOverviewTable();

    // Show ID and name of the functionality
    $table->addColumn(new FunctionalityTableColumn('Function'));

    echo $table->getHtmlTable($functions);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
