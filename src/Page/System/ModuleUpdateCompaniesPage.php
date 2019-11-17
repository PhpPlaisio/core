<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Form\Control\CoreButtonControl;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Core\Form\SlatControlFactory\SystemModuleUpdateCompaniesSlatControlFactory;
use Plaisio\Core\Page\TabPage;
use Plaisio\Core\Table\CoreDetailTable;
use Plaisio\Form\Control\FieldSet;
use Plaisio\Form\Control\LouverControl;
use Plaisio\Form\Control\SubmitControl;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;
use Plaisio\Table\TableRow\IntegerTableRow;
use Plaisio\Table\TableRow\TextTableRow;

/**
 * Page for granting or revoking a module to or from companies.
 */
class ModuleUpdateCompaniesPage extends TabPage
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

    $this->modId = Nub::$cgi->getManId('mdl', 'mdl');

    $this->details = Nub::$DL->abcSystemModuleGetDetails($this->modId, $this->lanId);

    Nub::$assets->appendPageTitle($this->details['mdl_name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $modId The ID of the module.
   *
   * @return string
   */
  public static function getUrl(int $modId): string
  {
    $url = Nub::$cgi->putLeader();
    $url .= Nub::$cgi->putId('pag', C::PAG_ID_SYSTEM_MODULE_UPDATE_COMPANIES, 'pag');
    $url .= Nub::$cgi->putId('mdl', $modId, 'mdl');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $this->showModule();

    $this->createForm();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm(): void
  {
    // Get all available pages.
    $pages = Nub::$DL->abcSystemModuleGetAvailableCompanies($this->modId);

    // Create form.
    $this->form = new CoreForm();

    // Add field set.
    $field_set = new FieldSet('');
    $this->form->addFieldSet($field_set);

    // Create factory.
    $factory = new SystemModuleUpdateCompaniesSlatControlFactory();
    $factory->enableFilter();

    // Add submit button.
    $button = new CoreButtonControl();
    $submit = new SubmitControl('submit');
    $submit->setMethod('handleForm');
    $submit->setValue(Nub::$babel->getWord(C::WRD_ID_BUTTON_UPDATE));
    $button->addFormControl($submit);

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
  private function databaseAction(): void
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    foreach ($changes['data'] as $cmp_id => $dummy)
    {
      if ($values['data'][$cmp_id]['mdl_granted'])
      {
        Nub::$DL->abcCompanyModuleEnable($cmp_id, $this->modId);
      }
      else
      {
        Nub::$DL->abcCompanyModuleDisable($cmp_id, $this->modId);
      }
    }

    // Use brute force to proper profiles.
    Nub::$DL->abcProfileProper();
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
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  private function handleForm(): void
  {
    $this->databaseAction();

    $this->response = new SeeOtherResponse(ModuleDetailsPage::getUrl($this->modId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos brief info about the functionality.
   */
  private function showModule(): void
  {
    $table = new CoreDetailTable();

    // Add row for the ID of the module.
    IntegerTableRow::addRow($table, 'ID', $this->details['mdl_id']);

    // Add row for the module name.
    TextTableRow::addRow($table, 'Module', $this->details['mdl_name']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
