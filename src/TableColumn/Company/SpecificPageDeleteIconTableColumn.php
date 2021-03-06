<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\Company;

use Plaisio\Core\Page\Company\SpecificPageDeletePage;
use Plaisio\Core\TableColumn\DeleteIconTableColumn;

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
  private int $targetCmpId;

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
  public function getUrl(array $row): ?string
  {
    $this->confirmMessage = 'Remove page "'.$row['pag_class_child'].'?'; // xxxbbl

    return SpecificPageDeletePage::getUrl($this->targetCmpId, $row['pag_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
