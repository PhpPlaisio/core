<?php

namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

/**
 * Page for updating the details of a role group.
 */
class RoleGroupUpdatePage extends RoleGroupBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the role group.
   *
   * @var array
   */
  private $roleGroup;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->rlgId     = Abc::$cgi->getManId('rlg', 'rlg');
    $this->roleGroup = Abc::$DL->abcSystemRoleGroupGetDetails($this->rlgId, $this->lanId);

    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $rlgId The ID of the role group.
   *
   * @return string
   */
  public static function getUrl(int $rlgId): string
  {
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_SYSTEM_ROLE_GROUP_UPDATE, 'pag');
    $url .= Abc::$cgi->putId('rlg', $rlgId, 'rlg');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a new role.
   */
  protected function databaseAction(): void
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

    $this->rlgId = Abc::$DL->abcSystemRoleGroupUpdate($this->rlgId,
                                                      $wrdId,
                                                      $values['rlg_weight'],
                                                      $values['rlg_label']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadValues(): void
  {
    $this->form->setValues($this->roleGroup);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

