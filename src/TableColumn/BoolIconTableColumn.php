<?php

namespace SetBased\Abc\Core\TableColumn;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\TableColumn\TableColumn;

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
   * @param string|int|null $headerText The header text of this table column.
   * @param string          $fieldName  The key to be used for getting the value from the data row.
   * @param bool            $showFalse  If set for false values an icon is shown, otherwise the cell is empty for
   *                                    false values.
   */
  public function __construct($headerText, string $fieldName, bool $showFalse = false)
  {
    parent::__construct('bool');

    $this->headerText = $headerText;
    $this->fieldName  = $fieldName;
    $this->showFalse  = $showFalse;
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
        $attributes['data-value'] = 1;
        $html                     = '<img src="'.ICON_SMALL_TRUE.'" alt="1"/>';
        break;

      case $row[$this->fieldName]===0:
      case $row[$this->fieldName]==='0':
      case $row[$this->fieldName]==='':
      case $row[$this->fieldName]===null:
      case $row[$this->fieldName]===false:
        $attributes['data-value'] = 0;
        $html                     = ($this->showFalse) ? '<img src="'.ICON_SMALL_FALSE.'" alt="0"/>' : '';
        break;

      default:
        $attributes['data-value'] = $row[$this->fieldName];
        $html                     = Html::txt2Html($row[$this->fieldName]);
    }

    return Html::generateElement('td', $attributes, $html, true);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
