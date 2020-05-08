<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Core\Page\TabPage;
use Plaisio\Form\Control\TextControl;
use Plaisio\Helper\Html;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;
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
   * @var int
   */
  protected $targetCmpId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->targetCmpId = Nub::$nub->cgi->getManId('cmp', 'cmp');
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
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', $pagId, 'pag');
    $url .= Nub::$nub->cgi->putId('cmp', $targetCmpId, 'cmp');

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

    $this->companyDetails = Nub::$nub->DL->abcCompanyGetDetails($this->targetCmpId);

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
      Nub::$nub->assets->appendPageTitle($this->companyDetails['cmp_abbr']);
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
    $this->targetCmpId = Nub::$nub->DL->abcCompanyGetCmpIdByCmpAbbr($values['cmp_abbr']);
    if ($this->targetCmpId!==null)
    {
      $this->response = new SeeOtherResponse(self::getChildUrl(Nub::$nub->requestHandler->getPagId(), $this->targetCmpId));
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
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
