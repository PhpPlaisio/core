<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Core\Form\FormValidator\SystemModuleInsertCompoundValidator;
use Plaisio\Core\Page\TabPage;
use Plaisio\Form\Control\SelectControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;

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
   * @return void
   */
  abstract protected function databaseAction(): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $this->createForm();
    $this->loadValues();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Loads the initial values of the form.
   *
   * @return void
   */
  abstract protected function loadValues(): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm(): void
  {
    $words = Nub::$nub->DL->abcBabelWordGroupGetAllWords(C::WDG_ID_MODULE, $this->lanId);

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

    $this->response = new SeeOtherResponse(ModuleDetailsPage::getUrl($this->mdlId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
