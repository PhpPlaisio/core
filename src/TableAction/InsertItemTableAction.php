<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction;

use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;

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
  public function getHtml(RenderWalker $walker): string
  {
    $classes   = $walker->getClasses('table-menu-icon');
    $classes[] = 'icons-medium';
    $classes[] = 'icons-medium-add';

    return Html::generateElement('a', ['href' => $this->url, 'title' => $this->title, 'class' => $classes]);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
