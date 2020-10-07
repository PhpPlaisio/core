<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Kernel\Nub;

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
  private array $roleGroup;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->rlgId     = Nub::$nub->cgi->getManId('rlg', 'rlg');
    $this->roleGroup = Nub::$nub->DL->abcSystemRoleGroupGetDetails($this->rlgId, $this->lanId);

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
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_SYSTEM_ROLE_GROUP_UPDATE, 'pag');
    $url .= Nub::$nub->cgi->putId('rlg', $rlgId, 'rlg');

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

    $this->rlgId = Nub::$nub->DL->abcSystemRoleGroupUpdate($this->rlgId,
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

