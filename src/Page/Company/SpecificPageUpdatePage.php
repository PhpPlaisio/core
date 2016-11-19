<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Form\Control\HtmlControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Helper\HttpHeader;

//----------------------------------------------------------------------------------------------------------------------
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
  protected $form;

  /**
   * The ID of the target page.
   *
   * @var int
   */
  private $targetPagId;

  /**
   * The details om the company specific page.
   *
   * @var array
   */
  private $targetPageDetails;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->targetPagId = self::getCgiId('tar_pag', 'pag');

    $this->targetPageDetails = Abc::$DL->companySpecificPageGetDetails($this->targetCmpId,
                                                                       $this->targetPagId,
                                                                       $this->lanId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @param int $targetCmpId The ID of the target company.
   * @param int $targetPagId The ID of the page.
   *
   * @return string The URL of this page.
   */
  public static function getUrl($targetCmpId, $targetPagId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_COMPANY_SPECIFIC_PAGE_UPDATE, 'pag');
    $url .= self::putCgiVar('cmp', $targetCmpId, 'cmp');
    $url .= self::putCgiVar('tar_pag', $targetPagId, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the Company specific page after a form submit.
   */
  protected function databaseAction()
  {
    if (!$this->form->getChangedControls()) return;

    $values = $this->form->getValues();

    Abc::$DL->companySpecificPageUpdate($this->targetCmpId, $this->targetPagId, $values['pag_class_child']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->createForm();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $this->form = new CoreForm();

    // Show the ID of the page.
    $input = new HtmlControl('pag_id');
    $input->setHtml($this->targetPageDetails['pag_id']);
    $this->form->addFormControl($input, 'ID');

    // Show the title of the page.
    $input = new HtmlControl('pag_title');
    $input->setHtml($this->targetPageDetails['pag_title']);
    $this->form->addFormControl($input, 'Title');

    // Show the parent class name of the page.
    $input = new HtmlControl('pag_class_parent');
    $input->setHtml($this->targetPageDetails['pag_class_parent']);
    $this->form->addFormControl($input, 'Parent Class');

    // Create text control for the child class name.
    $input = new TextControl('pag_class_child');
    $input->setValue($this->targetPageDetails['pag_class_child']);
    $this->form->addFormControl($input, 'Child Class');

    // Create a submit button.
    $this->form->addSubmitButton(C::WRD_ID_BUTTON_UPDATE, 'handleForm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Executes the form shown on this page.
   */
  private function executeForm()
  {
    $method = $this->form->execute();
    switch ($method)
    {
      case 'handleForm':
        $this->handleForm();
        break;

      default:
        $this->form->defaultHandler($method);
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm()
  {
    $this->databaseAction();

    HttpHeader::redirectSeeOther(SpecificPageOverviewPage::getUrl($this->targetCmpId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
