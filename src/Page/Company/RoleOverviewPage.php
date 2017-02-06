<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\Company\RoleInsertTableAction;
use SetBased\Abc\Core\TableColumn\Company\RoleDetailsIconTableColumn;
use SetBased\Abc\Core\TableColumn\Company\RoleUpdateIconTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

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
    $roles = Abc::$DL->abcCompanyRoleGetAll($this->targetCmpId);

    $table = new CoreOverviewTable();

    // Add table action for creating a new Company.
    $table->addTableAction('default', new RoleInsertTableAction($this->targetCmpId));

    // Show role ID.
    $table->addColumn(new NumericTableColumn('ID', 'rol_id'));

    // Show the name of the role.
    $table->addColumn(new TextTableColumn('Role', 'rol_name'));

    // Show the weight of the role.
    $col = $table->addColumn(new NumericTableColumn('Weight', 'rol_weight'));
    $col->setSortOrder(1);

    // Add link to the details of the role.
    $table->addColumn(new RoleDetailsIconTableColumn());

    // Add link to the update the role.
    $table->addColumn(new RoleUpdateIconTableColumn());

    echo $table->getHtmlTable($roles);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

