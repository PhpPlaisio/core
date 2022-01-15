<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Core\Page\PlaisioCorePage;
use Plaisio\Form\Control\SelectControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;

/**
 * Abstract parent page for inserting and updating a functionality.
 */
abstract class FunctionalityBasePage extends PlaisioCorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the word for the text of the submit button of the form shown on this page.
   *
   * @var int
   */
  protected int $buttonWrdId;

  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected CoreForm $form;

  /**
   * The ID of the inserted or updated functionality.
   *
   * @var int
   */
  protected int $funId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must be implemented by child pages to actually insert or update a functionality.
   *
   * @return void
   */
  abstract protected function dataBaseAction(): void;

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
    $modules = Nub::$nub->DL->abcSystemModuleGetAll($this->lanId);
    $words   = Nub::$nub->DL->abcBabelWordGroupGetAllWords(C::WDG_ID_FUNCTIONALITIES, $this->lanId);

    $this->form = new CoreForm();

    // Input for module.
    $input = new SelectControl('mdl_id');
    $input->setOptions($modules, 'mdl_id', 'mdl_name');
    $input->setEmptyOption();
    $this->form->addFormControl($input, 'Module', true);

    // Input for functionality name.
    // @todo Make control for reusing a word or create a new word.
    $input = new SelectControl('wrd_id');
    $input->setOptions($words, 'wrd_id', 'wdt_text');
    $input->setEmptyOption();
    $this->form->addFormControl($input, 'Name');

    $input = new TextControl('fun_name');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $this->form->addFormControl($input, 'Name');

    // Create a submit button.
    $this->form->addSubmitButton($this->buttonWrdId, 'handleForm');
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
    $this->dataBaseAction();

    $this->response = new SeeOtherResponse(FunctionalityDetailsPage::getUrl($this->funId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
