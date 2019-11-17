<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\Babel;

use Plaisio\Core\Page\Babel\WordInsertPage;
use Plaisio\Core\TableAction\InsertItemTableAction;

/**
 * Table action for inserting a word.
 */
class WordInsertTableAction extends InsertItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $wdgId The ID of the word group of the new word.
   */
  public function __construct(int $wdgId)
  {
    $this->url = WordInsertPage::getUrl($wdgId);

    $this->title = 'Create word';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
