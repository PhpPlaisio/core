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
   * The value of the alt attribute of the icon.
   *
   * @var
   */
  protected $altValue;

  /**
   * If set the will be prompted with an confirm message before the link is followed.
   *
   * @var string
   */
  protected $confirmMessage;

  /**
   * The URL of the icon.
   *
   * @var string
   */
  protected $iconUrl;

  /**
   * If set to true the icon is a download link (e.g. a PDF file).
   *
   * @var bool
   */
  protected $isDownloadLink = false;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct('none', null);

    $this->sortable = false;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtmlCell(array $row): string
  {
    $url = $this->getUrl($row);

    $ret = '<td>';

    if ($url!==null)
    {
      $ret .= Html::generateTag('a',
                                ['href'   => $url,
                                 'class'  => 'icon_action',
                                 'target' => ($this->isDownloadLink) ? '_blank' : null]);
    }

    $ret .= Html::generateVoidElement('img',
                                      ['src'                  => $this->iconUrl,
                                       'width'                => '12',
                                       'height'               => '12',
                                       'class'                => 'icon',
                                       'alt'                  => $this->altValue,
                                       'data-confirm-message' => $this->confirmMessage]);

    if ($url!==null) $ret .= '</a>';

    $ret .= '</td>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of the link of the icon for the row.
   *
   * @param array $row The data row.
   *
   * @return string|null
   */
  abstract public function getUrl(array $row): ?string;

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
