import * as $ from 'jquery';
import {FormPackage as Form} from 'Plaisio/Form/FormPackage';
import 'Plaisio/Form/LouverFormPackage';
import {Cast} from 'Plaisio/Helper/Cast';
import {Kernel} from 'Plaisio/Kernel/Kernel';
import {OverviewTablePackage as OverviewTable} from 'Plaisio/Table/OverviewTablePackage';

export class CoreCorePage
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Initializes the page decorator.
   */
  public static init()
  {
    this.initForms();
    this.initTables();
    this.initRowCount();

    Kernel.getInstance();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Init forms.
   */
  private static initForms(): void
  {
    Form.init();
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Install event handler for displaying the row count of am overview table.
   */
  private static initRowCount(): void
  {
    $('body').on(OverviewTable.FILTERING_ENDED, CoreCorePage.overviewTableRowCount);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Init tables.
   */
  private static initTables(): void
  {
    OverviewTable.init(window.matchMedia('only screen and (max-width: 40em)'));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Updates the row count of an overview table.
   *
   * @param event The OverviewTable.FILTERING_ENDED event.
   */
  private static overviewTableRowCount(event: JQuery.TriggeredEvent): void
  {
    const $table = $(event.target);

    const prefix = Cast.toOptString($table.attr('data-overview-table'));
    if (prefix !== null)
    {
      const all      = $table.children('tbody').children('tr').length;
      const visible  = $table.children('tbody').children('tr:visible').length;
      const selector = '.' + $.escapeSelector(prefix) + '-table-menu-row-count';

      if (all === visible)
      {
        $table.find(selector).text(all);
      }
      else
      {
        $table.find(selector).text(visible + '/' + all);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
// Plaisio\Console\TypeScript\Helper\MarkHelper::md5: 73409c9563c19a828449ce57529f6fee
