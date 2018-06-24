<?php

namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\TabPage;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\System\ModuleUpdateCompaniesTableAction;
use SetBased\Abc\Core\TableAction\System\ModuleUpdateTableAction;
use SetBased\Abc\Core\TableColumn\Company\CompanyTableColumn;
use SetBased\Abc\Core\TableColumn\Company\FunctionalityTableColumn;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

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

    $this->mdlId = Abc::$cgi->getManId('mdl', 'mdl');

    $this->details = Abc::$DL->abcSystemModuleGetDetails($this->mdlId, $this->lanId);

    Abc::$assets->appendPageTitle($this->details['mdl_name']);
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
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_SYSTEM_MODULE_DETAILS, 'pag');
    $url .= Abc::$cgi->putId('mdl', $mdlId, 'mdl');

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
    $functions = Abc::$DL->abcSystemModuleGetGrantedCompanies($this->mdlId);

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
    NumericTableRow::addRow($table, 'ID', $this->details['mdl_id'], '%d');

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
    $functions = Abc::$DL->abcSystemModuleGetFunctions($this->mdlId, $this->lanId);

    $table = new CoreOverviewTable();

    // Show ID and name of the functionality
    $table->addColumn(new FunctionalityTableColumn('Function'));

    echo $table->getHtmlTable($functions);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
