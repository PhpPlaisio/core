<?php

namespace SetBased\Abc\Core\TableColumn\System;

use SetBased\Abc\Core\Page\System\ModuleUpdatePage;
use SetBased\Abc\Core\TableColumn\UpdateIconTableColumn;

/**
 * Table column with icon linking to page for modifying the details of a module.
 */
class ModuleUpdateIconTableColumn extends UpdateIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getUrl(array $row): ?string
  {
    return ModuleUpdatePage::getUrl($row['mdl_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
