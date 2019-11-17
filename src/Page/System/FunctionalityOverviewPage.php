<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Page\TabPage;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\System\FunctionalityInsertTableAction;
use Plaisio\Core\TableColumn\Company\FunctionalityTableColumn;
use Plaisio\Core\TableColumn\Company\ModuleTableColumn;
use Plaisio\Core\TableColumn\System\FunctionalityUpdateIconTableColumn;
use Plaisio\Kernel\Nub;

/**
 * Page with an overview all functionalities.
 */
class FunctionalityOverviewPage extends TabPage
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
    $url .= Nub::$cgi->putId('pag', C::PAG_ID_SYSTEM_FUNCTIONALITY_OVERVIEW, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $functionalities = Nub::$DL->abcSystemFunctionalityGetAll($this->lanId);

    $table = new CoreOverviewTable();
    $table->addTableAction('default', new FunctionalityInsertTableAction());

    // Show the ID and the name of the module.
    $column = new ModuleTableColumn('Module');
    $column->setSortOrder(1);
    $table->addColumn($column);

    // Show ID and name of the functionality.
    $column = new FunctionalityTableColumn('Functionality');
    $column->setSortOrder(2);
    $table->addColumn($column);

    // Add column with icon adn link to update the details of the functionality.
    $table->addColumn(new FunctionalityUpdateIconTableColumn());

    // Generate the HTML code for the table.
    echo $table->getHtmlTable($functionalities);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
