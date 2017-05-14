<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\System;

use SetBased\Abc\Core\Page\System\RoleGroupDetailsPage;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\TableColumn\DualTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * A dual table column with the ID and name of a role group.
 */
class RoleGroupTableColumn extends DualTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $headerText The header of this column.
   */
  public function __construct($headerText)
  {
    $this->headerText = $headerText;
    $this->dataType   = 'numeric';
    $this->dataType2  = 'text';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getHtmlCell($row)
  {
    $url = RoleGroupDetailsPage::getUrl($row['rlg_id']);

    $ret = '<td class="number link">';
    $ret .= Html::generateElement('a', ['href' => $url], $row['rlg_id']);
    $ret .= '</td>';

    $ret .= Html::generateElement('td', [], $row['rlg_name']);

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
