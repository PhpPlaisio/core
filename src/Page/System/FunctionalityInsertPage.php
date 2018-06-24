<?php

namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

/**
 * Page for inserting a functionality.
 */
class FunctionalityInsertPage extends FunctionalityBasePage
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
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_SYSTEM_FUNCTIONALITY_INSERT, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a functionality.
   */
  protected function dataBaseAction(): void
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    if ($values['fun_name'])
    {
      $wrd_id = Abc::$DL->abcBabelWordInsertWord(C::WDG_ID_FUNCTIONALITIES, null, null, $values['fun_name']);
    }
    else
    {
      $wrd_id = $values['wrd_id'];
    }

    $this->funId = Abc::$DL->abcSystemFunctionalityInsertDetails($values['mdl_id'], $wrd_id);
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
