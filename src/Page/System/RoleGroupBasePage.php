<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Page\TabPage;
use SetBased\Abc\Form\Control\SelectControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Helper\HttpHeader;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent page for inserting and updating details of a role group for the target company.
 */
abstract class RoleGroupBasePage extends TabPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the word for the text of the submit button of the form shown on this page.
   *
   * @var int
   */
  protected $buttonWrdId;

  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected $form;

  /**
   * The ID of the role group that is been inserted or updated.
   *
   * @var int
   */
  protected $rlgId;

  //--------------------------------------------------------------------------------------------------------------------

  /**
   * Must implemented by child pages to actually insert or update a role of the target company.
   *
   * @return null
   */
  abstract protected function databaseAction();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->createForm();
    $this->loadValues();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the initial values of the form shown on this page.
   *
   * @return null
   */
  abstract protected function loadValues();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $this->form = new CoreForm();

    // Create select box for (known) role group names.
    $titles = Abc::$DL->abcBabelWordGroupGetAllWords(C::WDG_ID_ROLE_GROUP, $this->lanId);
    $input  = new SelectControl('wrd_id');
    $input->setOptions($titles, 'wrd_id', 'wdt_text');
    $input->setEmptyOption();
    $input->setOptionsObfuscator(Abc::getObfuscator('wrd'));
    $this->form->addFormControl($input, 'Name');

    // Create text box for (new) page title.
    $input = new TextControl('rlg_name');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $this->form->addFormControl($input, 'Name');
    /** @todo Add validator: either wrd_id is not empty or wdg_name is not empty */

    // Input for weight.
    $input = new TextControl('rlg_weight');
    $input->setAttrMaxLength(C::LEN_RLG_WEIGHT);
    $this->form->addFormControl($input, 'Weight');

    // Input for label.
    $input = new TextControl('rlg_label');
    $input->setAttrMaxLength(C::LEN_RLG_LABEL);
    $this->form->addFormControl($input, 'Label');

    // Create a submit button.
    $this->form->addSubmitButton($this->buttonWrdId, 'handleForm');
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

    HttpHeader::redirectSeeOther(RoleGroupOverviewPage::getUrl());
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

