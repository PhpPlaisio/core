<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Babel;

use Plaisio\C;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\Babel\WordGroupInsertTableAction;
use Plaisio\Core\TableColumn\Babel\WordGroupTableColumn;
use Plaisio\Core\TableColumn\Babel\WordGroupUpdateIconTableColumn;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableColumn\NumericTableColumn;
use Plaisio\Table\TableColumn\TextTableColumn;

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
  public static function getUrl(?int $targetLanId = null): string
  {
    $url = Nub::$cgi->putLeader();
    $url .= Nub::$cgi->putId('pag', C::PAG_ID_BABEL_WORD_GROUP_OVERVIEW, 'pag');
    $url .= Nub::$cgi->putId('act_lan', $targetLanId, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
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
  private function showWordGroups(): void
  {
    $groups = Nub::$DL->abcBabelWordGroupGetAll($this->actLanId);

    $table = new CoreOverviewTable();

    // Table action for inserting a new word group.
    $table->addTableAction('default', new WordGroupInsertTableAction());

    // Show ID and name of the word group
    $column = new WordGroupTableColumn('Word Group', $this->actLanId);
    $column->setSortOrder(1);
    $table->addColumn($column);

    // Show total words in the word group.
    $table->addColumn(new NumericTableColumn('# Words', 'n1'));

    // Show total words to be translated in the word group.
    if ($this->actLanId!=$this->refLanId)
    {
      $table->addColumn(new NumericTableColumn('To Do', 'n2'));
    }

    // Add link to the modify the word group.
    $table->addColumn(new WordGroupUpdateIconTableColumn());

    echo $table->getHtmlTable($groups);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

