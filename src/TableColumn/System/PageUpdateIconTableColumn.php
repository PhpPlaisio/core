<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\System;

use SetBased\Abc\Core\Page\System\PageUpdatePage;
use SetBased\Abc\Core\TableColumn\UpdateIconTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column with icon linking to page for updating the details of a target page.
 */
class PageUpdateIconTableColumn extends UpdateIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getUrl($row)
  {
    return PageUpdatePage::getUrl($row['pag_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
