<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn;

/**
 * Abstract table column with icon linking to page with details or information of an entity.
 */
abstract class DetailsIconTableColumn extends IconTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getClasses(array $row): array
  {
    return ['icons-small', 'icons-small-details'];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
