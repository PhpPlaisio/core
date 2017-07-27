<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\System;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Form\FormValidator\SystemModuleInsertCompoundValidator;
use SetBased\Abc\Core\Page\TabPage;
use SetBased\Abc\Form\Control\SelectControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Helper\HttpHeader;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent class for inserting or updating the details of a module.
 */
abstract class ModuleBasePage extends TabPage
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
   * @var int The ID of de module to be updated or inserted.
   */
  protected $mdlId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must implemented by child pages to actually insert or update a module.
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
    $words = Abc::$DL->bblWordGroupGetAllWords(C::WDG_ID_MODULE, $this->lanId);

    $this->form = new CoreForm();

    if ($words)
    {
      // If there are unused modules names (i.e. words in the word group WDG_ID_MODULES that are not used by a
      // module) create a select box with free modules names.
      $input = new SelectControl('wrd_id');
      $input->setOptions($words, 'wrd_id', 'wdt_text');
      $input->setEmptyOption();
      $this->form->addFormControl($input, 'Module Name');
    }

    // Create a text box for (new) module name.
    $input = new TextControl('mdl_name');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $this->form->addFormControl($input, 'Module Name');

    // Create a submit button.
    $this->form->addSubmitButton($this->buttonWrdId, 'handleForm');

    $this->form->addValidator(new SystemModuleInsertCompoundValidator());
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

    HttpHeader::redirectSeeOther(ModuleDetailsPage::getUrl($this->mdlId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
