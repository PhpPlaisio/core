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
 * Abstract parent page for inserting or modifying a page.
 */
abstract class PageBasePage extends TabPage
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
   * The ID of the page created or modified
   *
   * @var int .
   */
  protected $targetPagId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must implemented by child pages to actually insert or update a functionality.
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
    $this->form = new CoreForm();

    // Create select box for (known) page titles.
    $titles = Nub::$nub->DL->abcBabelWordGroupGetAllWords(C::WDG_ID_PAGE_TITLE, $this->lanId);
    $input  = new SelectControl('wrd_id');
    $input->setOptions($titles, 'wrd_id', 'wdt_text');
    $input->setEmptyOption();
    $input->setOptionsObfuscator(Nub::$nub->getObfuscator('wrd'));
    $this->form->addFormControl($input, 'Title');

    // Create text box for (new) page title.
    $input = new TextControl('pag_title');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $this->form->addFormControl($input, 'Title');
    /** @todo Add validator: either wrd_id is not empty or pag_title is not empty */

    // Create form control for page tab group.
    $tabs  = Nub::$nub->DL->abcSystemTabGetAll($this->lanId);
    $input = new SelectControl('ptb_id');
    $input->setOptions($tabs, 'ptb_id', 'ptb_label');
    $input->setEmptyOption();
    $this->form->addFormControl($input, 'Page Tab');

    // Create form control for original page.
    $pages = Nub::$nub->DL->abcSystemPageGetAllMasters($this->lanId);
    $input = new SelectControl('pag_id_org');
    $input->setOptions($pages, 'pag_id', 'pag_class');
    $input->setEmptyOption();
    $input->setOptionsObfuscator(Nub::$nub->getObfuscator('pag'));
    $this->form->addFormControl($input, 'Original Page');

    // Create form control for menu item with which the page is associated..
    $menus = Nub::$nub->DL->abcSystemMenuGetAllEntries($this->lanId);
    $input = new SelectControl('mnu_id');
    $input->setOptions($menus, 'mnu_id', 'mnu_name');
    $input->setEmptyOption();
    $input->setOptionsObfuscator(Nub::$nub->getObfuscator('mnu'));
    $this->form->addFormControl($input, 'Menu');

    // Create form control for page alias.
    $input = new TextControl('pag_alias');
    $input->setAttrMaxLength(C::LEN_PAG_ALIAS);
    $this->form->addFormControl($input, 'Alias');

    // Create form control for page class.
    $input = new TextControl('pag_class');
    $input->setAttrMaxLength(C::LEN_PAG_CLASS);
    $this->form->addFormControl($input, 'Class', true);

    // Create form control for the page label.
    $input = new TextControl('pag_label');
    $input->setAttrMaxLength(C::LEN_PAG_LABEL);
    $this->form->addFormControl($input, 'Label');

    // Create form control for the weight of the page (inside a tab group).
    /** @todo validate weight is a number and/or form control or validator for numeric input. */
    $input = new TextControl('pag_weight');
    $input->setAttrMaxLength(C::LEN_PAG_WEIGHT);
    $this->form->addFormControl($input, 'Weight');

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

    $this->response = new SeeOtherResponse(PageDetailsPage::getUrl($this->targetPagId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
