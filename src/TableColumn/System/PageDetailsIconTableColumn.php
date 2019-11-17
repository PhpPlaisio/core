<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\System;

use Plaisio\Core\Page\System\PageDetailsPage;
use Plaisio\Core\TableColumn\DetailsIconTableColumn;

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
