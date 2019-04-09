<?php

namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\TabPage;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableColumn\System\PageTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;
use SetBased\Abc\Table\TableRow\IntegerTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

/**
 * Page with information about a page group.
 */
class TabDetailsPage extends TabPage
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

    $this->tabId = Abc::$cgi->getManId('ptb', 'ptb');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $ptbId The ID of the tab shown on this page.
   *
   * @return string
   */
  public static function getUrl(int $ptbId): string
  {
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_SYSTEM_TAB_DETAILS, 'pag');
    $url .= Abc::$cgi->putId('ptb', $ptbId, 'ptb');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $this->showDetails();

    $this->showMasterPages();
    // XXX Show all pages.
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the details of the page group.
   */
  private function showDetails(): void
  {
    $details = Abc::$DL->abcSystemTabGetDetails($this->tabId, $this->lanId);
    $table   = new CoreDetailTable();

    // Add row with the ID of the tab.
    IntegerTableRow::addRow($table, 'ID', $details['ptb_id']);

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
  private function showMasterPages(): void
  {
    $pages = Abc::$DL->abcSystemTabGetMasterPages($this->tabId, $this->lanId);

    $table = new CoreOverviewTable();

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
}

//----------------------------------------------------------------------------------------------------------------------

