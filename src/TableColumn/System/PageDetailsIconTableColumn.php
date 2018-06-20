<?php

namespace SetBased\Abc\Core\TableColumn\System;

use SetBased\Abc\Core\Page\System\PageDetailsPage;
use SetBased\Abc\Core\TableColumn\DetailsIconTableColumn;

/**
 * Table column with icon linking to page with information about a (target) page.
 */
class PageDetailsIconTableColumn extends DetailsIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getUrl(array $row): ?string
  {
    return PageDetailsPage::getUrl($row['pag_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
