<?php

namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\TabPage;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\System\PageUpdateFunctionalitiesTableAction;
use SetBased\Abc\Core\TableAction\System\PageUpdateTableAction;
use SetBased\Abc\Core\TableColumn\Company\CompanyTableColumn;
use SetBased\Abc\Core\TableColumn\Company\FunctionalityTableColumn;
use SetBased\Abc\Core\TableColumn\Company\ModuleTableColumn;
use SetBased\Abc\Core\TableColumn\Company\RoleTableColumn;
use SetBased\Abc\Core\TableRow\System\PageDetailsTableRow;
use SetBased\Abc\Table\TableRow\IntegerTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

/**
 * Page with information about a (target) page.
 */
class PageDetailsPage extends TabPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the target page shown on this page.
   *
   * @var int
   */
  protected $targetPagId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->targetPagId = Abc::$cgi->getManId('tar_pag', 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $pagId The Company shown on this page.
   *
   * @return string
   */
  public static function getUrl(int $pagId): string
  {
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_SYSTEM_PAGE_DETAILS, 'pag');
    $url .= Abc::$cgi->putId('tar_pag', $pagId, 'pag');

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

    $this->showGrantedRoles();
    // XXX Show all child pages (if page is a master page).
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the details of a page.
   */
  private function showDetails(): void
  {
    $details = Abc::$DL->abcSystemPageGetDetails($this->targetPagId, $this->lanId);
    $table   = new CoreDetailTable();

    // Add table action for updating the page details.
    $table->addTableAction('default', new PageUpdateTableAction($this->targetPagId));

    // Add row with the ID of the page.
    IntegerTableRow::addRow($table, 'ID', $details['pag_id']);

    // Add row with the title of the page.
    TextTableRow::addRow($table, 'Title', $details['pag_title']);

    // Add row with the tab name of the page.
    TextTableRow::addRow($table, 'Tab', $details['ptb_name']);

    // Add row with the ID of the parent page of the page.
    PageDetailsTableRow::addRow($table, 'Original Page', $details);

    // Add row with the menu item of the page.
    TextTableRow::addRow($table, 'Menu', $details['mnu_name']);

    // Add row with the alias of the page.
    TextTableRow::addRow($table, 'Alias', $details['pag_alias']);

    // Add row with the class name of the page.
    TextTableRow::addRow($table, 'Class', $details['pag_class']);

    // Add row with the label of the page.
    TextTableRow::addRow($table, 'Label', $details['pag_label']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos functionalities that grant access to the page shown on this page.
   */
  private function showFunctionalities(): void
  {
    $roles = Abc::$DL->abcSystemPageGetGrantedFunctionalities($this->targetPagId, $this->lanId);

    $table = new CoreOverviewTable();

    // Table action for modify the functionalities that grant access to the page whow on this page.
    $table->addTableAction('default', new PageUpdateFunctionalitiesTableAction($this->targetPagId));

    // Show the ID and name of the module.
    $table->addColumn(new ModuleTableColumn('Module'));

    // Show the ID and name of the functionality.
    $table->addColumn(new FunctionalityTableColumn('Functionality'));

    echo $table->getHtmlTable($roles);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos roles that are granted access to the page shown on this page.
   */
  private function showGrantedRoles(): void
  {
    $roles = Abc::$DL->abcSystemPageGetGrantedRoles($this->targetPagId);

    $table = new CoreOverviewTable();

    // Show ID and abbreviation of the company.
    $table->addColumn(new CompanyTableColumn(C::WRD_ID_COMPANY));

    // Show ID and name of the role.
    $table->addColumn(new RoleTableColumn(C::WRD_ID_ROLE));

    echo $table->getHtmlTable($roles);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

