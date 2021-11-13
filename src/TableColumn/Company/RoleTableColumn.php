<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\Company;

use Plaisio\Core\Page\Company\RoleDetailsPage;
use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;
use Plaisio\Table\TableColumn\DualTableColumn;

/**
 * A dual table column with the ID and the name of a role.
 */
class RoleTableColumn extends DualTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string|int|null $header The header of this column.
   */
  public function __construct($header)
  {
    parent::__construct('number', 'text', $header);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtmlCell(RenderWalker $walker, array $row): string
  {
    $struct = [['tag'   => 'td',
                'attr'  => ['class' => $walker->getClasses(['cell', 'number'])],
                'inner' => ['tag'  => 'a',
                            'attr' => ['class' => 'link',
                                       'href'  => RoleDetailsPage::getUrl($row['cmp_id'], $row['rol_id'])],
                            'text' => $row['rol_id']]],
               ['tag'  => 'td',
                'attr' => ['class' => $walker->getClasses(['cell', 'text'])],
                'text' => $row['rol_name']]];

    return Html::generateNested($struct);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
