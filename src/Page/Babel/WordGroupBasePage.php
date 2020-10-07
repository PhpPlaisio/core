<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Babel;

use Plaisio\C;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Form\Control\SpanControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Response\SeeOtherResponse;
use SetBased\Helper\Cast;

/**
 * Abstract parent page for inserting and updating a word group.
 */
abstract class WordGroupBasePage extends BabelPage
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
   * The ID of the word group.
   *
   * @var int|null
   */
  protected ?int $wdgId = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must be implemented by a child page to actually insert or update a word group.
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
    $this->setValues();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the initial values of the form shown on this page.
   *
   * @return void
   */
  abstract protected function setValues(): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm(): void
  {
    $this->form = new CoreForm();

    // Show word group ID (update only).
    if ($this->wdgId!==null)
    {
      $input = new SpanControl('wdg_id');
      $input->setInnerText($this->wdgId);
      $this->form->addFormControl($input, 'ID');
    }

    // Input for the name of the word group.
    $input = new TextControl('wdg_name');
    $input->setAttrMaxLength(C::LEN_WDG_NAME);
    $this->form->addFormControl($input, 'Name', true);

    // Input for the label of the word group.
    $input = new TextControl('wdg_label');
    $input->setAttrMaxLength(C::LEN_WRD_LABEL);
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

    $this->response = new SeeOtherResponse(WordGroupDetailsPage::getUrl($this->wdgId, $this->actLanId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

