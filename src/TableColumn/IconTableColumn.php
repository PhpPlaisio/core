<?php

namespace SetBased\Abc\Core\TableColumn;

use SetBased\Abc\Helper\Html;
use SetBased\Abc\Table\TableColumn\TableColumn;

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
    parent::__construct('none');

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
    if ($url)
    {
      $ret .= '<a';
      $ret .= Html::generateAttribute('href', $url);
      $ret .= ' class="icon_action"';
      if ($this->isDownloadLink) $ret .= ' target="_blank"';
      $ret .= '>';
    }

    $ret .= '<img';
    $ret .= Html::generateAttribute('src', $this->iconUrl);
    $ret .= ' width="12"';
    $ret .= ' height="12"';
    $ret .= ' class="icon"';

    if ($this->confirmMessage) $ret .= Html::generateAttribute('data-confirm-message', $this->confirmMessage);

    $ret .= Html::generateAttribute('alt', $this->altValue);
    $ret .= '/>';

    if ($url) $ret .= '</a>';

    $ret .= '</td>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the URL of the link of the icon for the row.
   *
   * @param array $row The data row.
   *
   * @return string
   */
  abstract public function getUrl(array $row): string;

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
