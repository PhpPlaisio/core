<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\System;

use Plaisio\Core\Page\System\MenuUpdatePage;
use Plaisio\Core\TableColumn\UpdateIconTableColumn;

/**
 * Table column with icon linking to page updating the details of a menu entry.
 */
class MenuUpdateIconTableColumn extends UpdateIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getUrl(array $row): ?string
  {
    return MenuUpdatePage::getUrl($row['mnu_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
