<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Page\PlaisioCorePage;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\System\PageInsertTableAction;
use Plaisio\Core\TableColumn\System\PageTableColumn;
use Plaisio\Core\TableColumn\System\PageUpdateIconTableColumn;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableColumn\TextTableColumn;

/**
 * Page with an overview all pages.
 */
class PageOverviewPage extends PlaisioCorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @return string
   */
  public static function getUrl(): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_PAGE_OVERVIEW, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $pages = Nub::$nub->DL->abcSystemPageGetAll($this->lanId);

    $table = new CoreOverviewTable();
    $table->addTableAction('default', new PageInsertTableAction());

    // Show the id and class of the page.
    $column = new PageTableColumn('Page');
    $column->setSortOrder1(1);
    $table->addColumn($column);

    // Show title of page.
    $table->addColumn(new TextTableColumn('Title', 'pag_title'));

    // Show the alias of the page.
    $table->addColumn(new TextTableColumn('Label', 'pag_alias'));

    // Show label of the page.
    $table->addColumn(new TextTableColumn('Label', 'pag_label'));

    // Show page tab of the page.
    $table->addColumn(new TextTableColumn('Page Tab', 'ptb_label'));

    // Show icon to modify the page.
    $table->addColumn(new PageUpdateIconTableColumn());

    echo $table->getHtmlTable($pages);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

