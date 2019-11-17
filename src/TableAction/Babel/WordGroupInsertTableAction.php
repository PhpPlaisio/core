<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\Babel;

use Plaisio\Core\Page\Babel\WordGroupInsertPage;
use Plaisio\Core\TableAction\InsertItemTableAction;

/**
 * Table action for inserting a word group.
 */
class WordGroupInsertTableAction extends InsertItemTableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    $this->url = WordGroupInsertPage::getUrl();

    $this->title = 'Create word group';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
