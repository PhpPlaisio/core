<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\System;

use Plaisio\Core\Page\System\PageDetailsPage;
use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;
use Plaisio\Table\TableColumn\DualTableColumn;

/**
 * A dual table column with the ID and class of a page.
 */
class PageTableColumn extends DualTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $header The header of this column.
   */
  public function __construct($header)
  {
    parent::__construct('number', 'text', $header);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtmlCell(RenderWalker $walker, array $row): string
  {
    $url    = PageDetailsPage::getUrl($row['pag_id']);
    $inner1 = Html::generateElement('a', ['href' => $url], $row['pag_id']);

    $ret = Html::generateElement('td', ['class' => $walker->getClasses(['cell', 'number'])], $inner1, true);
    $ret .= Html::generateElement('td', ['class' => $walker->getClasses(['cell', 'text'])], $row['pag_class']);

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
