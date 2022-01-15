<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\Babel;

use Plaisio\Core\Page\Babel\WordTranslateWordsPage;
use Plaisio\Core\TableAction\TableAction;
use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;

/**
 * Table action for translation all words in a word group.
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
   * @param int $wdgId    The ID of the word group of the new word.
   * @param int $lanIdTar The ID of the target language.
   */
  public function __construct(int $wdgId, int $lanIdTar)
  {
    $this->url   = WordTranslateWordsPage::getUrl($wdgId, $lanIdTar);
    $this->title = 'Translate words';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function htmlTableAction(RenderWalker $walker): string
  {
    $struct = ['tag'  => 'a',
               'attr' => ['class' => $walker->getClasses('table-menu-icon', ['icons-medium',
                                                                             'icons-medium-babel-fish']),
                          'href'  => $this->url,
                          'title' => $this->title],
               'html' => null];

    return Html::htmlNested($struct);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
