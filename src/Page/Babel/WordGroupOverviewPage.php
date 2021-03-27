<?php
declare(strict_types=1);

namespace Plaisio\Core\Page\Babel;

use Plaisio\C;
use Plaisio\Core\Table\CoreOverviewTable;
use Plaisio\Core\TableAction\Babel\WordGroupInsertTableAction;
use Plaisio\Core\TableColumn\Babel\WordGroupTableColumn;
use Plaisio\Core\TableColumn\Babel\WordGroupUpdateIconTableColumn;
use Plaisio\Kernel\Nub;
use Plaisio\Table\TableColumn\NumberTableColumn;

/**
 * Page show an overview of all word groups.
 */
class WordGroupOverviewPage extends BabelPage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the relative URL for this page.
   *
   * @param int|null $lanIdTar
   *
   * @return string
   */
  public static function getUrl(?int $lanIdTar = null): string
  {
    $url = Nub::$nub->cgi->putLeader();
    $url .= Nub::$nub->cgi->putId('pag', C::PAG_ID_BABEL_WORD_GROUP_OVERVIEW, 'pag');
    $url .= Nub::$nub->cgi->putId('lan-target', $lanIdTar, 'lan');

    return $url;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  protected function echoTabContent(): void
  {
    $this->selectLanguage();

    if ($this->lanIdTar)
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
    $groups = Nub::$nub->DL->abcBabelWordGroupGetAll($this->lanIdTar);

    $table = new CoreOverviewTable();

    // Table action for inserting a new word group.
    $table->addTableAction('default', new WordGroupInsertTableAction());

    // Show ID and name of the word group
    $column = new WordGroupTableColumn('Word Group', $this->lanIdTar);
    $column->setSortOrder1(1);
    $table->addColumn($column);

    // Show total words in the word group.
    $table->addColumn(new NumberTableColumn('# Words', 'n1'));

    // Show total words to be translated in the word group.
    if ($this->lanIdTar!==$this->lanIdRef)
    {
      $table->addColumn(new NumberTableColumn('To Do', 'n2'));
    }

    // Add link to the modify the word group.
    $table->addColumn(new WordGroupUpdateIconTableColumn());

    echo $table->getHtmlTable($groups);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

