<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Form\Control\SpanControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Helper\HttpHeader;

//----------------------------------------------------------------------------------------------------------------------
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
  protected $buttonWrdId;

  /**
   * The form shown on this page.
   *
   * @var CoreForm
   */
  protected $form;

  /**
   * The ID of the word group.
   *
   * @var int
   */
  protected $wdgId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must be implemented by a child page to actually insert or update a word group.
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
    $this->setValues();
    $this->executeForm();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the initial values of the form shown on this page.
   *
   * @return null
   */
  abstract protected function setValues();

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Creates the form shown on this page.
   */
  private function createForm()
  {
    $this->form = new CoreForm();

    // Show word group ID (update only).
    if ($this->wdgId)
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

    HttpHeader::redirectSeeOther(WordGroupDetailsPage::getUrl($this->wdgId, $this->actLanId));
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

