<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction;

use Plaisio\Helper\Html;

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
  protected $rowCount;

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
  public function getHtml(): string
  {
    return Html::generateElement('span', ['class' => 'row_count'], $this->rowCount);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
