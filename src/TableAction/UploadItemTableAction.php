<?php
declare(strict_types=1);

namespace Plaisio\Core\TableAction;

use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;

/**
 * Parent class for table actions for uploading data.
 */
class UploadItemTableAction implements TableAction
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
  public function htmlTableAction(RenderWalker $walker): string
  {
    $struct = ['tag'  => 'a',
               'attr' => ['class' => $walker->getClasses('table-menu-icon', ['icons-medium', 'icons-medium-upload']),
                          'href'  => $this->url,
                          'title' => $this->title],
               'html' => null];

    return Html::htmlNested($struct);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
