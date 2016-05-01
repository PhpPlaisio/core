<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Helper\HttpHeader;
use SetBased\Exception\LogicException;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent page for pages about companies.
 */
abstract class CompanyPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the company of which data is shown on this page (i.e. the target company).
   *
   * @var int
   */
  protected $actCmpId;

  /**
   * The details of the company of which data is shown on this page.
   *
   * @var array
   */
  protected $companyDetails;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->actCmpId = self::getCgiId('cmp', 'cmp');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL to a child page of this page.
   *
   * @param int $pagId The ID of the child page.
   * @param int $cmpId The ID of the target company.
   *
   * @return string The URL.
   */
  public static function getChildUrl($pagId, $cmpId)
  {
    $url = self::putCgiVar('pag', $pagId, 'pag');
    $url .= self::putCgiVar('cmp', $cmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Shows brief information about the target company.
   */
  protected function echoDashboard()
  {
    // Return immediately if the cmp_id is not set.
    if (!$this->actCmpId) return;

    $this->companyDetails = Abc::$DL->companyGetDetails($this->actCmpId);

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
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    if ($this->actCmpId)
    {
      $this->appendPageTitle($this->companyDetails['cmp_abbr']);
    }
    else
    {
      $this->getCompany();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  protected function getTabUrl($pagId)
  {
    if ($this->actCmpId || $pagId==C::PAG_ID_COMPANY_OVERVIEW)
    {
      return self::getChildUrl($pagId, $this->actCmpId);
    }

    return null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handle the form submit of the form for selecting a company.
   *
   * @param CoreForm $form The form.
   */
  protected function handleCompanyForm($form)
  {
    $abc = Abc::getInstance();

    $values         = $form->getValues();
    $this->actCmpId = Abc::$DL->companyGetCmpIdByCmpAbbr($values['cmp_abbr']);
    if ($this->actCmpId) HttpHeader::redirectSeeOther(self::getChildUrl($abc->getPagId(), $this->actCmpId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form for selecting the target company.
   */
  private function createCompanyForm()
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
  private function getCompany()
  {
    $form   = $this->createCompanyForm();
    $method = $form->execute();
    switch ($method)
    {
      case  'handleForm':
        $this->handleCompanyForm($form);
        break;

      default:
        throw new LogicException("Unknown form method '%s'.", $method);
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

