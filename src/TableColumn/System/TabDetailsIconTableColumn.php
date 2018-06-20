<?php

namespace SetBased\Abc\Core\TableColumn\System;

use SetBased\Abc\Core\Page\System\TabDetailsPage;
use SetBased\Abc\Core\TableColumn\DetailsIconTableColumn;

/**
 * Table column with icon linking to page with information about a page group.
 */
class TabDetailsIconTableColumn extends DetailsIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getUrl(array $row): ?string
  {
    return TabDetailsPage::getUrl($row['ptb_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
