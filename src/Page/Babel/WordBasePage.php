<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Form\CoreForm;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Form\Control\SelectControl;
use SetBased\Abc\Form\Control\SpanControl;
use SetBased\Abc\Form\Control\TextControl;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Helper\HttpHeader;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Abstract parent page for pages for inserting and updating a word.
 */
abstract class WordBasePage extends BabelPage
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
   * @var CoreForm.
   */
  protected $form;

  /**
   * The ID of word group of the word (only used for creating a new word).
   *
   * @var int
   */
  protected $wdgId;

  /**
   * The ID of the word.
   *
   * @var int
   */
  protected $wrdId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must implemented by child pages to actually insert or update a word.
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
    $this->echoWordGroupInfo();

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
    $ref_language = Abc::$DL->abcBabelLanguageGetName($this->refLanId, $this->refLanId);

    $this->form = new CoreForm();

    // Create from control for word group name.
    $word_groups = Abc::$DL->abcBabelWordGroupGetAll($this->refLanId);
    $input       = new SelectControl('wdg_id');
    $input->setOptions($word_groups, 'wdg_id', 'wdg_name');
    $input->setValue($this->wdgId);
    $this->form->addFormControl($input, 'Word Group', true);

    // Create form control for ID.
    if ($this->wrdId)
    {
      $input = new SpanControl('wrd_id');
      $input->setInnerText($this->wrdId);
      $this->form->addFormControl($input, 'ID');
    }

    // Create form control for label.
    $input = new TextControl('wrd_label');
    $input->setAttrMaxLength(C::LEN_WRD_LABEL);
    $this->form->addFormControl($input, 'Label');

    // Input for the actual word.
    $input = new TextControl('wdt_text');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $this->form->addFormControl($input, Html::txt2Html($ref_language), true);

    // Create form control for comment.
    $input = new TextControl('wrd_comment');
    $input->setAttrMaxLength(C::LEN_WRD_COMMENT);
    $this->form->addFormControl($input, 'Remark');

    // Create a submit button.
    $this->form->addSubmitButton($this->buttonWrdId, 'handleForm');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos brief info of the word group of the word.
   */
  private function echoWordGroupInfo()
  {
    $group = Abc::$DL->abcBabelWordGroupGetDetails($this->wdgId);

    $table = new CoreDetailTable();

    // Add row for the ID of the word group.
    NumericTableRow::addRow($table, 'ID', $group['wdg_id'], '%d');

    // Add row for the name of the word group.
    TextTableRow::addRow($table, 'Word Group', $group['wdg_name']);

    echo $table->getHtmlTable();
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

