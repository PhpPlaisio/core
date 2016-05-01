<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\Babel;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\Control\CoreButtonControl;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\SlatControlFactory\SystemModuleUpdateCompaniesSlatControlFactory;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\LouverControl;
use SetBased\Abc\Form\Control\SubmitControl;
use SetBased\Abc\Helper\HttpHeader;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page for granting or revoking a module to or from companies.
 */
class ModuleUpdateCompaniesPage extends CorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the module.
   *
   * @var array
   */
  private $details;

  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  private $form;

  /**
   * The ID of the module that will be granted or revoked to or from companies.
   *
   * @var int
   */
  private $modId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->modId = self::getCgiId('mdl', 'mdl');

    $this->details = Abc::$DL->systemModuleGetDetails($this->modId, $this->lanId);

    $this->appendPageTitle($this->details['mdl_name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $modId The ID of the module.
   *
   * @return string
   */
  public static function getUrl($modId)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_SYSTEM_MODULE_UPDATE_COMPANIES, 'pag');
    $url .= self::putCgiVar('mdl', $modId, 'mdl');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->showModule();

    $this->createForm();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    // Get all available pages.
    $pages = Abc::$DL->systemModuleGetAvailableCompanies($this->modId);

    // Create form.
    $this->form = new CoreForm();

    // Add field set.
    $field_set = new FieldSet('');
    $this->form->addFieldSet($field_set);

    // Create factory.
    $factory = new SystemModuleUpdateCompaniesSlatControlFactory();
    $factory->enableFilter();

    // Add submit button.
    $button = new CoreButtonControl('');
    $submit = new SubmitControl('submit');
    $submit->setValue(Babel::getWord(C::WRD_ID_BUTTON_UPDATE));
    $button->addFormControl($submit);
    $this->form->addSubmitHandler($button, 'handleForm');

    // Put everything together in a LouverControl.
    $louver = new LouverControl('data');
    $louver->addClass('overview_table');
    $louver->setRowFactory($factory);
    $louver->setFooterControl($button);
    $louver->setData($pages);
    $louver->populate();

    // Add the lover control to the form.
    $field_set->addFormControl($louver);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit, i.e. add or removes pages to the functionality.
   */
  private function databaseAction()
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    foreach ($changes['data'] as $cmp_id => $dummy)
    {
      if ($values['data'][$cmp_id]['mdl_granted'])
      {
        Abc::$DL->companyModuleEnable($cmp_id, $this->modId);
      }
      else
      {
        Abc::$DL->companyModuleDisable($cmp_id, $this->modId);
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
      case  'handleForm':
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

    HttpHeader::redirectSeeOther(ModuleDetailsPage::getUrl($this->modId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos brief info about the functionality.
   */
  private function showModule()
  {
    $table = new CoreDetailTable();

    // Add row for the ID of the module.
    NumericTableRow::addRow($table, 'ID', $this->details['mdl_id'], '%d');

    // Add row for the module name.
    TextTableRow::addRow($table, 'Module', $this->details['mdl_name']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
