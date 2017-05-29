<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\TabPage;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\System\MenuInsertTableAction;
use SetBased\Abc\Core\TableColumn\System\MenuUpdateIconTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with an overview of all menu entries.
 */
class MenuOverviewPage extends TabPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @return string
   */
  public static function getUrl()
  {
    return self::putCgiId('pag', C::PAG_ID_SYSTEM_MENU_OVERVIEW, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function echoTabContent()
  {
    $pages = Abc::$DL->abcSystemMenuGetAllEntries($this->lanId);

    $table = new CoreOverviewTable();

    $table->addTableAction('default', new MenuInsertTableAction());

    // Show menu ID.
    $table->addColumn(new NumericTableColumn('ID', 'mnu_id'));

    // Show menu name.
    $col = $table->addColumn(new TextTableColumn('Name', 'mnu_name'));
    $col->setSortOrder(1);

    // Show menu level.
    $table->addColumn(new NumericTableColumn('Level', 'mnu_level'));

    // Show menu group.
    $table->addColumn(new NumericTableColumn('Group', 'mnu_group'));

    // Show menu weight.
    $table->addColumn(new NumericTableColumn('Weight', 'mnu_weight'));

    // Show menu link.
    $table->addColumn(new TextTableColumn('Link', 'mnu_link'));

    // Show modifying the menu item.
    $table->addColumn(new MenuUpdateIconTableColumn());

    // Generate the HTML code for the table.
    echo $table->getHtmlTable($pages);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
