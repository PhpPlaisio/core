<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Kernel\Nub;

/**
 * Page for updating the details of a menu entry.
 */
class MenuUpdatePage extends MenuBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the menu entry.
   *
   * @var array
   */
  private $details;

  /**
   * The ID of the menu entry.
   *
   * @var int
   */
  private $mnuId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->mnuId       = Nub::$cgi->getManId('mnu', 'mnu');
    $this->details     = Nub::$DL->abcSystemMenuGetDetails($this->mnuId, $this->lanId);
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $mnuId The ID of the menu entry.
   *
   * @return string
   */
  public static function getUrl(int $mnuId): string
  {
    $url = Nub::$cgi->putLeader();
    $url .= Nub::$cgi->putId('pag', C::PAG_ID_SYSTEM_MENU_MODIFY, 'pag');
    $url .= Nub::$cgi->putId('mnu', $mnuId, 'mnu');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the menu entry.
   */
  protected function databaseAction(): void
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    if ($values['mnu_title'])
    {
      $wrd_id = Nub::$DL->abcBabelWordInsertWord(C::WDG_ID_MENU, null, null, $values['mnu_title']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    Nub::$DL->abcSystemMenuUpdate($this->mnuId,
                                  $wrd_id,
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
    $this->form->setValues($this->details);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
