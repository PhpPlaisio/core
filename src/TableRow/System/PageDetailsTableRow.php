<?php
declare(strict_types=1);

namespace Plaisio\Core\TableRow\System;

use Plaisio\Core\Page\System\PageDetailsPage;
use Plaisio\Helper\Html;
use Plaisio\Table\DetailTable;

/**
 * Table row showing the original page of a page.
 */
class PageDetailsTableRow
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a row with a class name of a page with link to the page details to a detail table.
   *
   * @param DetailTable     $table  The (detail) table.
   * @param int|string|null $header The row header text.
   * @param array           $data   The page details.
   */
  public static function addRow(DetailTable $table, int|string|null $header, array $data): void
  {
    if ($data['pag_id_org']!==null)
    {
      $a = Html::htmlNested(['tag'  => 'a',
                             'attr' => ['href' => PageDetailsPage::getUrl($data['pag_id_org'])],
                             'text' => $data['pag_id_org']]);

      $table->addRow($header, ['class' => $table->renderWalker->getClasses(['cell', 'cell-text'])], $a, true);
    }
    else
    {
      $table->addRow($header, ['class' => $table->renderWalker->getClasses('cell')]);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
