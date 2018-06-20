<?php

namespace SetBased\Abc\Core\TableColumn\Babel;

use SetBased\Abc\Core\Page\Babel\WordDeletePage;
use SetBased\Abc\Core\TableColumn\DeleteIconTableColumn;

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
