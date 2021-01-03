<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\System;

use Plaisio\Core\Page\System\FunctionalityDetailsPage;
use Plaisio\Helper\Html;
use Plaisio\Table\TableColumn\DualTableColumn;
use Plaisio\Table\Walker\RenderWalker;

/**
 * A dual table column with the ID and name of a functionality.
 */
class FunctionalityTableColumn extends DualTableColumn
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
    $url = FunctionalityDetailsPage::getUrl($row['fun_id']);
    $inner1 = Html::generateElement('a', ['href' => $url], $row['fun_id']);

    $ret = Html::generateElement('td', ['class' => $walker->getClasses('number')], $inner1, true);
    $ret .= Html::generateElement('td', ['class' => $walker->getClasses('text')], $row['fun_name']);

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
