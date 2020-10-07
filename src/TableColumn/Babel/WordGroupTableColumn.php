<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\Babel;

use Plaisio\Core\Page\Babel\WordGroupDetailsPage;
use Plaisio\Helper\Html;
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
  private $lanIdTarget;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $header      The header of this column.
   * @param int             $lanIdTarget The ID of the target language.
   */
  public function __construct($header, int $lanIdTarget)
  {
    parent::__construct('numeric', 'text', $header);

    $this->lanIdTarget = $lanIdTarget;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtmlCell(array $row): string
  {
    $url = WordGroupDetailsPage::getUrl($row['wdg_id'], $this->lanIdTarget);

    $ret = '<td class="number link">';
    $ret .= Html::generateElement('a', ['href' => $url], $row['wdg_id']);
    $ret .= '</td>';

    $ret .= Html::generateElement('td', [], $row['wdg_name']);

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
