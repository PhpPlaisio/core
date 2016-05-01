<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableAction\Babel;

use SetBased\Abc\Core\Page\Babel\WordInsertPage;
use SetBased\Abc\Core\TableAction\InsertItemTableAction;

//----------------------------------------------------------------------------------------------------------------------
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
  public function __construct($wdgId)
  {
    $this->url = WordInsertPage::getUrl($wdgId);

    $this->title = 'Create word';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
