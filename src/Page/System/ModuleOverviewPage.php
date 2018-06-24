<?php

namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\TabPage;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\System\ModuleInsertTableAction;
use SetBased\Abc\Core\TableColumn\Company\ModuleTableColumn;
use SetBased\Abc\Core\TableColumn\System\ModuleUpdateIconTableColumn;

//----------------------------------------------------------------------------------------------------------------------

/**
 * Page with overview of all modules.
 */
class ModuleOverviewPage extends TabPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL of this page.
   *
   * @return string
   */
  public static function getUrl(): string
  {
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_SYSTEM_MODULE_OVERVIEW, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $modules = Abc::$DL->abcSystemModuleGetAll($this->lanId);

    $table = new CoreOverviewTable();

    // Add table action for inserting a new module.
    $table->addTableAction('default', new ModuleInsertTableAction());

    // Show the ID and the name of the module.
    $column = new ModuleTableColumn('Module');
    $column->setSortOrder(1);
    $table->addColumn($column);

    // Add column with icon to modify the details of the module.
    $table->addColumn(new ModuleUpdateIconTableColumn());

    echo $table->getHtmlTable($modules);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
