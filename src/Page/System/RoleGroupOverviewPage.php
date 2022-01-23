<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Page\CoreCorePage;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\System\RoleGroupInsertTableAction;
use Plaisio\Core\TableColumn\System\RoleGroupTableColumn;
use Plaisio\Core\TableColumn\System\RoleGroupUpdateIconTableColumn;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableColumn\NumberTableColumn;
use Plaisio\Table\TableColumn\TextTableColumn;

/**
 * Page with an overview of all role groups.
 */
class RoleGroupOverviewPage extends CoreCorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @return string
   */
  public static function getUrl(): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_ROLE_GROUP_OVERVIEW, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function htmlTabContent(): ?string
  {
    $roles = Nub::$nub->DL->abcSystemRoleGroupGetAll($this->lanId);

    $table = new CoreOverviewTable();

    // Add table action for creating a new role group.
    $table->addTableAction('default', new RoleGroupInsertTableAction());

    // Show ID and name of the role group.
    $table->addColumn(new RoleGroupTableColumn('Role Group'));

    // Show the weight of the role group.
    $column = new NumberTableColumn('Weight', 'rlg_weight');
    $column->setSortOrder(1);
    $table->addColumn($column);

    // Show the label of the role group.
    $table->addColumn(new TextTableColumn('Label', 'rlg_label'));

    // Add link to the update the role group.
    $table->addColumn(new RoleGroupUpdateIconTableColumn());

    return $table->htmlTable($roles);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

