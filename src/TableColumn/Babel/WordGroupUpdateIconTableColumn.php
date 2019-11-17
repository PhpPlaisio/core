<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\Babel;

use Plaisio\Core\Page\Babel\WordGroupUpdatePage;
use Plaisio\Core\TableColumn\UpdateIconTableColumn;

/**
 * Table column with icon linking to page for updating the details of a word group.
 */
class WordGroupUpdateIconTableColumn extends UpdateIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getUrl(array $row): ?string
  {
    return WordGroupUpdatePage::getUrl($row['wdg_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
