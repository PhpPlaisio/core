<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction;

use Plaisio\Helper\RenderWalker;

/**
 * Interface for table actions.
 */
interface TableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code for this table action.
   *
   * @param RenderWalker $walker The object for walking the row and column tree.
   *
   * @return string
   */
  public function htmlTableAction(RenderWalker $walker): string;

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
