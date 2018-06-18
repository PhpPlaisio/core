<?php

namespace SetBased\Abc\Core\Table;

use SetBased\Abc\Core\TableAction\TableAction;
use SetBased\Abc\Table\DetailTable;

/**
 * Extends \SetBased\Abc\Table\DetailTable with table actions.
 */
class CoreDetailTable extends DetailTable
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * If set to false table actions of this table are not shown.
   *
   * @var bool
   */
  protected $showTableActions = true;

  /**
   * Array with all table actions of this table.
   *
   * @var array
   */
  protected $tablesActionGroups = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   */
  public function __construct()
  {
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
  public function getHtmlHeader(): string
  {
    $ret = null;

    if ($this->showTableActions)
    {
      $ret .= '<tr class="table_actions">';
      $ret .= '<td colspan="2">';

      $first_group = true;
      foreach ($this->tablesActionGroups as $group)
      {
        // Add a separator between groups of table actions.
        if (!$first_group)
        {
          $ret .= '<img class="noaction" src="'.ICON_SEPARATOR.'" width="16" height="16" alt="|"/>';
        }

        // Generate HTML code for all table actions groups.
        /** @var TableAction $action */
        foreach ($group as $action)
        {
          $ret .= $action->getHtml();
        }

        if ($first_group) $first_group = false;
      }

      $ret .= '</td>';
      $ret .= '</tr>';
    }

    $ret .= parent::getHtmlHeader();

    return $ret;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the outer HTML code of this table.
   *
   * @return string
   */
  public function getHtmlTable(): string
  {
    $ret = '<div class="detail_table">';
    $ret .= parent::getHtmlTable();
    $ret .= '</div>';

    return $ret;
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
}

//----------------------------------------------------------------------------------------------------------------------
