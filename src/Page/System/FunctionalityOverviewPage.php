<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\System\FunctionalityInsertTableAction;
use SetBased\Abc\Core\TableColumn\System\FunctionalityDetailsIconTableColumn;
use SetBased\Abc\Core\TableColumn\System\FunctionalityUpdateIconTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

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
    return self::putCgiVar('pag', C::PAG_ID_SYSTEM_FUNCTIONALITY_OVERVIEW, 'pag');
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

    // Show the ID of the module.
    $table->addColumn(new NumericTableColumn('ID', 'mdl_id'));

    // Show name of module.
    $col = $table->addColumn(new TextTableColumn('Module', 'mdl_name'));
    $col->setSortOrder(1);

    // Show ID of the functionality.
    $table->addColumn(new NumericTableColumn('ID', 'fun_id'));

    // Show name of functionality.
    $col = $table->addColumn(new TextTableColumn('Functionality', 'fun_name'));
    $col->setSortOrder(2);

    // Add column with icon adn link to view the details of the functionality.
    $table->addColumn(new FunctionalityDetailsIconTableColumn());

    // Add column with icon adn link to update the details of the functionality.
    $table->addColumn(new FunctionalityUpdateIconTableColumn());

    // Generate the HTML code for the table.
    echo $table->getHtmlTable($functionalities);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
