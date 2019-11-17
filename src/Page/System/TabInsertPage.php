<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Kernel\Nub;

/**
 * Inserts a page group.
 */
class TabInsertPage extends TabBasePage
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
    $url .= Nub::$cgi->putId('pag', C::PAG_ID_SYSTEM_TAB_INSERT, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a page group.
   */
  protected function databaseAction(): void
  {
    $values = $this->form->getValues();
    if ($values['ptb_title'])
    {
      $wrd_id = Nub::$DL->abcBabelWordInsertWord(C::WDG_ID_PAGE_GROUP_TITLE, null, null, $values['ptb_title']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    Nub::$DL->abcSystemTabInsertDetails($wrd_id, $values['ptb_label']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadValues(): void
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

