<?php
declare(strict_types=1);

namespace Plaisio\Core\TableColumn;

use Plaisio\Helper\Html;
use Plaisio\Helper\RenderWalker;
use Plaisio\Table\TableColumn\UniTableColumn;

/**
 * Abstract table column for columns with icons.
 */
abstract class IconTableColumn extends UniTableColumn
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If set a confirmation message will be prompted before the link is followed.
   *
   * @var string|null
   */
  protected ?string $confirmMessage = null;

  /**
   * Whether the icon is a download link (e.g. a PDF file).
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
   * Returns the URL of the link of the icon for a row.
   *
   * @param array $row The data of the table row.
   *
   * @return string|null
   */
  abstract public function getUrl(array $row): ?string;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * @inheritdoc
   */
  public function htmlCell(RenderWalker $walker, array $row): string
  {
    $url = $this->getUrl($row);
    if ($url!==null)
    {
      $inner = ['tag'  => 'a',
                'attr' => ['href'                 => $url,
                           'class'                => $this->getClasses($row),
                           'target'               => ($this->isDownloadLink) ? '_blank' : null,
                           'data-confirm-message' => $this->confirmMessage],
                'html' => null];
    }
    else
    {
      $classes   = $this->getClasses($row);
      $classes[] = 'is-inactive-icon';
      $inner     = ['tag'  => 'span',
                    'attr' => ['class' => $classes],
                    'html' => null];
    }

    $struct = ['tag'   => 'td',
               'attr'  => ['class' => $walker->getClasses(['cell', 'icon'])],
               'inner' => $inner];

    return Html::htmlNested($struct);
  }

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
