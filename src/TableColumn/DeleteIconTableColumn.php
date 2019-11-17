<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn;

/**
 * Abstract table column with icon deleting an entity.
 */
abstract class DeleteIconTableColumn extends IconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->iconUrl  = ICON_SMALL_DELETE;
    $this->altValue = 'delete';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
