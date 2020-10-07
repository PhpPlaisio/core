<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Company;

use Plaisio\C;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Form\Control\SelectControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;
use SetBased\Helper\Cast;

/**
 * Page for inserting a company specific page that overrides a standard page.
 */
class SpecificPageInsertPage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected CoreForm $form;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @param int $targetCmpId The ID of the target company.
   *
   * @return string
   */
  public static function getUrl(int $targetCmpId): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_COMPANY_SPECIFIC_PAGE_INSERT, 'pag');
    $url .= Nub::$nub->cgi->putId('cmp', $targetCmpId, 'cmp');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Inserts a company specific page.
   */
  protected function databaseAction(): void
  {
    $values = $this->form->getValues();

    Nub::$nub->DL->abcCompanySpecificPageInsert($this->targetCmpId, $values['prt_pag_id'], $values['pag_class_child']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $this->createForm();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm(): void
  {
    $pages = Nub::$nub->DL->abcSystemPageGetAll($this->lanId);

    $this->form = new CoreForm();

    // Input for parent class.
    $input = new SelectControl('prt_pag_id');
    $input->setOptions($pages, 'pag_id', 'pag_class');
    $input->setOptionsObfuscator(Nub::$nub->obfuscator::create('pag'));
    $this->form->addFormControl($input, 'Parent Class');

    // Input for company specific page.
    $input = new TextControl('pag_class_child');
    $input->setAttrMax(Cast::toOptString(C::LEN_PAG_CLASS));
    $this->form->addFormControl($input, 'Child Class');

    // Create a submit button.
    $this->form->addSubmitButton(C::WRD_ID_BUTTON_INSERT, 'handleForm');
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
