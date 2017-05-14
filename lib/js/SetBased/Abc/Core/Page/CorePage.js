/*jslint browser: true, single: true, maxlen: 120, eval: true, white: true */
/*global define */
/*global set_based_abc_inline_js*/

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Core/Page/CorePage',
  ['jquery',
    'SetBased/Abc/Page/Page',
    'SetBased/Abc/Core/InputTable',
    'SetBased/Abc/Table/OverviewTablePackage',
    'SetBased/Abc/Form/FormPackage'],

  function ($, Page, InputTable, OverviewTable, Form) {
    'use strict';
    //------------------------------------------------------------------------------------------------------------------
    $('form').submit(InputTable.setCsrfValue);
    Form.registerForm('form');
    InputTable.registerTable('form');

    Page.enableDatePicker();

    OverviewTable.registerTable('.overview_table');

    $('.icon_action').click(Page.showConfirmMessage);

    if (window.hasOwnProperty('set_based_abc_inline_js')) {
      eval(set_based_abc_inline_js);
    }

    //------------------------------------------------------------------------------------------------------------------
  }
);

//----------------------------------------------------------------------------------------------------------------------
