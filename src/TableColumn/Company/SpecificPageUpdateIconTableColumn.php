<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Core\TableColumn\Company;

use SetBased\Abc\Core\Page\Company\SpecificPageUpdatePage;
use SetBased\Abc\Core\TableColumn\UpdateIconTableColumn;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Table column with icon linking to page for updating the details of a company specific page that overrides a standard
 * page.
 */
class SpecificPageUpdateIconTableColumn extends UpdateIconTableColumn
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
   * Object constructor.
   *
   * @param int $targetCmpId The ID of the target company.
   */
  public function __construct($targetCmpId)
  {
    parent::__construct();

    $this->targetCmpId = $targetCmpId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * {@inheritdoc}
   */
  public function getUrl($row)
  {
    return SpecificPageUpdatePage::getUrl($this->targetCmpId, $row['pag_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
