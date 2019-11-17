<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Kernel\Nub;

/**
 * Page for updating the details of a role.
 */
class RoleUpdatePage extends RoleBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the role.
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

    $this->rolId       = Nub::$cgi->getManId('rol', 'rol');
    $this->details     = Nub::$DL->abcCompanyRoleGetDetails($this->targetCmpId, $this->rolId, $this->lanId);
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $targetCmpId The ID of the target company.
   * @param int $rolId       The ID of role to be modified.
   *
   * @return string
   */
  public static function getUrl(int $targetCmpId, int $rolId): string
  {
    $url = Nub::$cgi->putLeader();
    $url .= Nub::$cgi->putId('pag', C::PAG_ID_COMPANY_ROLE_UPDATE, 'pag');
    $url .= Nub::$cgi->putId('cmp', $targetCmpId, 'cmp');
    $url .= Nub::$cgi->putId('rol', $rolId, 'rol');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Update the details of the role.
   */
  protected function databaseAction(): void
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    Nub::$DL->abcCompanyRoleUpdate($this->targetCmpId,
                                   $this->rolId,
                                   $values['rlg_id'],
                                   $values['rol_name'],
                                   $values['rol_weight'],
                                   $values['rol_label']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function loadValues(): void
  {
    $this->form->setValues($this->details);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

