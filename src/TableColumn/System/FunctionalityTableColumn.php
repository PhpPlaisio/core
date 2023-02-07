<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\System;

use Plaisio\Core\Page\System\FunctionalityDetailsPage;
use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;
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
                                       'href'  => FunctionalityDetailsPage::getUrl($row['fun_id'])],
                            'text' => $row['fun_id']]],
               ['tag'  => 'td',
                'attr' => ['class' => $walker->getClasses(['cell', 'text'])],
                'text' => $row['fun_name']]];

    return Html::htmlNested($struct);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
