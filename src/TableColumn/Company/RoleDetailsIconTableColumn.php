<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\Company;

use SetBased\Abc\Core\Page\Company\RoleDetailsPage;
use SetBased\Abc\Core\TableColumn\DetailsIconTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column with icon link to page with information about a role.
 */
class RoleDetailsIconTableColumn extends DetailsIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getUrl($row)
  {
    return RoleDetailsPage::getUrl($row['cmp_id'], $row['rol_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
