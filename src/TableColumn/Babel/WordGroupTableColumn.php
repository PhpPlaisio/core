<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\Babel;

use Plaisio\Core\Page\Babel\WordGroupDetailsPage;
use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;
use Plaisio\Table\TableColumn\DualTableColumn;

/**
 * A dual table column with the ID and name of a word group.
 */
class WordGroupTableColumn extends DualTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The ID of the target language.
   *
   * @var int
   */
  private int $lanIdTar;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param int|string|null $header   The header of this column.
   * @param int             $lanIdTar The ID of the target language.
   */
  public function __construct(int|string|null $header, int $lanIdTar)
  {
    parent::__construct('number', 'text', $header);

    $this->lanIdTar = $lanIdTar;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function htmlCell(RenderWalker $walker, array $row): string
  {
    $struct = [['tag'   => 'td',
                'attr'  => ['class' => $walker->getClasses(['cell', 'cell-number'])],
                'inner' => ['tag'  => 'a',
                            'attr' => ['class' => 'link',
                                       'href'  => WordGroupDetailsPage::getUrl($row['wdg_id'], $this->lanIdTar)],
                            'text' => $row['wdg_id']]],
               ['tag'  => 'td',
                'attr' => ['class' => $walker->getClasses(['cell', 'cell-text'])],
                'text' => $row['wdg_name']]];

    return Html::htmlNested($struct);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
