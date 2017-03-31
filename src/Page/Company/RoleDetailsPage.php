<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\Company\RoleUpdateFunctionalitiesTableAction;
use SetBased\Abc\Core\TableColumn\System\FunctionalityDetailsIconTableColumn;
use SetBased\Abc\Core\TableColumn\System\PageDetailsIconTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with information about a role.
 */
class RoleDetailsPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the role of which data is shown on this page.
   *
   * @var int
   */
  protected $rolId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->rolId = self::getCgiId('rol', 'rol');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $targetCmpId The ID of the target company.
   * @param int $rolId       The ID of the role.
   *
   * @return string
   */
  public static function getUrl($targetCmpId, $rolId)
  {
    $url = self::putCgiId('pag', C::PAG_ID_COMPANY_ROLE_DETAILS, 'pag');
    $url .= self::putCgiId('cmp', $targetCmpId, 'cmp');
    $url .= self::putCgiId('rol', $rolId, 'rol');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->showRole();

    $this->showFunctionalities();

    $this->showPages();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Shows the functionalities that are granted to the role shown on this page.
   */
  private function showFunctionalities()
  {
    $functionalities = Abc::$DL->abcCompanyRoleGetFunctionalities($this->targetCmpId, $this->rolId, $this->lanId);

    $table = new CoreOverviewTable();

    // Add table action for modifying the granted functionalities.
    $table->addTableAction('default', new RoleUpdateFunctionalitiesTableAction($this->targetCmpId, $this->rolId));

    // Show the ID of the module.
    $table->addColumn(new NumericTableColumn('ID', 'mdl_id'));

    // Show name of module.
    $col = $table->addColumn(new TextTableColumn('Module', 'mdl_name'));
    $col->setSortOrder(1);

    // Show the ID of the functionality.
    $table->addColumn(new NumericTableColumn('ID', 'fun_id'));

    // Show name of functionality.
    $col = $table->addColumn(new TextTableColumn('Functionality', 'fun_name'));
    $col->setSortOrder(2);

    // Add column with icon a link to view the details of the functionality.
    $table->addColumn(new FunctionalityDetailsIconTableColumn());

    // Generate the HTML code for the table.
    echo $table->getHtmlTable($functionalities);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Show the pages that the functionality shown on this page grants access to.
   */
  private function showPages()
  {
    $pages = Abc::$DL->abcCompanyRoleGetPages($this->targetCmpId, $this->rolId, $this->lanId);

    $table = new CoreOverviewTable();

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
   * Echos brief info about the role.
   */
  private function showRole()
  {
    $details = Abc::$DL->abcCompanyRoleGetDetails($this->targetCmpId, $this->rolId);

    $table = new CoreDetailTable();

    // @todo Add table action for update the company details.

    // Add row for role ID.
    NumericTableRow::addRow($table, 'ID', $details['rol_id'], '%d');

    // Add row for role name.
    TextTableRow::addRow($table, 'Role', $details['rol_name']);

    /// Add row for weight.
    NumericTableRow::addRow($table, 'Weight', $details['rol_weight'], '%d');

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

