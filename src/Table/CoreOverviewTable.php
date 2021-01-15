<?php
declare(strict_types=1);

namespace Plaisio\Core\Table;

use Plaisio\Core\TableAction\RowCountTableAction;
use Plaisio\Core\TableAction\TableAction;
use Plaisio\Helper\Html;
use Plaisio\Table\OverviewTable;

/**
 * Extends \Plaisio\Table\OverviewTable with table actions.
 */
class CoreOverviewTable extends OverviewTable
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

    // Enable filtering by default.
    $this->filter = true;

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
   * Returns the outer HTML code of this table.
   *
   * @param array[] $rows The data shown in the table.
   *
   * @return string
   */
  public function getHtmlTable(array $rows): string
  {
    // Always add row count to the default table actions.
    $this->addTableAction('default', new RowCountTableAction(count($rows)));

    // Don't show filters if the number of rows is less or equal than 3.
    if (count($rows)<=3) $this->filter = false;

    // Generate the HTML code for the table.
    return parent::getHtmlTable($rows);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set the flag for enabling or disabling table actions. By default table actions are shown.
   *
   * @param bool $flag If empty table actions are not shown.
   */
  public function setShowTableActions(bool $flag): void
  {
    $this->showTableActions = $flag;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the inner HTML code of the header for this table.
   *
   * @return string
   */
  protected function getHtmlInnerHeader(): string
  {
    $ret    = '';

    if ($this->showTableActions)
    {
      $colspan = $this->getNumberOfColumns();

      $classes = $this->renderWalker->getClasses('table-menu');
      $ret     .= Html::generateTag('tr', ['class' => $classes]);
      $ret     .= Html::generateTag('td', ['class' => $classes, 'colspan' => $colspan]);

      $first_group = true;
      foreach ($this->tablesActionGroups as $group)
      {
        // Add a separator between groups of table actions.
        if (!$first_group)
        {
          $ret .= Html::generateElement('span', ['class' => ['noaction', 'icons-medium', 'icons-medium-separator']]);
        }

        // Generate HTML code for all table actions groups.
        /** @var $action Object */
        foreach ($group as $action)
        {
          $ret .= $action->getHtml($this->renderWalker);
        }

        if ($first_group) $first_group = false;
      }

      $ret .= '</td>';
      $ret .= '</tr>';
    }

    $ret .= parent::getHtmlInnerHeader();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
