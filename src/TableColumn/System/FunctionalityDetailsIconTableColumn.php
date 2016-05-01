<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\System;

use SetBased\Abc\Core\Page\System\FunctionalityDetailsPage;
use SetBased\Abc\Core\TableColumn\DetailsIconTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column with icon linking to page with information about a functionality.
 */
class FunctionalityDetailsIconTableColumn extends DetailsIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getUrl($row)
  {
    return FunctionalityDetailsPage::getUrl($row['fun_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
