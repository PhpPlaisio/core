<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Babel;

use Plaisio\C;
use Plaisio\Kernel\Nub;

/**
 * Page for inserting a word.
 */
class WordInsertPage extends WordBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->wdgId       = Nub::$nub->cgi->getManId('wdg', 'wdg');
    $this->buttonWrdId = C::WRD_ID_BUTTON_INSERT;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $wdgId The ID of the word group.
   *
   * @return string
   */
  public static function getUrl(int $wdgId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_BABEL_WORD_INSERT, 'pag');
    $url .= Nub::$nub->cgi->putId('wdg', $wdgId, 'wdg');
    $url .= Nub::$nub->cgi->putId('lan-target', C::LAN_ID_BABEL_REFERENCE, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a word.
   */
  protected function databaseAction(): void
  {
    $values = $this->form->getValues();

    $this->wrdId = Nub::$nub->DL->abcBabelWordInsertWord($this->wdgId,
                                                         $values['wrd_label'],
                                                         $values['wrd_comment'],
                                                         $values['wdt_text']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function setValues(): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

