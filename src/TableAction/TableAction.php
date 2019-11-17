<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction;

/**
 * Interface for table actions.
 */
interface TableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this table action.
   *
   * @return string
   */
  public function getHtml(): string;

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
