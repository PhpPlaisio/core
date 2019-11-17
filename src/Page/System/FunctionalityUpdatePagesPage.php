<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Form\Control\CoreButtonControl;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Core\Form\SlatControlFactory\SystemFunctionalityUpdatePagesSlatControlFactory;
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
 * Page for setting the pages that a functionality grants access.
 */
class FunctionalityUpdatePagesPage extends TabPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  private $form;

  /**
   * The ID of the functionality of which the pages that belong to it will be modified.
   *
   * @var int
   */
  private $funId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->funId = Nub::$cgi->getManId('fun', 'fun');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $funId The ID of the functionality.
   *
   * @return string
   */
  public static function getUrl(int $funId): string
  {
    $url = Nub::$cgi->putLeader();
    $url .= Nub::$cgi->putId('pag', C::PAG_ID_SYSTEM_FUNCTIONALITY_UPDATE_PAGES, 'pag');
    $url .= Nub::$cgi->putId('fun', $funId, 'fun');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $this->showFunctionality();

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
    $pages = Nub::$DL->abcSystemFunctionalityGetAvailablePages($this->funId);

    // Create form.
    $this->form = new CoreForm();

    // Add field set.
    $field_set = new FieldSet('');
    $this->form->addFieldSet($field_set);

    // Create factory.
    $factory = new SystemFunctionalityUpdatePagesSlatControlFactory();
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

    foreach ($changes['data'] as $pag_id => $dummy)
    {
      if ($values['data'][$pag_id]['pag_enabled'])
      {
        Nub::$DL->abcSystemFunctionalityInsertPage($this->funId, $pag_id);
      }
      else
      {
        Nub::$DL->abcSystemFunctionalityDeletePage($this->funId, $pag_id);
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

    $this->response = new SeeOtherResponse(FunctionalityDetailsPage::getUrl($this->funId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos brief info about the functionality.
   */
  private function showFunctionality(): void
  {
    $details = Nub::$DL->abcSystemFunctionalityGetDetails($this->funId, $this->lanId);

    $table = new CoreDetailTable();

    // Add row for the ID of the function.
    IntegerTableRow::addRow($table, 'ID', $details['fun_id']);

    // Add row for the module name to which the function belongs.
    TextTableRow::addRow($table, 'Module', $details['mdl_name']);

    // Add row for the name of the function.
    TextTableRow::addRow($table, 'Functionality', $details['fun_name']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
