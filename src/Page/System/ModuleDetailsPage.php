<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\System\ModuleUpdateCompaniesTableAction;
use SetBased\Abc\Core\TableAction\System\ModuleUpdateTableAction;
use SetBased\Abc\Core\TableColumn\Company\CompanyDetailsIconTableColumn;
use SetBased\Abc\Core\TableColumn\System\FunctionalityDetailsIconTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with the details of a module.
 */
class ModuleDetailsPage extends CorePage
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

    $this->mdlId = self::getCgiId('mdl', 'mdl');

    $this->details = Abc::$DL->abcSystemModuleGetDetails($this->mdlId, $this->lanId);

    $this->appendPageTitle($this->details['mdl_name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $mdlId The ID of the module.
   *
   * @return string
   */
  public static function getUrl($mdlId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_SYSTEM_MODULE_DETAILS, 'pag');
    $url .= self::putCgiVar('mdl', $mdlId, 'mdl');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->showDetails();

    $this->showFunctionalities();

    $this->showCompanies();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos an overview table with all companies that are granted this module.
   */
  private function showCompanies()
  {
    $functions = Abc::$DL->abcSystemModuleGetGrantedCompanies($this->mdlId);

    $table = new CoreOverviewTable();

    // Add table action for granting this module to companies.
    $table->addTableAction('default', new ModuleUpdateCompaniesTableAction($this->mdlId));

    // Show company ID.
    $table->addColumn(new NumericTableColumn('ID', 'cmp_id'));

    // Show company abbr.
    $table->addColumn(new TextTableColumn('Company', 'cmp_abbr'));

    // Show link to view the details of the company.
    $table->addColumn(new CompanyDetailsIconTableColumn());

    echo $table->getHtmlTable($functions);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the details the module.
   */
  private function showDetails()
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
  private function showFunctionalities()
  {
    $functions = Abc::$DL->abcSystemModuleGetFunctions($this->mdlId, $this->lanId);

    $table = new CoreOverviewTable();

    // Show function ID.
    $table->addColumn(new NumericTableColumn('ID', 'fun_id'));

    // Show function name.
    $table->addColumn(new TextTableColumn('Function', 'fun_name'));

    // Show link to view the details of the functionality.
    $table->addColumn(new FunctionalityDetailsIconTableColumn());

    echo $table->getHtmlTable($functions);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
