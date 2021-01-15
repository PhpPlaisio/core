<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\Babel;

use Plaisio\Core\Page\Babel\WordGroupDetailsPage;
use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;
use Plaisio\Table\TableColumn\DualTableColumn;

/**
 * A dual table column with the ID and name of a word group.
 */
class WordGroupTableColumn extends DualTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the target language.
   *
   * @var int
   */
  private int $lanIdTarget;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $header      The header of this column.
   * @param int             $lanIdTarget The ID of the target language.
   */
  public function __construct($header, int $lanIdTarget)
  {
    parent::__construct('number', 'text', $header);

    $this->lanIdTarget = $lanIdTarget;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtmlCell(RenderWalker $walker, array $row): string
  {
    $url    = WordGroupDetailsPage::getUrl($row['wdg_id'], $this->lanIdTarget);
    $inner1 = Html::generateElement('a', ['href' => $url], $row['wdg_id']);

    $ret = Html::generateElement('td', ['class' => $walker->getClasses('number')], $inner1, true);
    $ret .= Html::generateElement('td', ['class' => $walker->getClasses('text')], $row['wdg_name']);

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
