<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn;

/**
 * Abstract table column with icon linking to page for updating or editing an entity.
 */
abstract class UpdateIconTableColumn extends IconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->iconUrl  = ICON_SMALL_EDIT;
    $this->altValue = 'edit';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
