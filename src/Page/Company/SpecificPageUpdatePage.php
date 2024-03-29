<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Form\Control\HtmlControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;

/**
 * Page for updating the details of a company specific page that overrides a standard page.
 */
class SpecificPageUpdatePage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected CoreForm $form;

  /**
   * The ID of the target page.
   *
   * @var int
   */
  private int $pagIdTarget;

  /**
   * The details of the company specific page.
   *
   * @var array
   */
  private array $targetPageDetails;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->pagIdTarget = Nub::$nub->cgi->getManId('pag-target', 'pag');

    $this->targetPageDetails = Nub::$nub->DL->abcCompanySpecificPageGetDetails($this->targetCmpId,
                                                                               $this->pagIdTarget,
                                                                               $this->lanId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @param int $targetCmpId The ID of the target company.
   * @param int $pagIdTarget The ID of the page.
   *
   * @return string The URL of this page.
   */
  public static function getUrl(int $targetCmpId, int $pagIdTarget): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_COMPANY_SPECIFIC_PAGE_UPDATE, 'pag');
    $url .= Nub::$nub->cgi->putId('cmp', $targetCmpId, 'cmp');
    $url .= Nub::$nub->cgi->putId('pag-target', $pagIdTarget, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the Company specific page after a form submit.
   */
  protected function databaseAction(): void
  {
    if (!$this->form->getChangedControls()) return;

    $values = $this->form->getValues();

    Nub::$nub->DL->abcCompanySpecificPageUpdate($this->targetCmpId, $this->pagIdTarget, $values['pag_class_child']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function htmlTabContent(): ?string
  {
    $this->createForm();
    $this->executeForm();

    return $this->form->htmlFormEmptyIfValid();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm(): void
  {
    $this->form = new CoreForm();

    // Show the ID of the page.
    $input = new HtmlControl('pag_id');
    $input->setText($this->targetPageDetails['pag_id']);
    $this->form->addFormControl($input, 'ID');

    // Show the title of the page.
    $input = new HtmlControl('pag_title');
    $input->setText($this->targetPageDetails['pag_title']);
    $this->form->addFormControl($input, 'Title');

    // Show the parent class name of the page.
    $input = new HtmlControl('pag_class_parent');
    $input->setText($this->targetPageDetails['pag_class_parent']);
    $this->form->addFormControl($input, 'Parent Class');

    // Create text control for the child class name.
    $input = new TextControl('pag_class_child');
    $input->setAttrMaxLength(C::LEN_PAG_CLASS);
    $input->setValue($this->targetPageDetails['pag_class_child']);
    $this->form->addFormControl($input, 'Child Class');

    // Create a submit button.
    $this->form->addSubmitButton(C::WRD_ID_BUTTON_UPDATE, 'handleForm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes the form shown on this page.
   */
  private function executeForm(): void
  {
    $method = $this->form->execute();
    switch ($method)
    {
      case 'handleForm':
        $this->handleForm();
        break;

      default:
        $this->form->defaultHandler($method);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm(): void
  {
    $this->databaseAction();

    $this->response = new SeeOtherResponse(SpecificPageOverviewPage::getUrl($this->targetCmpId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
