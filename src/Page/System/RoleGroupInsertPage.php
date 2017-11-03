<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for inserting a new role group.
 */
class RoleGroupInsertPage extends RoleGroupBasePage
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
    return self::putCgiId('pag', C::PAG_ID_SYSTEM_ROLE_GROUP_INSERT, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a new role.
   */
  protected function databaseAction()
  {
    $values = $this->form->getValues();

    if ($values['rlg_name']!==null)
    {
      $wrdId = Abc::$DL->abcBabelWordInsertWord(C::WDG_ID_ROLE_GROUP, null, null, $values['rlg_name']);
    }
    else
    {
      $wrdId = $values['wrd_id'];
    }

    $this->rlgId = Abc::$DL->abcSystemRoleGroupInsert($wrdId,
                                                      $values['rlg_weight'],
                                                      $values['rlg_label']);
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

