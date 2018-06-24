<?php

namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Page\TabPage;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\System\RoleGroupInsertTableAction;
use SetBased\Abc\Core\TableColumn\System\RoleGroupTableColumn;
use SetBased\Abc\Core\TableColumn\System\RoleGroupUpdateIconTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

/**
 * Page with an overview of all role groups.
 */
class RoleGroupOverviewPage extends TabPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @return string
   */
  public static function getUrl(): string
  {
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_SYSTEM_ROLE_GROUP_OVERVIEW, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $roles = Abc::$DL->abcSystemRoleGroupGetAll($this->lanId);

    $table = new CoreOverviewTable();

    // Add table action for creating a new role group.
    $table->addTableAction('default', new RoleGroupInsertTableAction());

    // Show ID and name of the role group.
    $table->addColumn(new RoleGroupTableColumn('Role Group'));

    // Show the weight of the role group.
    $column = new NumericTableColumn('Weight', 'rlg_weight');
    $column->setSortOrder(1);
    $table->addColumn($column);

    // Show the label of the role group.
    $table->addColumn(new TextTableColumn('Label', 'rlg_label'));

    // Add link to the update the role group.
    $table->addColumn(new RoleGroupUpdateIconTableColumn());

    echo $table->getHtmlTable($roles);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

