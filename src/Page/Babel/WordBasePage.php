<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Babel;

use Plaisio\C;
use Plaisio\Core\Form\CoreForm;
use Plaisio\Core\Html\VerticalLayout;
use Plaisio\Core\Table\CoreDetailTable;
use Plaisio\Form\Control\DatabaseLabelControl;
use Plaisio\Form\Control\HtmlControl;
use Plaisio\Form\Control\SelectControl;
use Plaisio\Form\Control\TextControl;
use Plaisio\Kernel\Nub;
use Plaisio\Response\SeeOtherResponse;
use Plaisio\Table\TableRow\IntegerTableRow;
use Plaisio\Table\TableRow\TextTableRow;

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
  protected int $buttonWrdId;

  /**
   * The form shown on this page.
   *
   * @var CoreForm.
   */
  protected CoreForm $form;

  /**
   * The ID of word group of the word (only used for creating a new word).
   *
   * @var int
   */
  protected int $wdgId;

  /**
   * The ID of the word.
   *
   * @var int|null
   */
  protected ?int $wrdId = null;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Must be implemented by child pages to actually insert or update a word.
   *
   * @return void
   */
  abstract protected function databaseAction(): void;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function htmlTabContent(): ?string
  {
    $this->createForm();
    $this->setValues();
    $this->executeForm();

    if ($this->response===null)
    {
      $layout = new VerticalLayout();
      $layout->addBlock($this->htmlWordGroupInfo())
             ->addBlock($this->form->htmlForm());
      $html = $layout->html();
    }
    else
    {
      $html = '';
    }

    return $html;
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
    $languageRef = Nub::$nub->DL->abcBabelLanguageGetName($this->lanIdRef, $this->lanIdRef);

    $this->form = new CoreForm();

    // Create from control for word group name.
    $word_groups = Nub::$nub->DL->abcBabelWordGroupGetAll($this->lanIdRef);
    $input       = new SelectControl('wdg_id');
    $input->setOptions($word_groups, 'wdg_id', 'wdg_name');
    $input->setValue($this->wdgId);
    $this->form->addFormControl($input, 'Word Group', true);

    // Create form control for ID.
    if ($this->wrdId!==null)
    {
      $input = new HtmlControl('wrd_id');
      $input->setText($this->wrdId);
      $this->form->addFormControl($input, 'ID');
    }

    // Input for the actual word.
    $input = new TextControl('wdt_text');
    $input->setAttrMaxLength(C::LEN_WDT_TEXT);
    $this->form->addFormControl($input, $languageRef, true);

    // Create form control for comment.
    $input = new TextControl('wrd_comment');
    $input->setAttrMaxLength(C::LEN_WRD_COMMENT);
    $this->form->addFormControl($input, 'Remark');

    // Create form control for label.
    $input = new DatabaseLabelControl('wrd_label', 'WRD_ID', C::LEN_WRD_LABEL);
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

    $this->response = new SeeOtherResponse(WordGroupDetailsPage::getUrl($this->wdgId, $this->lanIdTar));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns brief info of the word group of the word.
   */
  private function htmlWordGroupInfo(): string
  {
    $group = Nub::$nub->DL->abcBabelWordGroupGetDetails($this->wdgId);

    $table = new CoreDetailTable();

    // Add row for the ID of the word group.
    IntegerTableRow::addRow($table, 'ID', $group['wdg_id']);

    // Add row for the name of the word group.
    TextTableRow::addRow($table, 'Word Group', $group['wdg_name']);

    return $table->htmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

