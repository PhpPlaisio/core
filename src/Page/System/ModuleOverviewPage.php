<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Page\PlaisioCorePage;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\System\ModuleInsertTableAction;
use Plaisio\Core\TableColumn\System\ModuleTableColumn;
use Plaisio\Core\TableColumn\System\ModuleUpdateIconTableColumn;
use Plaisio\Kernel\Nub;

//----------------------------------------------------------------------------------------------------------------------

/**
 * Page with overview of all modules.
 */
class ModuleOverviewPage extends PlaisioCorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL of this page.
   *
   * @return string
   */
  public static function getUrl(): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_MODULE_OVERVIEW, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $modules = Nub::$nub->DL->abcSystemModuleGetAll($this->lanId);

    $table = new CoreOverviewTable();

    // Add table action for inserting a new module.
    $table->addTableAction('default', new ModuleInsertTableAction());

    // Show the ID and the name of the module.
    $column = new ModuleTableColumn('Module');
    $column->setSortOrder1(1);
    $table->addColumn($column);

    // Add column with icon to modify the details of the module.
    $table->addColumn(new ModuleUpdateIconTableColumn());

    echo $table->htmlTable($modules);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
