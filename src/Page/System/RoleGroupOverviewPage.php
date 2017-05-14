<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\System\RoleGroupInsertTableAction;
use SetBased\Abc\Core\TableColumn\System\RoleGroupDetailsIconTableColumn;
use SetBased\Abc\Core\TableColumn\System\RoleGroupUpdateIconTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with an overview of all role groups.
 */
class RoleGroupOverviewPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @return string
   */
  public static function getUrl()
  {
    return self::putCgiId('pag', C::PAG_ID_SYSTEM_ROLE_GROUP_OVERVIEW, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $roles = Abc::$DL->abcSystemRoleGroupGetAll($this->lanId);

    $table = new CoreOverviewTable();

    // Add table action for creating a new Company.
    $table->addTableAction('default', new RoleGroupInsertTableAction());

    // Show role ID.
    $table->addColumn(new NumericTableColumn('ID', 'rlg_id'));

    // Show the name of the role group.
    $table->addColumn(new TextTableColumn('Role Group', 'rlg_name'));

    // Show the weight of the role group.
    $col = $table->addColumn(new NumericTableColumn('Weight', 'rlg_weight'));
    $col->setSortOrder(1);

    // Show the label of the role group.
    $table->addColumn(new TextTableColumn('Label', 'rlg_label'));

    // Add link to the details of the role.
    $table->addColumn(new RoleGroupDetailsIconTableColumn());

    // Add link to the update the role group.
    $table->addColumn(new RoleGroupUpdateIconTableColumn());

    echo $table->getHtmlTable($roles);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

