<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for inserting a company.
 */
class CompanyInsertPage extends CompanyBasePage
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
   */
  public static function getUrl()
  {
    return self::putCgiId('pag', C::PAG_ID_COMPANY_INSERT, 'pag');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Actually insert the company.
   */
  protected function databaseAction()
  {
    $values = $this->form->getValues();

    $this->targetCmpId = Abc::$DL->abcCompanyInsert($values['cmp_abbr'], $values['cmp_label']);
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

