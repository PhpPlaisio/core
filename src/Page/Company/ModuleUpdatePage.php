<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Company;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\Control\CoreButtonControl;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\SlatControlFactory\CompanyModulesUpdateSlatControlFactory;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\LouverControl;
use SetBased\Abc\Form\Control\SubmitControl;
use SetBased\Abc\Helper\HttpHeader;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for enabling and disabling the modules for a company.
 */
class ModuleUpdatePage extends CompanyPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  private $form;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of this page.
   *
   * @param int $cmpId The ID of the target company.
   *
   * @return string
   */
  public static function getUrl($cmpId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_COMPANY_MODULE_UPDATE, 'pag');
    $url .= self::putCgiVar('cmp', $cmpId, 'cmp');

    return $url;
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
    // Get all available modules.
    $modules = Abc::$DL->companyModuleGetAllAvailable($this->actCmpId, $this->lanId);

    // Create the form.
    $this->form = new CoreForm();

    // Add field set.
    $field_set = new FieldSet('');
    $this->form->addFieldSet($field_set);

    // Create factory.
    $factory = new CompanyModulesUpdateSlatControlFactory();
    $factory->enableFilter();

    // Add submit button.
    $button = new CoreButtonControl('');
    $submit = new SubmitControl('submit');
    $submit->setValue(Babel::getWord(C::WRD_ID_BUTTON_OK));
    $button->addFormControl($submit);
    $this->form->addSubmitHandler($button, 'handleForm');

    // Put everything together in a LoverControl.
    $louver = new LouverControl('data');
    $louver->addClass('overview_table');
    $louver->setRowFactory($factory);
    $louver->setFooterControl($button);
    $louver->setData($modules);
    $louver->populate();

    // Add the LouverControl the the form.
    $field_set->addFormControl($louver);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   *  Handles the form submit.
   */
  private function databaseAction()
  {
    $values  = $this->form->getValues();
    $changes = $this->form->getChangedControls();

    // If no changes are submitted return immediately.
    if (empty($changes)) return;

    foreach ($changes['data'] as $mdl_id => $dummy)
    {
      if ($values['data'][$mdl_id]['mdl_enabled'])
      {
        Abc::$DL->companyModuleEnable($this->actCmpId, $mdl_id);
      }
      else
      {
        Abc::$DL->companyModuleDisable($this->actCmpId, $mdl_id);
      }
    }

    // Use brute force to proper profiles.
    Abc::$DL->profileProper();
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

    HttpHeader::redirectSeeOther(ModuleOverviewPage::getUrl($this->actCmpId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
