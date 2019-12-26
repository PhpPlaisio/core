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
   * @inheritdoc
   */
  public function getClasses(array $row): array
  {
    return ['icons-small', 'icons-small-edit'];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
