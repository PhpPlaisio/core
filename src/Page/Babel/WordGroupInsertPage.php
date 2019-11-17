<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Babel;

use Plaisio\C;
use Plaisio\Kernel\Nub;

/**
 * Page for inserting a word group.
 */
class WordGroupInsertPage extends WordGroupBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->buttonWrdId = C::WRD_ID_BUTTON_INSERT;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @return string
   */
  public static function getUrl(): string
  {
    $url = Nub::$cgi->putLeader();
    $url .= Nub::$cgi->putId('pag', C::PAG_ID_BABEL_WORD_GROUP_INSERT, 'pag');
    $url .= Nub::$cgi->putId('act_lan', C::LAN_ID_BABEL_REFERENCE, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Actually inserts a word group.
   */
  protected function databaseAction(): void
  {
    $values = $this->form->getValues();

    $this->wdgId = Nub::$DL->abcBabelWordGroupInsertDetails($values['wdg_name'], $values['wdg_label']);
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

