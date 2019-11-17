<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Page\TabPage;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\System\MenuInsertTableAction;
use Plaisio\Core\TableColumn\System\MenuUpdateIconTableColumn;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableColumn\NumericTableColumn;
use Plaisio\Table\TableColumn\TextTableColumn;

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
  public static function getUrl(): string
  {
    $url = Nub::$cgi->putLeader();
    $url .= Nub::$cgi->putId('pag', C::PAG_ID_SYSTEM_MENU_OVERVIEW, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function echoTabContent(): void
  {
    $pages = Nub::$DL->abcSystemMenuGetAllEntries($this->lanId);

    $table = new CoreOverviewTable();

    $table->addTableAction('default', new MenuInsertTableAction());

    // Show menu ID.
    $table->addColumn(new NumericTableColumn('ID', 'mnu_id'));

    // Show menu name.
    $column = new TextTableColumn('Name', 'mnu_name');
    $column->setSortOrder(1);
    $table->addColumn($column);

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
