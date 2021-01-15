<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn\Company;

use Plaisio\Core\Page\Company\CompanyDetailsPage;
use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;
use Plaisio\Table\TableColumn\DualTableColumn;

/**
 * A dual table column with the ID and name of a company.
 */
class CompanyTableColumn extends DualTableColumn
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
    $url    = CompanyDetailsPage::getUrl($row['cmp_id']);
    $inner1 = Html::generateElement('a', ['href' => $url], $row['cmp_id']);

    $ret = Html::generateElement('td', ['class' => $walker->getClasses('number')], $inner1, true);
    $ret .= Html::generateElement('td', ['class' => $walker->getClasses('text')], $row['cmp_abbr']);

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
