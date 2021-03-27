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
use Plaisio\Table\TableColumn\NumberTableColumn;
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
  private array $details;

  /**
   * The ID of the word group shown on this page.
   *
   * @var int
   */
  private int $wdgId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->wdgId = Nub::$nub->cgi->getManId('wdg', 'wdg');

    $this->details = Nub::$nub->DL->abcBabelWordGroupGetDetails($this->wdgId);

    Nub::$nub->assets->appendPageTitle($this->details['wdg_name']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL of this page.
   *
   * @param int $wdgId    The ID of the word group.
   * @param int $lanIdTar The ID of the target language.
   *
   * @return string
   */
  public static function getUrl(int $wdgId, int $lanIdTar): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_BABEL_WORD_GROUP_DETAILS, 'pag');
    $url .= Nub::$nub->cgi->putId('wdg', $wdgId, 'wdg');
    $url .= Nub::$nub->cgi->putId('lan-target', $lanIdTar, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function echoTabContent(): void
  {
    $this->selectLanguage();

    if ($this->lanIdTar)
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
    $is_translator = ($this->lanIdTar!=$this->lanIdRef &&
      Nub::$nub->DL->abcAuthGetPageAuth($this->cmpId, $this->proId, C::PAG_ID_BABEL_WORD_TRANSLATE));

    $words = Nub::$nub->DL->abcBabelWordGroupGetAllWordsTranslator($this->wdgId, $this->lanIdTar);

    $languageRef = Nub::$nub->DL->abcBabelLanguageGetName($this->lanIdRef, $this->lanIdRef);
    $languageTar = Nub::$nub->DL->abcBabelLanguageGetName($this->lanIdTar, $this->lanIdRef);

    $table = new CoreOverviewTable();

    // Add action for inserting a new word to the word group.
    $table->addTableAction('default', new WordInsertTableAction($this->wdgId));

    // Add action for translation all words in the word group.
    if ($is_translator)
    {
      $table->addTableAction('default', new WordTranslateWordsTableAction($this->wdgId, $this->lanIdTar));
    }

    // Show word ID.
    $table->addColumn(new NumberTableColumn('ID', 'wrd_id'));

    // Show label of word.
    if ($this->lanIdTar===$this->lanIdRef)
    {
      $table->addColumn(new TextTableColumn('Label', 'wrd_label'));
    }

    // Show reference text.
    $column = new TextTableColumn($languageRef, 'ref_wdt_text');
    $column->setSortOrder(1);
    $table->addColumn($column);

    // Show target text.
    if ($this->lanIdTar!==$this->lanIdRef)
    {
      $table->addColumn(new TextTableColumn($languageTar, 'tar_wdt_text'));
    }

    // Show remark.
    $table->addColumn(new TextTableColumn('Comment', 'wrd_comment'));

    // Show link to translate the word.
    if ($is_translator)
    {
      $table->addColumn(new WordTranslateIconTableColumn($this->lanIdTar));
    }

    // Show link to modify the word.
    $table->addColumn(new WordUpdateIconTableColumn());

    // Show link to delete the word.
    if (Nub::$nub->DL->abcAuthGetPageAuth($this->cmpId, $this->proId, C::PAG_ID_BABEL_WORD_DELETE))
    {
      $table->addColumn(new WordDeleteIconTableColumn());
    }

    // Generate the HTML code for the table.
    echo $table->getHtmlTable($words);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
