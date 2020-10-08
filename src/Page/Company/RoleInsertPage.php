<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Kernel\Nub;
use SetBased\Helper\Cast;

/**
 * Page for inserting a new role.
 */
class RoleInsertPage extends RoleBasePage
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
   * @param int $targetCmpId The ID of the target company.
   *
   * @return string
   */
  public static function getUrl(int $targetCmpId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_COMPANY_ROLE_INSERT, 'pag');
    $url .= Nub::$nub->cgi->putId('cmp', $targetCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a new role.
   */
  protected function databaseAction(): void
  {
    $values = $this->form->getValues();

    $this->rolId = Nub::$nub->DL->abcCompanyRoleInsert($this->targetCmpId,
                                                       $values['rlg_id'],
                                                       $values['rol_name'],
                                                       Cast::toOptInt($values['rol_weight']),
                                                       $values['rol_label']);
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

