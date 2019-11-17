<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction\Babel;

use Plaisio\Core\Page\Babel\WordTranslateWordsPage;
use Plaisio\Core\TableAction\TableAction;
use Plaisio\Helper\Html;

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
  protected $title;

  /**
   * The URL of the table action.
   *
   * @var string
   */
  protected $url;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $wdgId       The ID of the word group of the new word.
   * @param int $targetLanId The ID of the target language.
   */
  public function __construct(int $wdgId, int $targetLanId)
  {
    $this->url = WordTranslateWordsPage::getUrl($wdgId, $targetLanId);

    $this->title = 'Translate words';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtml(): string
  {
    $ret = '<a';
    $ret .= Html::generateAttribute('href', $this->url);
    $ret .= '><img';
    $ret .= Html::generateAttribute('title', $this->title);
    $ret .= Html::generateAttribute('src', ICON_BABEL_FISH);
    $ret .= ' width="16" height="16" alt="translate"/></a>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
