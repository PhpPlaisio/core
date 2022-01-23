<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Html\VerticalLayout;
use Plaisio\Core\Page\CoreCorePage;
use Plaisio\Core\Table\CoreDetailTable;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\System\ModuleUpdateCompaniesTableAction;
use Plaisio\Core\TableAction\System\ModuleUpdateTableAction;
use Plaisio\Core\TableColumn\Company\CompanyTableColumn;
use Plaisio\Core\TableColumn\System\FunctionalityTableColumn;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableRow\IntegerTableRow;
use Plaisio\Table\TableRow\TextTableRow;

/**
 * Page with the details of a module.
 */
class ModuleDetailsPage extends CoreCorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the functionality.
   *
   * @var array
   */
  private array $details;

  /**
   * The ID of the functionality.
   *
   * @var int
   */
  private int $mdlId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->mdlId = Nub::$nub->cgi->getManId('mdl', 'mdl');

    $this->details = Nub::$nub->DL->abcSystemModuleGetDetails($this->mdlId, $this->lanId);

    Nub::$nub->assets->appendPageTitle($this->details['mdl_name']);
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
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_MODULE_DETAILS, 'pag');
    $url .= Nub::$nub->cgi->putId('mdl', $mdlId, 'mdl');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function htmlTabContent(): ?string
  {
    $layout = new VerticalLayout();
    $layout->addBlock($this->showCompanies())
           ->addBlock($this->showDetails())
           ->addBlock($this->showFunctionalities());

    return $layout->html();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an overview table with all companies that are granted this module.
   */
  private function showCompanies(): string
  {
    $companies = Nub::$nub->DL->abcSystemModuleGetGrantedCompanies($this->mdlId);

    $table = new CoreOverviewTable();

    // Add table action for granting this module to companies.
    $table->addTableAction('default', new ModuleUpdateCompaniesTableAction($this->mdlId));

    // Show ID and abbreviation of the company
    $table->addColumn(new CompanyTableColumn(C::WRD_ID_COMPANY));

    return $table->htmlTable($companies);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the details the module.
   */
  private function showDetails(): string
  {
    $table = new CoreDetailTable();

    // Add table action for updating the module details.
    $table->addTableAction('default', new ModuleUpdateTableAction($this->mdlId));

    // Add row for the ID of the module.
    IntegerTableRow::addRow($table, 'ID', $this->details['mdl_id']);

    // Add row for the name of the module.
    TextTableRow::addRow($table, 'Module', $this->details['mdl_name']);

    return $table->htmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an overview table with all functionalities provides by the module.
   */
  private function showFunctionalities(): string
  {
    $functions = Nub::$nub->DL->abcSystemModuleGetFunctions($this->mdlId, $this->lanId);

    $table = new CoreOverviewTable();

    // Show ID and name of the functionality
    $table->addColumn(new FunctionalityTableColumn('Function'));

    return $table->htmlTable($functions);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
