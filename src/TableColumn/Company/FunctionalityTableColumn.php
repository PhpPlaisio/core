<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\Company;

use Plaisio\Core\Page\System\FunctionalityDetailsPage;
use Plaisio\Helper\Html;
use Plaisio\Table\TableColumn\DualTableColumn;

/**
 * A dual table column with the ID and name of a functionality.
 */
class FunctionalityTableColumn extends DualTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $headerText The header of this column.
   */
  public function __construct($headerText)
  {
    parent::__construct('numeric', 'text', $headerText);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtmlCell(array $row): string
  {
    $url = FunctionalityDetailsPage::getUrl($row['fun_id']);

    $ret = '<td class="number link">';
    $ret .= Html::generateElement('a', ['href' => $url], $row['fun_id']);
    $ret .= '</td>';

    $ret .= Html::generateElement('td', [], $row['fun_name']);

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
