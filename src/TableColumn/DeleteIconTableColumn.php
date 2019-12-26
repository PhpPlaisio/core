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
   * @inheritdoc
   */
  public function getClasses(array $row): array
  {
    return ['icons-small', 'icons-small-delete'];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
