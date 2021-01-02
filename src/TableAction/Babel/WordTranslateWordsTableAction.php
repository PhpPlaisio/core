<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\Babel;

use Plaisio\Core\Page\Babel\WordTranslateWordsPage;
use Plaisio\Core\TableAction\TableAction;
use Plaisio\Helper\Html;
use Plaisio\Table\Walker\RenderWalker;

/**
 * Table action action for translation all words in a word group.
 */
class WordTranslateWordsTableAction implements TableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The title of the icon of the table action.
   *
   * @var string
   */
  protected string $title;

  /**
   * The URL of the table action.
   *
   * @var string
   */
  protected string $url;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $wdgId       The ID of the word group of the new word.
   * @param int $targetLanId The ID of the target language.
   */
  public function __construct(int $wdgId, int $targetLanId)
  {
    $this->url   = WordTranslateWordsPage::getUrl($wdgId, $targetLanId);
    $this->title = 'Translate words';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtml(RenderWalker $walker): string
  {
    $classes   = $walker->getClasses('table-menu-icon');
    $classes[] = 'icons-medium';
    $classes[] = 'icons-medium-babel-fish';

    return Html::generateElement('a', ['href' => $this->url, 'title' => $this->title, 'class' => $classes]);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
