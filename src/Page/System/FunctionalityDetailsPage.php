<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Page\TabPage;
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
class FunctionalityDetailsPage extends TabPage
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
  private $funId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function __construct()
  {
    parent::__construct();

    $this->funId = Nub::$cgi->getManId('fun', 'fun');

    $this->details = Nub::$DL->abcSystemFunctionalityGetDetails($this->funId, $this->lanId);

    Nub::$assets->appendPageTitle($this->details['fun_name']);
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
    $url = Nub::$cgi->putLeader();
    $url .= Nub::$cgi->putId('pag', C::PAG_ID_SYSTEM_FUNCTIONALITY_DETAILS, 'pag');
    $url .= Nub::$cgi->putId('fun', $funId, 'fun');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $this->showDetails();

    $this->showPages();

    $this->showRoles();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the details of a functionality.
   */
  private function showDetails(): void
  {
    $table = new CoreDetailTable();

    // Add row for the ID of the function.
    IntegerTableRow::addRow($table, 'ID', $this->details['fun_id']);

    // Add row for the module name to which the function belongs.
    TextTableRow::addRow($table, 'Module', $this->details['mdl_name']);

    // Add row for the name of the function.
    TextTableRow::addRow($table, 'Functionality', $this->details['fun_name']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the pages that the functionality grants access to.
   */
  private function showPages(): void
  {
    $pages = Nub::$DL->abcSystemFunctionalityGetPages($this->funId, $this->lanId);

    $table = new CoreOverviewTable();
    $table->addTableAction('default', new FunctionalityUpdatePagesTableAction($this->funId));

    // Show the ID and class of the page.
    $column = new PageTableColumn('Page');
    $column->setSortOrder(1);
    $table->addColumn($column);

    // Show title of page.
    $table->addColumn(new TextTableColumn('Title', 'pag_title'));

    // Show label of the page ID.
    $table->addColumn(new TextTableColumn('Label', 'pag_label'));

    echo $table->getHtmlTable($pages);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Show the roles that are granted the functionality.
   */
  private function showRoles(): void
  {
    $roles = Nub::$DL->abcSystemFunctionalityGetRoles($this->funId);

    $table = new CoreOverviewTable();

    // Add table action for granting and revoking this functionality to/from roles.
    $table->addTableAction('default', new FunctionalityUpdateRolesTableAction($this->funId));

    // Show ID and abbreviation of the company.
    $column = new CompanyTableColumn(C::WRD_ID_COMPANY);
    $column->setSortOrder(1);
    $table->addColumn($column);

    // Show role ID and name
    $table->addColumn(new RoleTableColumn(C::WRD_ID_ROLE));

    echo $table->getHtmlTable($roles);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
