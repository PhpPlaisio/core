<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\System;

use Plaisio\Core\Page\System\PageUpdatePage;
use Plaisio\Core\TableColumn\UpdateIconTableColumn;

/**
 * Table column with icon linking to page for updating the details of a target page.
 */
class PageUpdateIconTableColumn extends UpdateIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getUrl(array $row): ?string
  {
    return PageUpdatePage::getUrl($row['pag_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
