<?php

namespace SetBased\Abc\Core\TableColumn\Company;

use SetBased\Abc\Core\Page\Company\RoleDetailsPage;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\TableColumn\DualTableColumn;

/**
 * A dual table column with the ID and the name of a role.
 */
class RoleTableColumn extends DualTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $headerText The header of this column.
   */
  public function __construct($headerText)
  {
    parent::__construct('numeric', 'text', $headerText);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtmlCell(array $row): string
  {
    $url = RoleDetailsPage::getUrl($row['cmp_id'], $row['rol_id']);

    $ret = '<td class="number link">';
    $ret .= Html::generateElement('a', ['href' => $url], $row['rol_id']);
    $ret .= '</td>';

    $ret .= Html::generateElement('td', [], $row['rol_name']);

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
