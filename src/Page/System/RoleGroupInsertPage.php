<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Kernel\Nub;

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
  public static function getUrl(): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_ROLE_GROUP_INSERT, 'pag');

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
      $wrdId = Nub::$nub->DL->abcBabelWordInsertWord(C::WDG_ID_ROLE_GROUP, null, null, $values['rlg_name']);
    }
    else
    {
      $wrdId = $values['wrd_id'];
    }

    $this->rlgId = Nub::$nub->DL->abcSystemRoleGroupInsert($wrdId,
                                                           $values['rlg_weight'],
                                                           $values['rlg_label']);
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

