<?php

namespace SetBased\Abc\Core\TableColumn\Company;

use SetBased\Abc\Core\Page\Company\SpecificPageDeletePage;
use SetBased\Abc\Core\TableColumn\DeleteIconTableColumn;

/**
 * Table with column for deleting a company specific page that overrides a standard page.
 */
class SpecificPageDeleteIconTableColumn extends DeleteIconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the target company.
   *
   * @var int
   */
  private $targetCmpId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @param int $targetCmpId The ID of the target company.
   */
  public function __construct(int $targetCmpId)
  {
    parent::__construct();

    $this->targetCmpId = $targetCmpId;
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getUrl(array $row): string
  {
    $this->confirmMessage = 'Remove page "'.$row['pag_class_child'].'?'; // xxxbbl

    return SpecificPageDeletePage::getUrl($this->targetCmpId, $row['pag_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
