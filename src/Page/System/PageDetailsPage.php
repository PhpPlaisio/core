<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Html\VerticalLayout;
use Plaisio\Core\Page\CoreCorePage;
use Plaisio\Core\Table\CoreDetailTable;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\System\PageUpdateFunctionalitiesTableAction;
use Plaisio\Core\TableAction\System\PageUpdateTableAction;
use Plaisio\Core\TableColumn\Company\CompanyTableColumn;
use Plaisio\Core\TableColumn\Company\RoleTableColumn;
use Plaisio\Core\TableColumn\System\FunctionalityTableColumn;
use Plaisio\Core\TableColumn\System\ModuleTableColumn;
use Plaisio\Core\TableRow\System\PageDetailsTableRow;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableRow\IntegerTableRow;
use Plaisio\Table\TableRow\TextTableRow;

/**
 * Page with information about a (target) page.
 */
class PageDetailsPage extends CoreCorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the target page shown on this page.
   *
   * @var int
   */
  protected int $pagIdTarget;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->pagIdTarget = Nub::$nub->cgi->getManId('pag-target', 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $pagIdTarget The ID of the target page.
   *
   * @return string
   */
  public static function getUrl(int $pagIdTarget): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_PAGE_DETAILS, 'pag');
    $url .= Nub::$nub->cgi->putId('pag-target', $pagIdTarget, 'pag');

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
           ->addBlock($this->htmlFunctionalities())
           ->addBlock($this->htmlDetails())
           ->addBlock($this->htmlGrantedRoles());

    return $layout->html();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns an overview table with all companies that are granted this page.
   */
  private function htmlCompanies(): string
  {
    $companies = Nub::$nub->DL->abcSystemPageGetGrantedCompanies($this->pagIdTarget);

    $table = new CoreOverviewTable();

    // Show ID and abbreviation of the company
    $table->addColumn(new CompanyTableColumn(C::WRD_ID_COMPANY));

    return $table->htmlTable($companies);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the details of a page.
   */
  private function htmlDetails(): string
  {
    $details = Nub::$nub->DL->abcSystemPageGetDetails($this->pagIdTarget, $this->lanId);
    $table   = new CoreDetailTable();

    // Add table action for updating the page details.
    $table->addTableAction('default', new PageUpdateTableAction($this->pagIdTarget));

    // Add row with the ID of the page.
    IntegerTableRow::addRow($table, 'ID', $details['pag_id']);

    // Add row with the title of the page.
    TextTableRow::addRow($table, 'Title', $details['pag_title']);

    // Add row with the tab name of the page.
    TextTableRow::addRow($table, 'Tab', $details['ptb_name']);

    // Add row with the ID of the parent page of the page.
    PageDetailsTableRow::addRow($table, 'Original Page', $details);

    // Add row with the alias of the page.
    TextTableRow::addRow($table, 'Alias', $details['pag_alias']);

    // Add row with the class name of the page.
    TextTableRow::addRow($table, 'Class', $details['pag_class']);

    // Add row with the label of the page.
    TextTableRow::addRow($table, 'Label', $details['pag_label']);

    return $table->htmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns functionalities that grant access to the page shown on this page.
   */
  private function htmlFunctionalities(): string
  {
    $roles = Nub::$nub->DL->abcSystemPageGetGrantedFunctionalities($this->pagIdTarget, $this->lanId);

    $table = new CoreOverviewTable();

    // Table action for modify the functionalities that grant access to the page shown on this page.
    $table->addTableAction('default', new PageUpdateFunctionalitiesTableAction($this->pagIdTarget));

    // Show the ID and name of the module.
    $table->addColumn(new ModuleTableColumn('Module'));

    // Show the ID and name of the functionality.
    $table->addColumn(new FunctionalityTableColumn('Functionality'));

    return $table->htmlTable($roles);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns roles that are granted access to the page shown on this page.
   */
  private function htmlGrantedRoles(): string
  {
    $roles = Nub::$nub->DL->abcSystemPageGetGrantedRoles($this->pagIdTarget);

    $table = new CoreOverviewTable();

    // Show ID and abbreviation of the company.
    $table->addColumn(new CompanyTableColumn(C::WRD_ID_COMPANY));

    // Show ID and name of the role.
    $table->addColumn(new RoleTableColumn(C::WRD_ID_ROLE));

    return $table->htmlTable($roles);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

