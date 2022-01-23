<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Html\VerticalLayout;
use Plaisio\Core\Page\CoreCorePage;
use Plaisio\Core\Table\CoreDetailTable;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\System\FunctionalityUpdatePagesTableAction;
use Plaisio\Core\TableAction\System\FunctionalityUpdateRolesTableAction;
use Plaisio\Core\TableColumn\Company\CompanyTableColumn;
use Plaisio\Core\TableColumn\Company\RoleTableColumn;
use Plaisio\Core\TableColumn\System\PageTableColumn;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableColumn\TextTableColumn;
use Plaisio\Table\TableRow\IntegerTableRow;
use Plaisio\Table\TableRow\TextTableRow;

/**
 * Page with information about a functionality.
 */
class FunctionalityDetailsPage extends CoreCorePage
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
  private int $funId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function __construct()
  {
    parent::__construct();

    $this->funId = Nub::$nub->cgi->getManId('fun', 'fun');

    $this->details = Nub::$nub->DL->abcSystemFunctionalityGetDetails($this->funId, $this->lanId);

    Nub::$nub->assets->appendPageTitle($this->details['fun_name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $funId The ID of the functionality.
   *
   * @return string
   */
  public static function getUrl(int $funId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_FUNCTIONALITY_DETAILS, 'pag');
    $url .= Nub::$nub->cgi->putId('fun', $funId, 'fun');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function htmlTabContent(): ?string
  {
    $layout = new VerticalLayout();
    $layout->addBlock($this->htmlCompanies())
           ->addBlock($this->htmlDetails())
           ->addBlock($this->htmlPages())
           ->addBlock($this->htmlRoles());

    return $layout->html();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an overview table with all companies that are granted this module.
   */
  private function htmlCompanies(): string
  {
    $companies = Nub::$nub->DL->abcSystemFunctionalityGetGrantedCompanies($this->funId);

    $table = new CoreOverviewTable();

    // Show ID and abbreviation of the company
    $table->addColumn(new CompanyTableColumn(C::WRD_ID_COMPANY));

    return $table->htmlTable($companies);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the details of a functionality.
   */
  private function htmlDetails(): string
  {
    $table = new CoreDetailTable();

    // Add row for the ID of the function.
    IntegerTableRow::addRow($table, 'ID', $this->details['fun_id']);

    // Add row for the module name to which the function belongs.
    TextTableRow::addRow($table, 'Module', $this->details['mdl_name']);

    // Add row for the name of the function.
    TextTableRow::addRow($table, 'Functionality', $this->details['fun_name']);

    return $table->htmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the pages that the functionality grants access to.
   */
  private function htmlPages(): string
  {
    $pages = Nub::$nub->DL->abcSystemFunctionalityGetPages($this->funId, $this->lanId);

    $table = new CoreOverviewTable();
    $table->addTableAction('default', new FunctionalityUpdatePagesTableAction($this->funId));

    // Show the ID and class of the page.
    $column = new PageTableColumn('Page');
    $column->setSortOrder1(1);
    $table->addColumn($column);

    // Show title of page.
    $table->addColumn(new TextTableColumn('Title', 'pag_title'));

    // Show label of the page ID.
    $table->addColumn(new TextTableColumn('Label', 'pag_label'));

    return $table->htmlTable($pages);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the roles that are granted the functionality.
   */
  private function htmlRoles(): string
  {
    $roles = Nub::$nub->DL->abcSystemFunctionalityGetRoles($this->funId);

    $table = new CoreOverviewTable();

    // Add table action for granting and revoking this functionality to/from roles.
    $table->addTableAction('default', new FunctionalityUpdateRolesTableAction($this->funId));

    // Show ID and abbreviation of the company.
    $column = new CompanyTableColumn(C::WRD_ID_COMPANY);
    $column->setSortOrder1(1);
    $table->addColumn($column);

    // Show role ID and name
    $table->addColumn(new RoleTableColumn(C::WRD_ID_ROLE));

    return $table->htmlTable($roles);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
