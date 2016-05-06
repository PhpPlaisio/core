<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableRow\System;

use SetBased\Abc\Core\Page\System\PageDetailsPage;
use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\DetailTable;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table row showing the original page of a page.
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
    $a = Html::generateElement('a', ['href' => PageDetailsPage::getUrl($data['pag_id_org'])], $data['pag_id_org']);

    $table->addRow($header, ['class' => 'text'], $a, true);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
