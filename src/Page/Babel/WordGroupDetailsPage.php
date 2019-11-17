<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Babel;

use Plaisio\C;
use Plaisio\Core\Table\CoreDetailTable;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\Babel\WordInsertTableAction;
use Plaisio\Core\TableAction\Babel\WordTranslateWordsTableAction;
use Plaisio\Core\TableColumn\Babel\WordDeleteIconTableColumn;
use Plaisio\Core\TableColumn\Babel\WordTranslateIconTableColumn;
use Plaisio\Core\TableColumn\Babel\WordUpdateIconTableColumn;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableColumn\NumericTableColumn;
use Plaisio\Table\TableColumn\TextTableColumn;
use Plaisio\Table\TableRow\IntegerTableRow;
use Plaisio\Table\TableRow\TextTableRow;

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

    $this->wdgId = Nub::$cgi->getManId('wdg', 'wdg');

    $this->details = Nub::$DL->abcBabelWordGroupGetDetails($this->wdgId);

    Nub::$assets->appendPageTitle($this->details['wdg_name']);
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
  public static function getUrl(int $wdgId, int $targetLanId): string
  {
    $url = Nub::$cgi->putLeader();
    $url .= Nub::$cgi->putId('pag', C::PAG_ID_BABEL_WORD_GROUP_DETAILS, 'pag');
    $url .= Nub::$cgi->putId('wdg', $wdgId, 'wdg');
    $url .= Nub::$cgi->putId('act_lan', $targetLanId, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function echoTabContent(): void
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
  private function showWordGroupInfo(): void
  {
    $table = new CoreDetailTable();

    // Add row for the ID of the word group.
    IntegerTableRow::addRow($table, 'ID', $this->details['wdg_id']);

    // Add row for the name of the word group.
    TextTableRow::addRow($table, 'Word Group', $this->details['wdg_name']);

    echo $table->getHtmlTable();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos an overview of all words in a word group.
   */
  private function showWords(): void
  {
    // Determine whether the user is a translator.
    $is_translator = ($this->actLanId!=$this->refLanId &&
      Nub::$DL->abcAuthGetPageAuth($this->cmpId, $this->proId, C::PAG_ID_BABEL_WORD_TRANSLATE));

    $words = Nub::$DL->abcBabelWordGroupGetAllWordsTranslator($this->wdgId, $this->actLanId);

    $ref_language = Nub::$DL->abcBabelLanguageGetName($this->refLanId, $this->refLanId);
    $act_language = Nub::$DL->abcBabelLanguageGetName($this->actLanId, $this->refLanId);

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
    $column = new TextTableColumn($ref_language, 'ref_wdt_text');
    $column->setSortOrder(1);
    $table->addColumn($column);

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
    if (Nub::$DL->abcAuthGetPageAuth($this->cmpId, $this->proId, C::PAG_ID_BABEL_WORD_DELETE))
    {
      $table->addColumn(new WordDeleteIconTableColumn());
    }

    // Generate the HTML code for the table.
    echo $table->getHtmlTable($words);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
