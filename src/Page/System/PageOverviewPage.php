<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\TabPage;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\System\PageInsertTableAction;
use SetBased\Abc\Core\TableColumn\System\PageTableColumn;
use SetBased\Abc\Core\TableColumn\System\PageUpdateIconTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with an overview all pages.
 */
class PageOverviewPage extends TabPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @return string
   */
  public static function getUrl()
  {
    return self::putCgiId('pag', C::PAG_ID_SYSTEM_PAGE_OVERVIEW, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $pages = Abc::$DL->abcSystemPageGetAll($this->lanId);

    $table = new CoreOverviewTable();
    $table->addTableAction('default', new PageInsertTableAction());

    // Show the id and class of the page.
    $col = $table->addColumn(new PageTableColumn('Page'));
    $col->setSortOrder(1);

    // Show title of page.
    $table->addColumn(new TextTableColumn('Title', 'pag_title'));

    // Show the alias of the page.
    $table->addColumn(new TextTableColumn('Label', 'pag_alias'));

    // Show label of the page.
    $table->addColumn(new TextTableColumn('Label', 'pag_label'));

    // Show associated menu item of the page.
    $table->addColumn(new TextTableColumn('Menu', 'mnu_name'));

    // Show page tab of the page.
    $table->addColumn(new TextTableColumn('Page Tab', 'ptb_label'));

    // Show icon to modify the page.
    $table->addColumn(new PageUpdateIconTableColumn());

    echo $table->getHtmlTable($pages);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

