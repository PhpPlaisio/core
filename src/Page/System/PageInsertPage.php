<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for inserting a page.
 */
class PageInsertPage extends PageBasePage
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
  public static function getUrl()
  {
    return self::putCgiVar('pag', C::PAG_ID_SYSTEM_PAGE_INSERT, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a page.
   */
  protected function databaseAction()
  {
    $values = $this->form->getValues();
    if ($values['pag_title'])
    {
      $wrd_id = Abc::$DL->bblWordInsertWord($this->usrId,
                                            C::WDG_ID_PAGE_TITLE,
                                            false,
                                            false,
                                            $values['pag_title']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    $this->targetPagId = Abc::$DL->abcSystemPageInsertDetails($wrd_id,
                                                              $values['ptb_id'],
                                                              $values['pag_id_org'],
                                                              $values['mnu_id'],
                                                              $values['pag_alias'],
                                                              $values['pag_class'],
                                                              $values['pag_label'],
                                                              $values['pag_weight']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadValues()
  {
    // Nothing to do.
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

