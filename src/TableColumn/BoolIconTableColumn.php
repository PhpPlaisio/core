<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn;

use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;
use Plaisio\Table\TableColumn\UniTableColumn;

/**
 * Table column for cells with an icon for boolean values.
 */
class BoolIconTableColumn extends UniTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The field name of the data row used for generating this table column.
   *
   * @var string
   */
  protected string $fieldName;

  /**
   * If set false values are shown explicitly. Otherwise, when the value evaluates to false an empty cell is shown.
   *
   * @var bool
   */
  private bool $showFalse;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $header     The header text of this table column.
   * @param string          $fieldName  The key to be used for getting the value from the data row.
   * @param bool            $showFalse  If set for false values an icon is shown, otherwise the cell is empty for
   *                                    false values.
   */
  public function __construct($header, string $fieldName, bool $showFalse = false)
  {
    parent::__construct('bool', $header);

    $this->fieldName = $fieldName;
    $this->showFalse = $showFalse;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function htmlCell(RenderWalker $walker, array $row): string
  {
    switch (true)
    {
      case $row[$this->fieldName]===1:
      case $row[$this->fieldName]==='1':
      case $row[$this->fieldName]===true:
        $value = 1;
        $inner = ['tag'  => 'span',
                  'attr' => ['class' => ['icons-small', 'icons-small-true']],
                  'html' => null];
        break;

      case $row[$this->fieldName]===0:
      case $row[$this->fieldName]==='0':
      case $row[$this->fieldName]==='':
      case $row[$this->fieldName]===null:
      case $row[$this->fieldName]===false:
        $value = 0;
        if ($this->showFalse)
        {
          $inner = ['tag'  => 'span',
                    'attr' => ['class' => ['icons-small', 'icons-small-false']],
                    'html' => null];
        }
        else
        {
          $value = null;
          $inner = null;
        }
        break;

      default:
        $value = $row[$this->fieldName];
        $inner = ['text' => $row[$this->fieldName]];
    }

    $struct = ['tag'   => 'td',
               'attr'  => ['class'      => $walker->getClasses(['cell', 'bool']),
                           'data-value' => $value],
               'inner' => $inner];

    return Html::htmlNested($struct);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
