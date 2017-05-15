<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\Company;

use SetBased\Abc\Core\Page\Company\CompanyDetailsPage;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\TableColumn\DualTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * A dual table column with the ID and name of a company.
 */
class CompanyTableColumn extends DualTableColumn
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
    $url = CompanyDetailsPage::getUrl($row['cmp_id']);

    $ret = '<td class="number link">';
    $ret .= Html::generateElement('a', ['href' => $url], $row['cmp_id']);
    $ret .= '</td>';

    $ret .= Html::generateElement('td', [], $row['cmp_abbr']);

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
