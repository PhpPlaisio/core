<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\Company\RoleInsertTableAction;
use Plaisio\Core\TableColumn\Company\RoleTableColumn;
use Plaisio\Core\TableColumn\Company\RoleUpdateIconTableColumn;
use Plaisio\Core\TableColumn\System\RoleGroupTableColumn;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableColumn\NumberTableColumn;
use Plaisio\Table\TableColumn\TextTableColumn;

/**
 * Page with an overview of all roles of the target company.
 */
class RoleOverviewPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $targetCmpId The ID of the target company.
   *
   * @return string
   */
  public static function getUrl(int $targetCmpId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_COMPANY_ROLE_OVERVIEW, 'pag');
    $url .= Nub::$nub->cgi->putId('cmp', $targetCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function htmlTabContent(): ?string
  {
    $roles = Nub::$nub->DL->abcCompanyRoleGetAll($this->targetCmpId, $this->lanId);

    $table = new CoreOverviewTable();

    // Add table action for creating a new role.
    $table->addTableAction('default', new RoleInsertTableAction($this->targetCmpId));

    // Show role group ID and name.
    $table->addColumn(new RoleGroupTableColumn('Role Group'));

    // Show role ID and name.
    $table->addColumn(new RoleTableColumn('Role'));

    // Show the weight of the role.
    $table->addColumn(new NumberTableColumn('Weight', 'rol_weight'));

    // Show the label of the role.
    $table->addColumn(new TextTableColumn('Label', 'rol_label'));

    // Add link to the update the role.
    $table->addColumn(new RoleUpdateIconTableColumn());

    return $table->htmlTable($roles);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

