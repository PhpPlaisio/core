<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Table\CoreDetailTable;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\Babel\WordInsertTableAction;
use SetBased\Abc\Core\TableAction\Babel\WordTranslateWordsTableAction;
use SetBased\Abc\Core\TableColumn\Babel\WordDeleteIconTableColumn;
use SetBased\Abc\Core\TableColumn\Babel\WordTranslateIconTableColumn;
use SetBased\Abc\Core\TableColumn\Babel\WordUpdateIconTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;
use SetBased\Abc\Table\TableRow\NumericTableRow;
use SetBased\Abc\Table\TableRow\TextTableRow;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page with an overview of all words in a word group.
 */
class WordGroupDetailsPage extends BabelPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The details of the word group shown on this page.
   *
   * @var array
   */
  private $details;

  /**
   * The ID of the word group shown on this page.
   *
   * @var int
   */
  private $wdgId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->wdgId = self::getCgiId('wdg', 'wdg');

    $this->details = Abc::$DL->bblWordGroupGetDetails($this->wdgId);

    $this->appendPageTitle($this->details['wdg_name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL of this page.
   *
   * @param int $wdgId       The ID of the word group.
   * @param int $targetLanId The ID of the target language.
   *
   * @return string
   */
  public static function getUrl($wdgId, $targetLanId)
  {
    $url = self::putCgiId('pag', C::PAG_ID_BABEL_WORD_GROUP_DETAILS, 'pag');
    $url .= self::putCgiId('wdg', $wdgId, 'wdg');
    $url .= self::putCgiId('act_lan', $targetLanId, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function echoTabContent()
  {
    $this->selectLanguage();

    if ($this->actLanId)
    {
      $this->showWordGroupInfo();

      $this->showWords();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos brief info about the word group.
   */
  private function showWordGroupInfo()
  {
    $table = new CoreDetailTable();

    // Add row for the ID of the word group.
    NumericTableRow::addRow($table, 'ID', $this->details['wdg_id'], '%d');

    // Add row for the name of the word group.
    TextTableRow::addRow($table, 'Word Group', $this->details['wdg_name']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos an overview of all words in a word group.
   */
  private function showWords()
  {
    // Determine whether the user is a translator.
    $is_translator = ($this->actLanId!=$this->refLanId &&
      Abc::$DL->abcAuthGetPageAuth($this->cmpId, $this->proId, C::PAG_ID_BABEL_WORD_TRANSLATE));

    $words = Abc::$DL->bblWordGroupGetAllWordsTranslator($this->wdgId, $this->actLanId);

    $ref_language = Abc::$DL->bblLanguageGetName($this->refLanId, $this->refLanId);
    $act_language = Abc::$DL->bblLanguageGetName($this->actLanId, $this->refLanId);

    $table = new CoreOverviewTable();

    // Add action for inserting a new word to the word group.
    $table->addTableAction('default', new WordInsertTableAction($this->wdgId));

    // Add action for translation all words in the word group.
    if ($is_translator)
    {
      $table->addTableAction('default', new WordTranslateWordsTableAction($this->wdgId, $this->actLanId));
    }

    // Show word ID.
    $table->addColumn(new NumericTableColumn('ID', 'wrd_id'));

    // Show label of word.
    // Show target text.
    if ($this->actLanId==$this->refLanId)
    {
      $table->addColumn(new TextTableColumn('Label', 'wrd_label'));
    }

    // Show reference text.
    $col = $table->addColumn(new TextTableColumn($ref_language, 'ref_wdt_text'));
    $col->setSortOrder(1);

    // Show target text.
    if ($this->actLanId!=$this->refLanId)
    {
      $table->addColumn(new TextTableColumn($act_language, 'act_wdt_text'));
    }

    // Show remark.
    $table->addColumn(new TextTableColumn('Comment', 'wrd_comment'));

    // Show link to translate the word.
    if ($is_translator)
    {
      $table->addColumn(new WordTranslateIconTableColumn($this->actLanId));
    }

    // Show link to modify the word.
    $table->addColumn(new WordUpdateIconTableColumn());

    // Show link to delete the word.
    if (Abc::$DL->abcAuthGetPageAuth($this->cmpId, $this->proId, C::PAG_ID_BABEL_WORD_DELETE))
    {
      $table->addColumn(new WordDeleteIconTableColumn());
    }

    // Generate the HTML code for the table.
    echo $table->getHtmlTable($words);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
