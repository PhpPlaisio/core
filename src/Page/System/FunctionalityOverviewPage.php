<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Page\CoreCorePage;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\System\FunctionalityInsertTableAction;
use Plaisio\Core\TableColumn\System\FunctionalityTableColumn;
use Plaisio\Core\TableColumn\System\FunctionalityUpdateIconTableColumn;
use Plaisio\Core\TableColumn\System\ModuleTableColumn;
use Plaisio\Kernel\Nub;

/**
 * Page with an overview all functionalities.
 */
class FunctionalityOverviewPage extends CoreCorePage
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
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_FUNCTIONALITY_OVERVIEW, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function htmlTabContent(): ?string
  {
    $functionalities = Nub::$nub->DL->abcSystemFunctionalityGetAll($this->lanId);

    $table = new CoreOverviewTable();
    $table->addTableAction('default', new FunctionalityInsertTableAction());

    // Show the ID and the name of the module.
    $column = new ModuleTableColumn('Module');
    $column->setSortOrder1(1);
    $table->addColumn($column);

    // Show ID and name of the functionality.
    $column = new FunctionalityTableColumn('Functionality');
    $column->setSortOrder1(2);
    $table->addColumn($column);

    // Add column with icon adn link to update the details of the functionality.
    $table->addColumn(new FunctionalityUpdateIconTableColumn());

    // Generate the HTML code for the table.
    return $table->htmlTable($functionalities);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
