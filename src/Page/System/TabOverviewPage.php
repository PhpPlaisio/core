<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Page\CoreCorePage;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\System\TabInsertTableAction;
use Plaisio\Core\TableColumn\System\TabDetailsIconTableColumn;
use Plaisio\Core\TableColumn\System\TabUpdateIconTableColumn;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableColumn\NumberTableColumn;
use Plaisio\Table\TableColumn\TextTableColumn;

/**
 * Page with overview of all page groups.
 */
class TabOverviewPage extends CoreCorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @return string
   */
  public static function getUrl(): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_TAB_OVERVIEW, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function htmlTabContent(): ?string
  {
    $tabs = Nub::$nub->DL->abcSystemTabGetAll($this->lanId);

    $table = new CoreOverviewTable();

    // Add table action for creating a new page tab.
    $table->addTableAction('default', new TabInsertTableAction());

    // Show page tab ID.
    $table->addColumn(new NumberTableColumn('ID', 'ptb_id'));

    // Show label title of the page tab.
    $column = new TextTableColumn('Title', 'ptb_title');
    $column->setSortOrder(1);
    $table->addColumn($column);

    // Show label of the page tab.
    $table->addColumn(new TextTableColumn('Label', 'ptb_label'));

    // Show link to the details of the page tab.
    $table->addColumn(new TabDetailsIconTableColumn());

    // Show link to modify the page tab.
    $table->addColumn(new TabUpdateIconTableColumn());

    // Generate the HTML code for the table.
    return $table->htmlTable($tabs);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
