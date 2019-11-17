<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\Company;

use Plaisio\Core\Page\Company\RoleUpdatePage;
use Plaisio\Core\TableColumn\UpdateIconTableColumn;

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
