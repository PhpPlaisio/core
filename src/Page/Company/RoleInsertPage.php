<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
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
   * @param int $cmpId The ID of the target company.
   *
   * @return string
   */
  public static function getUrl($cmpId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_COMPANY_ROLE_INSERT, 'pag');
    $url .= self::putCgiVar('cmp', $cmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a new role.
   */
  protected function databaseAction()
  {
    $values = $this->form->getValues();

    $this->rolId = Abc::$DL->companyRoleInsert($this->actCmpId, $values['rol_name'], $values['rol_weight']);
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

