<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction;

use Plaisio\Helper\Html;
use Plaisio\Table\Walker\RenderWalker;

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
  public function getHtml(RenderWalker $walker): string
  {
    return Html::generateElement('span', ['class' => $walker->getClasses('table-menu-row-count')], $this->rowCount);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
