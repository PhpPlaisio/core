<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\System;

use Plaisio\Core\Page\System\TabDetailsPage;
use Plaisio\Core\TableColumn\DetailsIconTableColumn;

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
