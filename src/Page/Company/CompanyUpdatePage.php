<?php

namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;

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
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->targetCmpId = Abc::$cgi->getManId('cmp', 'cmp');
    $this->details     = Abc::$DL->abcCompanyGetDetails($this->targetCmpId);
    $this->buttonWrdId = C::WRD_ID_BUTTON_UPDATE;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int $targetCmpId The ID of the target language.
   *
   * @return string
   */
  public static function getUrl(int $targetCmpId): string
  {
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_COMPANY_UPDATE, 'pag');
    $url .= Abc::$cgi->putId('cmp', $targetCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the details of the target company.
   */
  protected function databaseAction(): void
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    Abc::$DL->abcCompanyUpdate($this->targetCmpId, $values['cmp_abbr'], $values['cmp_label']);
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

