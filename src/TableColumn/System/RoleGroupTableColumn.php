<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\System;

use Plaisio\Core\Page\System\RoleGroupDetailsPage;
use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;
use Plaisio\Table\TableColumn\DualTableColumn;

/**
 * A dual table column with the ID and name of a role group.
 */
class RoleGroupTableColumn extends DualTableColumn
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
    $url    = RoleGroupDetailsPage::getUrl($row['rlg_id']);
    $inner1 = Html::generateElement('a', ['href' => $url], $row['rlg_id']);

    $ret = Html::generateElement('td', ['class' => $walker->getClasses(['cell', 'number'])], $inner1, true);
    $ret .= Html::generateElement('td', ['class' => $walker->getClasses(['cell', 'text'])], $row['rlg_name']);

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
