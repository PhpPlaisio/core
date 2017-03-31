<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
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

    $this->rolId       = self::getCgiId('rol', 'rol');
    $this->details     = Abc::$DL->abcCompanyRoleGetDetails($this->targetCmpId, $this->rolId);
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
  public static function getUrl($targetCmpId, $rolId)
  {
    $url = self::putCgiId('pag', C::PAG_ID_COMPANY_ROLE_UPDATE, 'pag');
    $url .= self::putCgiId('cmp', $targetCmpId, 'cmp');
    $url .= self::putCgiId('rol', $rolId, 'rol');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Update the details of the role.
   */
  protected function databaseAction()
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    Abc::$DL->abcCompanyRoleUpdate($this->targetCmpId, $this->rolId, $values['rol_name'], $values['rol_weight']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function loadValues()
  {
    $this->form->setValues($this->details);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

