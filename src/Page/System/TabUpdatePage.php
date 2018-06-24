<?php

namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

/**
 * Page for updating the details of a page group.
 */
class TabUpdatePage extends TabBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the page group.
   *
   * @var array
   */
  private $details;

  /**
   * The ID of the page group.
   *
   * @var int
   */
  private $ptbId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->ptbId       = Abc::$cgi->getManId('ptb', 'ptb');
    $this->details     = Abc::$DL->abcSystemTabGetDetails($this->ptbId, $this->lanId);
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $ptbId The ID of the page tab.
   *
   * @return string
   */
  public static function getUrl(int $ptbId): string
  {
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_SYSTEM_TAB_UPDATE, 'pag');
    $url .= Abc::$cgi->putId('ptb', $ptbId, 'ptb');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of a page group.
   */
  protected function databaseAction(): void
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    if ($values['ptb_title'])
    {
      $wrd_id = Abc::$DL->abcBabelWordInsertWord(C::WDG_ID_PAGE_GROUP_TITLE, null, null, $values['ptb_title']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    Abc::$DL->abcSystemTabUpdateDetails($this->ptbId, $wrd_id, $values['ptb_label']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadValues(): void
  {
    $values = $this->details;
    unset($values['ptb_title']);

    $this->form->setValues($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

