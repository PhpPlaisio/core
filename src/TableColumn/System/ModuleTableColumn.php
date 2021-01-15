<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\System;

use Plaisio\Core\Page\System\ModuleDetailsPage;
use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;
use Plaisio\Table\TableColumn\DualTableColumn;

/**
 * A dual table column with the ID and name of a module.
 */
class ModuleTableColumn extends DualTableColumn
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
    $url    = ModuleDetailsPage::getUrl($row['mdl_id']);
    $inner1 = Html::generateElement('a', ['href' => $url], $row['mdl_id']);

    $ret = Html::generateElement('td', ['class' => $walker->getClasses('number')], $inner1, true);
    $ret .= Html::generateElement('td', ['class' => $walker->getClasses('text')], $row['mdl_name']);

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
