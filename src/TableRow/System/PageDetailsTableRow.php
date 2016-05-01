<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableRow\System;

use SetBased\Abc\Core\Page\System\PageDetailsPage;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\DetailTable;

//----------------------------------------------------------------------------------------------------------------------
/**
 *
 */
class PageDetailsTableRow
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a row with a class name of a page with link to the page details to a detail table.
   *
   * @param DetailTable $table  The (detail) table.
   * @param string      $header The row header text.
   * @param array       $data   The page details.
   */
  public static function addRow($table, $header, $data)
  {
    $row = '<tr><th>';
    $row .= Html::txt2Html($header);
    $row .= '</th><td class="text"><a';
    $row .= Html::generateAttribute('href', PageDetailsPage::getUrl($data['pag_id_org']));
    $row .= '>';
    $row .= $data['pag_id_org'];
    $row .= '</a></td></tr>';

    $table->addRow($row);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
