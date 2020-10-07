<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\Company;

use Plaisio\Core\Page\Company\SpecificPageUpdatePage;
use Plaisio\Core\TableColumn\UpdateIconTableColumn;

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
  private int $targetCmpId;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
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
    return SpecificPageUpdatePage::getUrl($this->targetCmpId, $row['pag_id']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
