<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
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

    $this->ptbId       = self::getCgiId('ptb', 'ptb');
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
  public static function getUrl($ptbId)
  {
    $url = self::putCgiId('pag', C::PAG_ID_SYSTEM_TAB_UPDATE, 'pag');
    $url .= self::putCgiId('ptb', $ptbId, 'ptb');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of a page group.
   */
  protected function databaseAction()
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    if ($values['ptb_title'])
    {
      $wrd_id = Abc::$DL->bblWordInsertWord($this->usrId,
                                            C::WDG_ID_PAGE_GROUP_TITLE,
                                            false,
                                            false,
                                            $values['ptb_title']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    Abc::$DL->abcSystemTabUpdateDetails($this->ptbId, $wrd_id, $values['ptb_label']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadValues()
  {
    $values = $this->details;
    unset($values['ptb_title']);

    $this->form->setValues($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

