<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\Page\Babel;

use SetBased\Abc\Abc;
use SetBased\Abc\C;
use SetBased\Abc\Core\Table\CoreOverviewTable;
use SetBased\Abc\Core\TableAction\Babel\WordGroupInsertTableAction;
use SetBased\Abc\Core\TableColumn\Babel\WordGroupDetailsIconTableColumn;
use SetBased\Abc\Core\TableColumn\Babel\WordGroupUpdateIconTableColumn;
use SetBased\Abc\Table\TableColumn\NumericTableColumn;
use SetBased\Abc\Table\TableColumn\TextTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Page show an overview of all word groups.
 */
class WordGroupOverviewPage extends BabelPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int|null $targetLanId
   *
   * @return string
   */
  public static function getUrl($targetLanId = null)
  {
    $url = self::putCgiVar('pag', C::PAG_ID_BABEL_WORD_GROUP_OVERVIEW, 'pag');
    $url .= self::putCgiVar('act_lan', $targetLanId, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  protected function echoTabContent()
  {
    $this->selectLanguage();

    if ($this->actLanId)
    {
      $this->showWordGroups();
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Shows the overview of all word groups.
   */
  private function showWordGroups()
  {
    $groups = Abc::$DL->wordGroupGetAll($this->actLanId);

    $table = new CoreOverviewTable();

    // Table action for inserting a new word group.
    $table->addTableAction('default', new WordGroupInsertTableAction());

    // Show word group ID.
    $table->addColumn(new NumericTableColumn('ID', 'wdg_id'));

    // Show word group name.
    $col = $table->addColumn(new TextTableColumn('Word Group', 'wdg_name'));
    $col->setSortOrder(1);

    // Show total words in the word group.
    $table->addColumn(new TextTableColumn('# Words', 'n1'));

    // Show total words to be translated in the word group.
    if ($this->actLanId!=$this->refLanId)
    {
      $table->addColumn(new TextTableColumn('To Do', 'n2'));
    }

    // Add link to the details of the word group.
    $table->addColumn(new WordGroupDetailsIconTableColumn($this->actLanId));

    // Add link to the modify the word group.
    $table->addColumn(new WordGroupUpdateIconTableColumn());

    echo $table->getHtmlTable($groups);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

