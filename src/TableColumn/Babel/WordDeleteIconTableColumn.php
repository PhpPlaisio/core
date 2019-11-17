<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\Babel;

use Plaisio\Core\Page\Babel\WordDeletePage;
use Plaisio\Core\TableColumn\DeleteIconTableColumn;

/**
 * Table column with icon to delete a word.
 */
class WordDeleteIconTableColumn extends DeleteIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getUrl(array $row): ?string
  {
    $this->confirmMessage = 'Remove word '.$row['wrd_id'].'?'; // xxxbbl

    return WordDeletePage::getUrl($row['wrd_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
