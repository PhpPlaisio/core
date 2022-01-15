<?php
declare(strict_types=1);

namespace Plaisio\Core\Table;

use Plaisio\Core\TableAction\TableAction;
use Plaisio\Helper\Html;
use Plaisio\Table\DetailTable;

/**
 * Extends \Plaisio\Table\DetailTable with table actions.
 */
class CoreDetailTable extends DetailTable
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If set to false table actions of this table are not shown.
   *
   * @var bool
   */
  protected bool $showTableActions = true;

  /**
   * Array with all table actions of this table.
   *
   * @var array
   */
  protected array $tablesActionGroups = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
    parent::__construct();

    $this->tablesActionGroups['default'] = [];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a table action to the list of table actions of this table.
   *
   * @param string      $groupName   The group to witch the table action must be added.
   * @param TableAction $tableAction The table action.
   */
  public function addTableAction(string $groupName, TableAction $tableAction): void
  {
    $this->tablesActionGroups[$groupName][] = $tableAction;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the header for this table.
   *
   * @return string
   */
  public function htmlHeader(): string
  {
    $html = '';
    if ($this->showTableActions)
    {
      $inner = '';
      $first = true;
      foreach ($this->tablesActionGroups as $group)
      {
        // Add a separator between groups of table actions.
        if (!$first)
        {
          $inner .= Html::htmlNested(['tag'  => 'span',
                                      'attr' => ['class' => ['no-action', 'icons-medium', 'icons-medium-separator']],
                                      'html' => null]);
        }

        // Generate the HTML code for all table actions groups.
        /** @var TableAction $action */
        foreach ($group as $action)
        {
          $inner .= $action->htmlTableAction($this->renderWalker);
        }

        $first = false;
      }

      $struct = ['tag'   => 'tr',
                 'attr'  => ['class' => $this->renderWalker->getClasses('table-menu-row')],
                 'inner' => ['tag'  => 'td',
                             'attr' => ['class'   => $this->renderWalker->getClasses('table-menu-cell'),
                                        'colspan' => 2],
                             'html' => $inner]];

      $html .= Html::htmlNested($struct);
    }

    $html .= parent::htmlHeader();

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the flag for enabling or disabling table actions. By default, table actions are shown.
   *
   * @param bool $flag If empty table actions are not shown.
   */
  public function setShowTableActions(bool $flag): void
  {
    $this->showTableActions = $flag;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
