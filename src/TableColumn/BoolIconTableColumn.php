<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn;

use Plaisio\Helper\Html;
use Plaisio\Table\TableColumn\TableColumn;
use SetBased\Helper\Cast;

/**
 * Table column for cells with an icon for boolean values.
 */
class BoolIconTableColumn extends TableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The field name of the data row used for generating this table column.
   *
   * @var string
   */
  protected $fieldName;

  /**
   * If set false values are shown explicitly. Otherwise when the value evaluates to false an empty cell is shown.
   *
   * @var bool
   */
  private $showFalse;

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
  public function getHtmlCell(array $row): string
  {
    $attributes = ['class' => 'bool'];

    switch (true)
    {
      case $row[$this->fieldName]===1:
      case $row[$this->fieldName]==='1':
      case $row[$this->fieldName]===true:
        $attributes['data-value'] = 1;
        $html                     = Html::generateElement('span', ['class' => ['icons-small', 'icons-small-true']]);
        break;

      case $row[$this->fieldName]===0:
      case $row[$this->fieldName]==='0':
      case $row[$this->fieldName]==='':
      case $row[$this->fieldName]===null:
      case $row[$this->fieldName]===false:
        $attributes['data-value'] = 0;
        if ($this->showFalse)
        {
          $html = Html::generateElement('span', ['class' => ['icons-small', 'icons-small-false']]);
        }
        else
        {
          $html = '';
        }
        break;

      default:
        $attributes['data-value'] = $row[$this->fieldName];
        $html                     = Html::txt2Html(Cast::toOptString($row[$this->fieldName]));
    }

    return Html::generateElement('td', $attributes, $html, true);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
