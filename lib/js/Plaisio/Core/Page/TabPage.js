/*jslint browser: true, single: true, maxlen: 120, eval: true, white: true */
/*global define */
/*global php_plaisio_inline_js*/

//----------------------------------------------------------------------------------------------------------------------
define(
  'Plaisio/Core/Page/TabPage',
  ['jquery',
    'Plaisio/Page/Page',
    'Plaisio/Core/InputTable',
    'SetBased/Abc/Table/OverviewTablePackage',
    'SetBased/Abc/Form/FormPackage'],

  function ($, Page, InputTable, OverviewTable, Form) {
    'use strict';
    //------------------------------------------------------------------------------------------------------------------
    $('form').submit(InputTable.setCsrfValue);
    Form.registerForm('form');
    InputTable.registerTable('form');

    Page.enableDatePicker();

    OverviewTable.registerTable('.overview-table');

    $('.icon_action').click(Page.showConfirmMessage);

    if (window.hasOwnProperty('php_plaisio_inline_js')) {
      eval(php_plaisio_inline_js);
    }

    //------------------------------------------------------------------------------------------------------------------
  }
);

//----------------------------------------------------------------------------------------------------------------------
