<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Html\VerticalLayout;
use Plaisio\Core\Page\CoreCorePage;
use Plaisio\Core\Table\CoreDetailTable;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\System\RoleGroupUpdateTableAction;
use Plaisio\Core\TableColumn\Company\CompanyTableColumn;
use Plaisio\Core\TableColumn\Company\RoleTableColumn;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableRow\IntegerTableRow;
use Plaisio\Table\TableRow\TextTableRow;

/**
 * Page with the details of a role group.
 */
class RoleGroupDetailsPage extends CoreCorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the role group.
   *
   * @var int
   */
  private int $rlgId;

  /**
   * The details of the role group.
   *
   * @var array
   */
  private array $roleGroup;

  /**
   * The details of roles in the role group.
   *
   * @var array
   */
  private array $roles;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->rlgId     = Nub::$nub->cgi->getManId('rlg', 'rlg');
    $this->roleGroup = Nub::$nub->DL->abcSystemRoleGroupGetDetails($this->rlgId, $this->lanId);
    $this->roles     = Nub::$nub->DL->abcSystemRoleGroupGetRoles($this->rlgId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $rlgId The ID of the role group.
   *
   * @return string
   */
  public static function getUrl(int $rlgId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_ROLE_GROUP_DETAILS, 'pag');
    $url .= Nub::$nub->cgi->putId('rlg', $rlgId, 'rlg');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function htmlTabContent(): ?string
  {
    $layout = new VerticalLayout();
    $layout->addBlock($this->htmlDetails())
           ->addBlock($this->htmlRoles());

    return $layout->html();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return the details of the role group.
   */
  private function htmlDetails(): string
  {
    $table = new CoreDetailTable();

    // Add table action for updating the rle group.
    $table->addTableAction('default', new RoleGroupUpdateTableAction($this->rlgId));

    // Show ID of the role group.
    IntegerTableRow::addRow($table, 'ID', $this->roleGroup['rlg_id']);

    // Show name of the role group.
    TextTableRow::addRow($table, 'Name', $this->roleGroup['rlg_name']);

    // Show weight of the role group.
    IntegerTableRow::addRow($table, 'Weight', $this->roleGroup['rlg_weight']);

    // Show label of the role group.
    TextTableRow::addRow($table, 'Label', $this->roleGroup['rlg_label']);

    // Show table.
    return $table->htmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Return the roles in the role group.
   */
  private function htmlRoles(): string
  {
    $table = new CoreOverviewTable();

    // Show the ID abbreviation of the company.
    $table->addColumn(new CompanyTableColumn(C::WRD_ID_COMPANY));

    // Show the name of the role.
    $table->addColumn(new RoleTableColumn(C::WRD_ID_ROLE));

    // Show the table.
    return $table->htmlTable($this->roles);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

