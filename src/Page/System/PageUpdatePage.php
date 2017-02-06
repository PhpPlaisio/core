<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for updating the details of a target page.
 */
class PageUpdatePage extends PageBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the target page.
   *
   * @var array
   */
  private $details;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->targetPagId = self::getCgiId('tar_pag', 'pag');
    $this->details     = Abc::$DL->abcSystemPageGetDetails($this->targetPagId, $this->lanId);
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $pagId
   *
   * @return string
   */
  public static function getUrl($pagId)
  {
    $url = self::putCgiId('pag', C::PAG_ID_SYSTEM_PAGE_UPDATE, 'pag');
    $url .= self::putCgiId('tar_pag', $pagId, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a page.
   */
  protected function databaseAction()
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

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

    Abc::$DL->abcSystemPageUpdateDetails($this->targetPagId,
                                         $wrd_id,
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
    $values = $this->details;
    unset($values['pag_title']);

    $this->form->setValues($values);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

