<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\Control\CoreButtonControl;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\SlatControlFactory\SystemFunctionalityUpdatePagesSlatControlFactory;
use SetBased\Abc\Core\Page\TabPage;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\LouverControl;
use SetBased\Abc\Form\Control\SubmitControl;
use SetBased\Abc\Helper\HttpHeader;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
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

    $this->funId = self::getCgiId('fun', 'fun');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $funId The ID of the functionality.
   *
   * @return string
   */
  public static function getUrl($funId)
  {
    $url = self::putCgiId('pag', C::PAG_ID_SYSTEM_FUNCTIONALITY_UPDATE_PAGES, 'pag');
    $url .= self::putCgiId('fun', $funId, 'fun');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->showFunctionality();

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
    $pages = Abc::$DL->abcSystemFunctionalityGetAvailablePages($this->funId);

    // Create form.
    $this->form = new CoreForm();

    // Add field set.
    $field_set = new FieldSet('');
    $this->form->addFieldSet($field_set);

    // Create factory.
    $factory = new SystemFunctionalityUpdatePagesSlatControlFactory();
    $factory->enableFilter();

    // Add submit button.
    $button = new CoreButtonControl('');
    $submit = new SubmitControl('submit');
    $submit->setValue(Abc::$babel->getWord(C::WRD_ID_BUTTON_UPDATE));
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

    foreach ($changes['data'] as $pag_id => $dummy)
    {
      if ($values['data'][$pag_id]['pag_enabled'])
      {
        Abc::$DL->abcSystemFunctionalityInsertPage($this->funId, $pag_id);
      }
      else
      {
        Abc::$DL->abcSystemFunctionalityDeletePage($this->funId, $pag_id);
      }
    }

    // Use brute force to proper profiles.
    Abc::$DL->abcProfileProper();
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

    HttpHeader::redirectSeeOther(FunctionalityDetailsPage::getUrl($this->funId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos brief info about the functionality.
   */
  private function showFunctionality()
  {
    $details = Abc::$DL->abcSystemFunctionalityGetDetails($this->funId, $this->lanId);

    $table = new CoreDetailTable();

    // Add row for the ID of the function.
    NumericTableRow::addRow($table, 'ID', $details['fun_id'], '%d');

    // Add row for the module name to which the function belongs.
    TextTableRow::addRow($table, 'Module', $details['mdl_name']);

    // Add row for the name of the function.
    TextTableRow::addRow($table, 'Functionality', $details['fun_name']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
