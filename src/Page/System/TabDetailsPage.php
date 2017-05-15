<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableColumn\System\PageDetailsIconTableColumn;
use SetBased\Abc\Core\TableColumn\System\PageTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with information about a page group.
 */
class TabDetailsPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the page group shown on this page.
   *
   * @var int
   */
  protected $tabId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->tabId = self::getCgiId('ptb', 'ptb');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $tabId The ID of the tab shown on this page.
   *
   * @return string
   */
  public static function getUrl($tabId)
  {
    $url = self::putCgiId('pag', C::PAG_ID_SYSTEM_TAB_DETAILS, 'pag');
    $url .= self::putCgiId('ptb', $tabId, 'ptb');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->showDetails();

    $this->showMasterPages();
    // XXX Show all pages.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the details of the page group.
   */
  private function showDetails()
  {
    $details = Abc::$DL->abcSystemTabGetDetails($this->tabId, $this->lanId);
    $table   = new CoreDetailTable();

    // Add row with the ID of the tab.
    NumericTableRow::addRow($table, 'ID', $details['ptb_id'], '%d');

    // Add row with the title of the tab.
    TextTableRow::addRow($table, 'Title', $details['ptb_title']);

    // Add row with the label of the tab.
    TextTableRow::addRow($table, 'Label', $details['ptb_label']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos an overview of all master pages of the page group.
   */
  private function showMasterPages()
  {
    $pages = Abc::$DL->abcSystemTabGetMasterPages($this->tabId, $this->lanId);

    $table = new CoreOverviewTable();

    // Show the ID and class of the page.
    $col = $table->addColumn(new PageTableColumn('Page'));
    $col->setSortOrder(1);

    // Show title of page.
    $table->addColumn(new TextTableColumn('Title', 'pag_title'));

    // Show label of the page ID.
    $table->addColumn(new TextTableColumn('Label', 'pag_label'));

    echo $table->getHtmlTable($pages);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

