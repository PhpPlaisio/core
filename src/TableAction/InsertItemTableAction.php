<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction;

use Plaisio\Helper\Html;

/**
 * Parent class for table actions for inserting certain items.
 */
class InsertItemTableAction implements TableAction
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The title of the icon of the table action.
   *
   * @var string
   */
  protected string $title;

  /**
   * The URL of the table action.
   *
   * @var string
   */
  protected string $url;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function getHtml(): string
  {
    return Html::generateElement('a', ['href'  => $this->url,
                                       'title' => $this->title,
                                       'class' => ['icons-medium', 'icons-medium-add']]);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
