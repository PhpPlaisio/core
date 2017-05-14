<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\Company\RoleInsertTableAction;
use SetBased\Abc\Core\TableColumn\Company\RoleTableColumn;
use SetBased\Abc\Core\TableColumn\Company\RoleUpdateIconTableColumn;
use SetBased\Abc\Core\TableColumn\System\RoleGroupTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;

//----------------------------------------------------------------------------------------------------------------------
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
  public static function getUrl($targetCmpId)
  {
    $url = self::putCgiId('pag', C::PAG_ID_COMPANY_ROLE_OVERVIEW, 'pag');
    $url .= self::putCgiId('cmp', $targetCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $roles = Abc::$DL->abcCompanyRoleGetAll($this->targetCmpId, $this->lanId);

    $table = new CoreOverviewTable();

    // Add table action for creating a new role.
    $table->addTableAction('default', new RoleInsertTableAction($this->targetCmpId));

    // Show role group ID and name.
    $table->addColumn(new RoleGroupTableColumn('Role Group'));

    // Show role ID and name.
    $table->addColumn(new RoleTableColumn('Role'));

    // Show the weight of the role.
    $table->addColumn(new NumericTableColumn('Weight', 'rol_weight'));

    // Add link to the update the role.
    $table->addColumn(new RoleUpdateIconTableColumn());

    echo $table->getHtmlTable($roles);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

