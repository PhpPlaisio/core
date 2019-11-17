<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\System;

use Plaisio\Core\Page\System\TabUpdatePage;
use Plaisio\Core\TableColumn\UpdateIconTableColumn;

/**
 * Table column with icon linking to page for updating the details of a page group.
 */
class TabUpdateIconTableColumn extends UpdateIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getUrl(array $row): ?string
  {
    return TabUpdatePage::getUrl($row['ptb_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
