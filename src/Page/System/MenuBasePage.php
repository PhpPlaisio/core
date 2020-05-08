<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\System;

use Plaisio\C;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Core\Page\TabPage;
use Plaisio\Form\Control\SelectControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Form\Validator\IntegerValidator;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;

/**
 * Abstract parent class for inserting or updating a menu entry.
 */
abstract class MenuBasePage extends TabPage
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
   * Must implemented by child pages to actually insert or update a menu entry.
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
    $titles = Nub::$nub->DL->abcBabelWordGroupGetAllWords(C::WDG_ID_MENU, $this->lanId);
    $input  = new SelectControl('wrd_id');
    $input->setOptions($titles, 'wrd_id', 'wdt_text');
    $input->setOptionsObfuscator(Nub::$nub->getObfuscator('wrd'));
    $input->setEmptyOption();
    $this->form->addFormControl($input, 'Menu Title');

    // Create text box for the title the menu item.
    $input = new TextControl('mnu_title');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $this->form->addFormControl($input, 'Menu Title');

    // Create select box for chose page for menu.
    $pages = Nub::$nub->DL->abcSystemPageGetAll($this->lanId);
    $input = new SelectControl('pag_id');
    $input->setOptions($pages, 'pag_id', 'pag_class');
    $input->setEmptyOption();
    $input->setOptionsObfuscator(Nub::$nub->getObfuscator('pag'));
    $this->form->addFormControl($input, 'Page Class', true);

    // Create text form control for input menu level.
    $input = new TextControl('mnu_level');
    $input->setAttrMaxLength(C::LEN_MNU_LEVEL);
    $input->setValue(1);
    $input->addValidator(new IntegerValidator(0, 100));
    $this->form->addFormControl($input, 'Menu Level', true);

    // Create text form control for input menu group.
    $input = new TextControl('mnu_group');
    $input->setAttrMaxLength(C::LEN_MNU_GROUP);
    $input->addValidator(new IntegerValidator(0, 100));
    $this->form->addFormControl($input, 'Menu Group', true);

    // Create text form control for input menu weight.
    $input = new TextControl('mnu_weight');
    $input->setAttrMaxLength(C::LEN_MNU_WEIGHT);
    $input->addValidator(new IntegerValidator(0, 999));
    $this->form->addFormControl($input, 'Menu Weight', true);

    // Create text box for URL of the menu item.
    $input = new TextControl('mnu_link');
    $input->setAttrMaxLength(C::LEN_MNU_LINK);
    $this->form->addFormControl($input, 'Menu Link');

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

    $this->response = new SeeOtherResponse(MenuOverviewPage::getUrl());
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
