<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Page\CorePage;
use SetBased\Abc\Form\Control\SelectControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Helper\HttpHeader;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent page for inserting or modifying a page group.
 */
abstract class TabBasePage extends CorePage
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

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must implemented by child pages to actually insert or update a page group.
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
   * Handles the form submit.
   */
  protected function handleForm()
  {
    $this->databaseAction();

    HttpHeader::redirectSeeOther(TabOverviewPage::getUrl());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the initial values of the form.
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

    // Create select box for (known) page titles.
    $titles = Abc::$DL->wordGroupGetAllWords(C::WDG_ID_PAGE_GROUP_TITLE, $this->lanId);
    $input  = new SelectControl('wrd_id');
    $input->setOptions($titles, 'wrd_id', 'wrd_text');
    $input->setEmptyOption();
    $this->form->addFormControl($input, 'Title');

    // Create text box for (new) page title.
    $input = new TextControl('ptb_title');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $this->form->addFormControl($input, 'Title');

    // Create form control for the page label.
    $input = new TextControl('ptb_label');
    $input->setAttrMaxLength(C::LEN_PTB_LABEL);
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
      case  'handleForm':
        $this->handleForm();
        break;

      default:
        $this->form->defaultHandler($method);
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
