<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Core\Page\CoreCorePage;
use Plaisio\Form\Control\DatabaseLabelControl;
use Plaisio\Form\Control\SelectControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;

/**
 * Abstract parent page for inserting or modifying a page group.
 */
abstract class TabBasePage extends CoreCorePage
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

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must be implemented by child pages to actually insert or update a page group.
   *
   * @return void
   */
  abstract protected function databaseAction(): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Handles the form submit.
   */
  protected function handleForm(): void
  {
    $this->databaseAction();

    $this->response = new SeeOtherResponse(TabOverviewPage::getUrl());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function htmlTabContent(): ?string
  {
    $this->createForm();
    $this->loadValues();
    $this->executeForm();

    return $this->form->htmlFormEmptyIfValid();
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
    $this->form = new CoreForm();

    // Input for (known) page titles.
    $titles = Nub::$nub->DL->abcBabelWordGroupGetAllWords(C::WDG_ID_PAGE_GROUP_TITLE, $this->lanId);
    $input  = new SelectControl('wrd_id');
    $input->setOptions($titles, 'wrd_id', 'wdt_text');
    $input->setEmptyOption();
    $this->form->addFormControl($input, 'Title');

    // Input for (new) page title.
    $input = new TextControl('ptb_title');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $this->form->addFormControl($input, 'Title');

    // Input for the page label.
    $input = new DatabaseLabelControl('ptb_label', 'PTB_ID', C::LEN_PTB_LABEL);
    $this->form->addFormControl($input, 'Label', true);

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
}

//----------------------------------------------------------------------------------------------------------------------
