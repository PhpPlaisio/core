<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction;

use Plaisio\Helper\Html;

/**
 * Parent class for table actions for updating certain items.
 */
class UpdateItemTableAction implements TableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The title of the icon of the table action.
   *
   * @var string
   */
  protected $title;

  /**
   * The URL of the table action.
   *
   * @var string
   */
  protected $url;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtml(): string
  {
    $ret = '<a';
    $ret .= Html::generateAttribute('href', $this->url);
    $ret .= '><img';
    $ret .= Html::generateAttribute('title', $this->title);
    $ret .= Html::generateAttribute('src', ICON_EDIT);
    $ret .= ' width="16" height="16" alt="update"/></a>';

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
