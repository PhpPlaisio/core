<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Kernel\Nub;

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

    $this->targetCmpId = Nub::$nub->cgi->getManId('cmp', 'cmp');
    $this->details     = Nub::$nub->DL->abcCompanyGetDetails($this->targetCmpId);
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
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_COMPANY_UPDATE, 'pag');
    $url .= Nub::$nub->cgi->putId('cmp', $targetCmpId, 'cmp');

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

    Nub::$nub->DL->abcCompanyUpdate($this->targetCmpId, $values['cmp_abbr'], $values['cmp_label']);
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

