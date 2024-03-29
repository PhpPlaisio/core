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
   * @param int|string|null $header The header of this column.
   */
  public function __construct(int|string|null $header)
  {
    parent::__construct('number', 'text', $header);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function htmlCell(RenderWalker $walker, array $row): string
  {
    $struct = [['tag'   => 'td',
                'attr'  => ['class' => $walker->getClasses(['cell', 'number'])],
                'inner' => ['tag'  => 'a',
                            'attr' => ['class' => 'link',
                                       'href'  => ModuleDetailsPage::getUrl($row['mdl_id'])],
                            'text' => $row['mdl_id']]],
               ['tag'  => 'td',
                'attr' => ['class' => $walker->getClasses(['cell', 'text'])],
                'text' => $row['mdl_name']]];

    return Html::htmlNested($struct);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
