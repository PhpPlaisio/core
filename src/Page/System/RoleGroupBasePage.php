<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Core\Page\TabPage;
use Plaisio\Form\Control\SelectControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;

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
  protected int $buttonWrdId;

  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected CoreForm $form;

  /**
   * The ID of the role group that is been inserted or updated.
   *
   * @var int
   */
  protected int $rlgId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must implemented by child pages to actually insert or update a role of the target company.
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
   * Loads the initial values of the form shown on this page.
   *
   * @return void
   */
  abstract protected function loadValues(): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $this->form = new CoreForm();

    // Create select box for (known) role group names.
    $titles = Nub::$nub->DL->abcBabelWordGroupGetAllWords(C::WDG_ID_ROLE_GROUP, $this->lanId);
    $input  = new SelectControl('wrd_id');
    $input->setOptions($titles, 'wrd_id', 'wdt_text');
    $input->setEmptyOption();
    $input->setOptionsObfuscator(Nub::$nub->obfuscator::create('wrd'));
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

    $this->response = new SeeOtherResponse(RoleGroupOverviewPage::getUrl());
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

