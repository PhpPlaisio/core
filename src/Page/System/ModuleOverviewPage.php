<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\System\ModuleInsertTableAction;
use SetBased\Abc\Core\TableColumn\Company\ModuleTableColumn;
use SetBased\Abc\Core\TableColumn\System\ModuleUpdateIconTableColumn;

//----------------------------------------------------------------------------------------------------------------------

/**
 * Page with overview of all modules.
 */
class ModuleOverviewPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL of this page.
   *
   * @return string
   */
  public static function getUrl()
  {
    return self::putCgiId('pag', C::PAG_ID_SYSTEM_MODULE_OVERVIEW, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $modules = Abc::$DL->abcSystemModuleGetAll($this->lanId);

    $table = new CoreOverviewTable();

    // Add table action for inserting a new module.
    $table->addTableAction('default', new ModuleInsertTableAction());

    // Show the ID and the name of the module.
    $col = $table->addColumn(new ModuleTableColumn('Module'));
    $col->setSortOrder(1);

    // Add column with icon to modify the details of the module.
    $table->addColumn(new ModuleUpdateIconTableColumn());

    echo $table->getHtmlTable($modules);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
