<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\System\TabInsertTableAction;
use SetBased\Abc\Core\TableColumn\System\TabDetailsIconTableColumn;
use SetBased\Abc\Core\TableColumn\System\TabUpdateIconTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with overview of all page groups.
 */
class TabOverviewPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @return string
   */
  public static function getUrl()
  {
    return self::putCgiVar('pag', C::PAG_ID_SYSTEM_TAB_OVERVIEW, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $tabs = Abc::$DL->systemTabGetAll($this->lanId);

    $table = new CoreOverviewTable();

    // Add table action for creating a new page tab.
    $table->addTableAction('default', new TabInsertTableAction());

    // Show page tab ID.
    $table->addColumn(new NumericTableColumn('ID', 'ptb_id'));

    // Show label title of the page tab.
    $col = $table->addColumn(new TextTableColumn('Title', 'ptb_title'));
    $col->setSortOrder(1);

    // Show label of the page tab.
    $table->addColumn(new TextTableColumn('Label', 'ptb_label'));

    // Show link to the details of the page tab.
    $table->addColumn(new TabDetailsIconTableColumn());

    // Show link to the modify the page tab.
    $table->addColumn(new TabUpdateIconTableColumn());

    // Generate the HTML code for the table.
    echo $table->getHtmlTable($tabs);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
