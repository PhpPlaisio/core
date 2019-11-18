/*jslint browser: true, single: true, maxlen: 120, eval: true, white: true */
/*global define */
/*global set_based_abc_inline_js*/

//----------------------------------------------------------------------------------------------------------------------
define(
  'Plaisio/Core/Page/CorePage',
  ['jquery',
    'Plaisio/Page/Page',
    'Plaisio/Core/InputTable',
    'Plaisio/Table/OverviewTablePackage',
    'Plaisio/Form/FormPackage'],

  function ($, Page, InputTable, OverviewTable, Form) {
    'use strict';
    //------------------------------------------------------------------------------------------------------------------
    $('form').submit(InputTable.setCsrfValue);
    Form.registerForm('form');
    InputTable.registerTable('form');

    Page.enableDatePicker();

    OverviewTable.registerTable('.overview-table');

    $('.icon_action').click(Page.showConfirmMessage);

    if (window.hasOwnProperty('set_based_abc_inline_js')) {
      eval(set_based_abc_inline_js);
    }

    //------------------------------------------------------------------------------------------------------------------
  }
);

//----------------------------------------------------------------------------------------------------------------------
