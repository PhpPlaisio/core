/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Core/DetailTable',

  ['jquery'],

  function ($) {
    "use strict";
    //------------------------------------------------------------------------------------------------------------------
    /**
     * Object constructor.
     *
     * @constructor
     */
    function DetailTable($table) {
      // The HTML table associated with the JavaScript object.
      this.$myTable = $table;

      // Execute additional initializations (if any)
      this.initHook();
    }

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Array with all registered detail tables.
     *
     * @type {{DetailTable}}
     */
    DetailTable.ourTables = {};

    //------------------------------------------------------------------------------------------------------------------
    /**
     *  Install additional event handlers and other actions for the table.
     */
    DetailTable.prototype.initHook = function () {
      // Install event handlers.
      this.$myTable.find('.table_export_csv').click({table: this}, DetailTable.convertTableEventHandler);
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Event handler wrappers.
     * @param event
     */
    DetailTable.convertTableEventHandler = function (event) {
      event.data.table.convertTable(event);
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Action for convert table to csv format.
     * @returns {boolean}
     */
    DetailTable.prototype.convertTable = function () {
      var snippet;
      var data = [];
      var row;
      var cell;

      // Add table data to data.
      this.$myTable.find('tbody tr').each(function () {
        row = [];
        $(this).find('td,th').each(function () {
          cell = $(this);
          if (cell.hasClass('email_body')) {
            row[row.length] = cell.find('object').contents().find('body').text();
          } else {
            row[row.length] = cell.text();
          }
        });
        data[data.length] = row;
      });

      // Create HTML snippet with a temporary form.
      snippet = '<form id="_mmm_tmp_id_1" action="/page/convert_table/" method="post">';
      snippet += '<input id="_mmm_tmp_id_2" type="hidden" name="data" />';
      snippet += '<input id="_mmm_tmp_id_3" type="hidden" name="options" />';
      snippet += '<input id="_mmm_tmp_id_4" type="hidden" name="returl" />';
      snippet += '</form>';

      // Append HTML snippet to the body of this document.
      $(snippet).appendTo('body');

      // Set the value of the form control 'data' to the HTML code of the table to be converted.
      $('#_mmm_tmp_id_2').val(JSON.stringify(data));
      $('#_mmm_tmp_id_3').val('csv');
      $('#_mmm_tmp_id_4').val($(location).attr('href'));

      // Submit the form and remove the form immediate afterwards.
      $('#_mmm_tmp_id_1').submit().remove();

      return false;
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Registers tables that matches a jQuery selector as a DetailTable.
     *
     * @param selector  {string} The jQuery selector.
     */
    DetailTable.registerTable = function (selector) {
      var class_name = 'DetailTable';

      $(selector).each(function () {
        var $this1 = $(this);

        if ($this1.is('table')) {
          // Selector is a table.
          if (!$this1.hasClass('registered')) {
            DetailTable.ourTables[DetailTable.ourTables.length] = new window[class_name]($this1);
            $this1.addClass('registered');
          }
        } else {
          // Selector is not a table. Find the table below the selector.
          $this1.find('table').first().each(function () {
            var $this2 = $(this);
            if (!$this2.hasClass('registered')) {
              DetailTable.ourTables[DetailTable.ourTables.length] = new window[class_name]($this2);
              $this2.addClass('registered');
            }
          });
        }
      });
    };

    //------------------------------------------------------------------------------------------------------------------
    return DetailTable;

    //------------------------------------------------------------------------------------------------------------------
  }
);

//----------------------------------------------------------------------------------------------------------------------
