<?php

namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\Control\CoreButtonControl;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\SlatControlFactory\SystemPageUpdateFunctionalitiesSlatControlFactory;
use SetBased\Abc\Core\Page\TabPage;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Core\TableRow\System\PageDetailsTableRow;
use SetBased\Abc\Form\Control\FieldSet;
use SetBased\Abc\Form\Control\LouverControl;
use SetBased\Abc\Form\Control\SubmitControl;
use SetBased\Abc\Helper\HttpHeader;
use SetBased\Abc\Table\TableRow\IntegerTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

/**
 * Page for modifying the functionalities that grant access to a target page.
 */
class PageUpdateFunctionalitiesPage extends TabPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the target page.
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
   * The ID of the target page.
   *
   * @var int
   */
  private $targetPagId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->targetPagId = Abc::$cgi->getManId('tar_pag', 'pag');
    $this->details     = Abc::$DL->abcSystemPageGetDetails($this->targetPagId, $this->lanId);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL to this page.
   *
   * @param int $pagId The Id of the target page.
   *
   * @return string
   */
  public static function getUrl(int $pagId): string
  {
    $url = Abc::$cgi->putLeader();
    $url .= Abc::$cgi->putId('pag', C::PAG_ID_SYSTEM_PAGE_UPDATE_FUNCTIONALITIES, 'pag');
    $url .= Abc::$cgi->putId('tar_pag', $pagId, 'pag');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the functionalities that grant access to the target page.
   */
  protected function databaseAction(): void
  {
    $changes = $this->form->getChangedControls();
    $values  = $this->form->getValues();

    // Return immediately if no changes are submitted.
    if (empty($changes)) return;

    foreach ($changes['data'] as $fun_id => $dummy)
    {
      if ($values['data'][$fun_id]['fun_checked'])
      {
        Abc::$DL->abcSystemFunctionalityInsertPage($fun_id, $this->targetPagId);
      }
      else
      {
        Abc::$DL->abcSystemFunctionalityDeletePage($fun_id, $this->targetPagId);
      }
    }

    // Use brute force to proper profiles.
    Abc::$DL->abcProfileProper();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $this->showPageDetails();

    $this->createForm();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm(): void
  {
    // Get all functionalities.
    $pages = Abc::$DL->abcSystemPageGetAvailableFunctionalities($this->targetPagId, $this->lanId);

    // Create form.
    $this->form = new CoreForm();

    // Add field set.
    $field_set = new FieldSet('');
    $this->form->addFieldSet($field_set);

    // Create factory.
    $factory = new SystemPageUpdateFunctionalitiesSlatControlFactory();
    $factory->enableFilter();

    // Add submit button.
    $button = new CoreButtonControl();
    $submit = new SubmitControl('submit');
    $submit->setMethod('handleForm');
    $submit->setValue(Abc::$babel->getWord(C::WRD_ID_BUTTON_UPDATE));
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

    HttpHeader::redirectSeeOther(PageDetailsPage::getUrl($this->targetPagId));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the details of the target page.
   */
  private function showPageDetails(): void
  {
    $details = Abc::$DL->abcSystemPageGetDetails($this->targetPagId, $this->lanId);
    $table   = new CoreDetailTable();

    // Add row with the ID of the page.
    IntegerTableRow::addRow($table, 'ID', $details['pag_id']);

    // Add row with the title of the page.
    TextTableRow::addRow($table, 'Title', $details['pag_title']);

    // Add row with the tab name of the page.
    TextTableRow::addRow($table, 'Tab', $details['ptb_name']);

    // Add row with the ID of the parent page of the page.
    PageDetailsTableRow::addRow($table, 'Original Page', $details);

    // Add row with the menu item of the page.
    TextTableRow::addRow($table, 'Menu', $details['mnu_name']);

    // Add row with the class name of the page.
    TextTableRow::addRow($table, 'Class', $details['pag_class']);

    // Add row with the label of the page.
    TextTableRow::addRow($table, 'Label', $details['pag_label']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
