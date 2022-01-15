<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\Company\SpecificPageInsertTableAction;
use Plaisio\Core\TableColumn\Company\SpecificPageDeleteIconTableColumn;
use Plaisio\Core\TableColumn\Company\SpecificPageUpdateIconTableColumn;
use Plaisio\Core\TableColumn\System\PageDetailsIconTableColumn;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableColumn\NumberTableColumn;
use Plaisio\Table\TableColumn\TextTableColumn;

/**
 * Page with an overview of all company specific pages for the target company.
 */
class SpecificPageOverviewPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the company specific pages.
   *
   * @var array[]
   */
  private array $pages;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->pages = Nub::$nub->DL->abcCompanySpecificPageGetAll($this->targetCmpId, $this->lanId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @param int $targetCmpId The ID of the target company.
   *
   * @return string The URL of this page.
   */
  public static function getUrl(int $targetCmpId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_COMPANY_SPECIFIC_PAGE_OVERVIEW, 'pag');
    $url .= Nub::$nub->cgi->putId('cmp', $targetCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $table = new CoreOverviewTable();

    $table->addTableAction('default', new SpecificPageInsertTableAction($this->targetCmpId));

    // Add column with ID and class of the parent page.
    $table->addColumn(new NumberTableColumn('ID', 'pag_id'));

    // Add column with page title.
    $table->addColumn(new TextTableColumn('Title', 'pag_title'));

    // Add column with name of parent class.
    $column = new TextTableColumn('Parent Class', 'pag_class_parent');
    $column->setSortOrder(1);
    $table->addColumn($column);

    // Add column with name of child class.
    $table->addColumn(new TextTableColumn('Child Class', 'pag_class_child'));

    // Show link to the details of the page.
    $table->addColumn(new PageDetailsIconTableColumn());

    // Show link to modify Company specific page.
    $table->addColumn(new SpecificPageUpdateIconTableColumn($this->targetCmpId));

    // Show link to delete Company specific page.
    $table->addColumn(new SpecificPageDeleteIconTableColumn($this->targetCmpId));

    echo $table->htmlTable($this->pages);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
