<?php

namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Page\TabPage;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Helper\HttpHeader;
use SetBased\Exception\LogicException;

/**
 * Abstract parent page for pages about companies.
 */
abstract class CompanyPage extends TabPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the company of which data is shown on this page.
   *
   * @var array
   */
  protected $companyDetails;

  /**
   * The ID of the company of which data is shown on this page (i.e. the target company).
   *
   * @var int|null
   */
  protected $targetCmpId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->targetCmpId = Abc::$cgi->getOptId('cmp', 'cmp');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL to a child page of this page.
   *
   * @param int      $pagId       The ID of the child page.
   * @param int|null $targetCmpId The ID of the target company.
   *
   * @return string The URL.
   */
  public static function getChildUrl(int $pagId, ?int $targetCmpId): string
  {
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', $pagId, 'pag');
    $url .= Abc::$cgi->putId('cmp', $targetCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Shows brief information about the target company.
   */
  protected function echoDashboard(): void
  {
    // Return immediately if the cmp_id is not set.
    if (!$this->targetCmpId) return;

    $this->companyDetails = Abc::$DL->abcCompanyGetDetails($this->targetCmpId);

    echo '<div id="dashboard">';
    echo '<div id="info">';

    echo '<div id="info0">';
    echo Html::txt2Html($this->companyDetails['cmp_abbr']);
    echo '<br/>';
    echo '<br/>';
    echo '</div>';

    echo '</div>';
    echo '</div>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    if ($this->targetCmpId)
    {
      Abc::$assets->appendPageTitle($this->companyDetails['cmp_abbr']);
    }
    else
    {
      $this->getCompany();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function getTabUrl(int $pagId): ?string
  {
    if ($this->targetCmpId || $pagId==C::PAG_ID_COMPANY_OVERVIEW)
    {
      return self::getChildUrl($pagId, $this->targetCmpId);
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handle the form submit of the form for selecting a company.
   *
   * @param CoreForm $form The form.
   */
  protected function handleCompanyForm(CoreForm $form): void
  {
    $values            = $form->getValues();
    $this->targetCmpId = Abc::$DL->abcCompanyGetCmpIdByCmpAbbr($values['cmp_abbr']);
    if ($this->targetCmpId!==null)
    {
      HttpHeader::redirectSeeOther(self::getChildUrl(Abc::$requestHandler->getPagId(), $this->targetCmpId));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form for selecting the target company.
   *
   * @return CoreForm
   */
  private function createCompanyForm(): CoreForm
  {
    $form = new CoreForm();

    // Create input control for Company abbreviation.
    $input = new TextControl('cmp_abbr');
    $input->setAttrMaxLength(C::LEN_CMP_ABBR);
    $form->addFormControl($input, 'Company', true);

    // Create "OK" submit button.
    $form->addSubmitButton(C::WRD_ID_BUTTON_OK, 'handleCompanyForm');

    return $form;
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the target company.
   */
  private function getCompany(): void
  {
    $form   = $this->createCompanyForm();
    $method = $form->execute();
    switch ($method)
    {
      case 'handleForm':
        $this->handleCompanyForm($form);
        break;

      default:
        throw new LogicException("Unknown form method '%s'.", $method);
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
