<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn;

use Plaisio\Helper\Html;
use Plaisio\Table\TableColumn\TableColumn;

/**
 * Abstract table column for columns with icons.
 */
abstract class IconTableColumn extends TableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If set the will be prompted with an confirm message before the link is followed.
   *
   * @var string|null
   */
  protected ?string $confirmMessage = null;

  /**
   * If set to true the icon is a download link (e.g. a PDF file).
   *
   * @var bool
   */
  protected bool $isDownloadLink = false;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct('none', null);

    $this->isSortable = false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtmlCell(array $row): string
  {
    $url     = $this->getUrl($row);
    $classes = $this->getClasses($row);

    $ret = '<td>';

    if ($url!==null)
    {
      $classes[] = 'icon_action';
      $ret       .= Html::generateElement('a',
                                          ['href'                 => $url,
                                           'class'                => $classes,
                                           'target'               => ($this->isDownloadLink) ? '_blank' : null,
                                           'data-confirm-message' => $this->confirmMessage]);
    }
    else
    {
      $classes[] = 'inactive';
      $ret       .= Html::generateElement('span', ['class' => $classes]);
    }

    $ret .= '</td>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of the link of the icon for a row.
   *
   * @param array $row The data of the table row.
   *
   * @return string|null
   */
  abstract public function getUrl(array $row): ?string;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the classes for the table cell content.
   *
   * @param array $row The data of the table row.
   *
   * @return array
   */
  protected function getClasses(array $row): array
  {
    unset($row);

    return [];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
