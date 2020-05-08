<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Kernel\Nub;

/**
 * Page for inserting a menu entry.
 */
class MenuInsertPage extends MenuBasePage
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
   * Returns the relative URL to this page.
   *
   * @return string
   */
  public static function getUrl(): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_MENU_INSERT, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a menu entry.
   */
  protected function databaseAction(): void
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    if ($values['mnu_title'])
    {
      $wrd_id = Nub::$nub->DL->abcBabelWordInsertWord(C::WDG_ID_MENU, null, null, $values['mnu_title']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    Nub::$nub->DL->abcSystemMenuInsert($wrd_id,
                                       $values['pag_id'],
                                       $values['mnu_level'],
                                       $values['mnu_group'],
                                       $values['mnu_weight'],
                                       $values['mnu_link']);
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
