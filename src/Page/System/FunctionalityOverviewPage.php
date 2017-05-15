<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\System\FunctionalityInsertTableAction;
use SetBased\Abc\Core\TableColumn\Company\FunctionalityTableColumn;
use SetBased\Abc\Core\TableColumn\Company\ModuleTableColumn;
use SetBased\Abc\Core\TableColumn\System\FunctionalityUpdateIconTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with an overview all functionalities.
 */
class FunctionalityOverviewPage extends CorePage
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
    $col = $table->addColumn(new ModuleTableColumn('Module'));
    $col->setSortOrder(1);

    // Show ID and name of the functionality.
    $col = $table->addColumn(new FunctionalityTableColumn('Functionality'));
    $col->setSortOrder(2);

    // Add column with icon adn link to update the details of the functionality.
    $table->addColumn(new FunctionalityUpdateIconTableColumn());

    // Generate the HTML code for the table.
    echo $table->getHtmlTable($functionalities);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
