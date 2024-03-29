<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction;

use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;

/**
 * A pseudo table action showing the row count in a (overview) table body.
 */
class RowCountTableAction implements TableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The number of rows in the table body.
   *
   * @var int
   */
  protected int $rowCount;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int $rowCount The number of rows in the table body.
   */
  public function __construct(int $rowCount)
  {
    $this->rowCount = $rowCount;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function htmlTableAction(RenderWalker $walker): string
  {
    $struct = ['tag'  => 'span',
               'attr' => ['class' => $walker->getClasses('table-menu-row-count')],
               'text' => $this->rowCount];

    return Html::htmlNested($struct);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
