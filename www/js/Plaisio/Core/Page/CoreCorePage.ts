import '../../Form/LouverFormPackage';
import * as $ from 'jquery';
import {FormPackage as Form} from '../../Form/FormPackage';
import {OverviewTablePackage as OverviewTable} from '../../Table/OverviewTablePackage';

export class CoreCorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Initializes the page decorator.
   */
  public static init()
  {
    Form.registerForm('form');

    // Register all overview tables.
    const mq = window.matchMedia("only screen and (max-width: 40em)");
    OverviewTable.registerTable('table', mq);

    // Install event handler for displaying the row count of am overview table.
    $('body').on(OverviewTable.FILTERING_ENDED, CoreCorePage.overviewTableRowCount)

    if (window.hasOwnProperty('php_plaisio_inline_js'))
    {
      // @ts-ignore
      eval(php_plaisio_inline_js);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the row count of an overview table.
   *
   * @param event The OverviewTable.FILTERING_ENDED event.
   */
  public static overviewTableRowCount(event: JQuery.TriggeredEvent): void
  {
    const $table = $(event.target);

    const all     = $table.children('tbody').children('tr').length;
    const visible = $table.children('tbody').children('tr:visible').length;

    if (all === visible)
    {
      $table.find('span.row_count').text(all);
    }
    else
    {
      $table.find('span.row_count').text(visible + '/' + all);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
// Plaisio\Console\Helper\TypeScript\TypeScriptMarkHelper::md5: f80c9193310135bcf8d7226f8f311686
