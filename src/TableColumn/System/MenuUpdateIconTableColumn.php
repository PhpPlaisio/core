<?php

namespace SetBased\Abc\Core\TableColumn\System;

use SetBased\Abc\Core\Page\System\MenuUpdatePage;
use SetBased\Abc\Core\TableColumn\UpdateIconTableColumn;

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
