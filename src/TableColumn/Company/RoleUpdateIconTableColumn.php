<?php

namespace SetBased\Abc\Core\TableColumn\Company;

use SetBased\Abc\Core\Page\Company\RoleUpdatePage;
use SetBased\Abc\Core\TableColumn\UpdateIconTableColumn;

/**
 * Table column with icon linking to page for updating the details of a role.
 */
class RoleUpdateIconTableColumn extends UpdateIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getUrl(array $row): ?string
  {
    return RoleUpdatePage::getUrl($row['cmp_id'], $row['rol_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
