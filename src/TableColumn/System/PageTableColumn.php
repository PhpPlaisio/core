<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\System;

use Plaisio\Core\Page\System\PageDetailsPage;
use Plaisio\Helper\Html;
use Plaisio\Table\TableColumn\DualTableColumn;
use SetBased\Helper\Cast;

/**
 * A dual table column with the ID and class of a page.
 */
class PageTableColumn extends DualTableColumn
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
    $url = PageDetailsPage::getUrl($row['pag_id']);

    $ret = '<td class="number link">';
    $ret .= Html::generateElement('a', ['href' => $url], Cast::toOptString($row['pag_id']));
    $ret .= '</td>';

    $ret .= Html::generateElement('td', [], $row['pag_class']);

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
