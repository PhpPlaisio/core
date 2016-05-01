<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for updating the details of a company.
 */
class CompanyUpdatePage extends CompanyBasePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the target company.
   *
   * @var array
   */
  private $details;

  //--------------------------------------------------------------------------------------------------------------------
  public function __construct()
  {
    parent::__construct();

    $this->actCmpId    = self::getCgiId('cmp', 'cmp');
    $this->details     = Abc::$DL->companyGetDetails($this->actCmpId);
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $cmpId The ID of the target language.
   *
   * @return string
   */
  public static function getUrl($cmpId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_COMPANY_UPDATE, 'pag');
    $url .= self::putCgiVar('cmp', $cmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of the target company.
   */
  protected function databaseAction()
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    Abc::$DL->companyUpdate($this->actCmpId, $values['cmp_abbr'], $values['cmp_label']);
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

