<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\System\FunctionalityUpdatePagesTableAction;
use SetBased\Abc\Core\TableAction\System\FunctionalityUpdateRolesTableAction;
use SetBased\Abc\Core\TableColumn\Company\RoleDetailsIconTableColumn;
use SetBased\Abc\Core\TableColumn\System\PageDetailsIconTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with information about a functionality.
 */
class FunctionalityDetailsPage extends CorePage
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
   * {inheritdoc}
   */
  public function __construct()
  {
    parent::__construct();

    $this->funId = self::getCgiId('fun', 'fun');

    $this->details = Abc::$DL->abcSystemFunctionalityGetDetails($this->funId, $this->lanId);

    $this->appendPageTitle($this->details['fun_name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $funId The ID of the functionality.
   *
   * @return string
   */
  public static function getUrl($funId)
  {
    $url = self::putCgiId('pag', C::PAG_ID_SYSTEM_FUNCTIONALITY_DETAILS, 'pag');
    $url .= self::putCgiId('fun', $funId, 'fun');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->showDetails();

    $this->showPages();

    $this->showRoles();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the details of a functionality.
   */
  private function showDetails()
  {
    $table = new CoreDetailTable();

    // Add row for the ID of the function.
    NumericTableRow::addRow($table, 'ID', $this->details['fun_id'], '%d');

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
  private function showPages()
  {
    $pages = Abc::$DL->abcSystemFunctionalityGetPages($this->funId, $this->lanId);

    $table = new CoreOverviewTable();
    $table->addTableAction('default', new FunctionalityUpdatePagesTableAction($this->funId));

    // Show page ID.
    $table->addColumn(new NumericTableColumn('ID', 'pag_id'));

    // Show class name.
    $col = $table->addColumn(new TextTableColumn('Class', 'pag_class'));
    $col->setSortOrder(1);

    // Show title of page.
    $table->addColumn(new TextTableColumn('Title', 'pag_title'));

    // Show label of the page ID.
    $table->addColumn(new TextTableColumn('Label', 'pag_label'));

    // Show modifying the page.
    $table->addColumn(new PageDetailsIconTableColumn());

    echo $table->getHtmlTable($pages);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Show the roles that are granted the functionality.
   */
  private function showRoles()
  {
    $roles = Abc::$DL->abcSystemFunctionalityGetRoles($this->funId);

    $table = new CoreOverviewTable();

    // Add table action for granting and revoking this functionality to/from roles.
    $table->addTableAction('default', new FunctionalityUpdateRolesTableAction($this->funId));

    // Show Company ID.
    $table->addColumn(new NumericTableColumn('ID', 'cmp_id'));

    // Show Company abbreviation.
    $col = $table->addColumn(new TextTableColumn('Company', 'cmp_abbr'));
    $col->setSortOrder(1);

    // Show role ID.
    $table->addColumn(new NumericTableColumn('ID', 'rol_id'));

    // Show name of the role.
    $table->addColumn(new TextTableColumn('Role', 'rol_name'));

    // Show viewing the details of the role.
    $table->addColumn(new RoleDetailsIconTableColumn());

    echo $table->getHtmlTable($roles);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
