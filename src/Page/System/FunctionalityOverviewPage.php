<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\TabPage;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\System\FunctionalityInsertTableAction;
use SetBased\Abc\Core\TableColumn\Company\FunctionalityTableColumn;
use SetBased\Abc\Core\TableColumn\Company\ModuleTableColumn;
use SetBased\Abc\Core\TableColumn\System\FunctionalityUpdateIconTableColumn;

//----------------------------------------------------------------------------------------------------------------------
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
  public static function getUrl()
  {
    return self::putCgiId('pag', C::PAG_ID_SYSTEM_FUNCTIONALITY_OVERVIEW, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $functionalities = Abc::$DL->abcSystemFunctionalityGetAll($this->lanId);

    $table = new CoreOverviewTable();
    $table->addTableAction('default', new FunctionalityInsertTableAction());

    // Show the ID and the name of the module.
    $column = new ModuleTableColumn('Module');
    $column->setSortOrder(1);
    $table->addColumn($column);

    // Show ID and name of the functionality.
    $column =new FunctionalityTableColumn('Functionality');
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
