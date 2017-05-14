/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global define */
/*global require */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Core/InputTable',

  ['jquery', 'js-cookie'],

  function ($, Cookies) {
    "use strict";

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Object constructor.
     *
     * @constructor
     */
    function InputTable($table) {
      // The HTML table associated with the JavaScript object.
      this.$myTable = $table;

      //  Resize table cell width buttons.
      this.resizeButton();
    }

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Array with all registered input tables.
     *
     * @type {{InputTable}}
     */
    InputTable.ourTables = {};

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Resize table cell width buttons.
     */
    InputTable.prototype.resizeButton = function () {
      var $buttons_cells;
      var count_of_buttons;
      var width;
      var diff;

      $buttons_cells = this.$myTable.find('table.button').find('td');
      count_of_buttons = $buttons_cells.length;

      diff = 100 % count_of_buttons;
      width = Math.round((100 - diff) / count_of_buttons);

      if (diff) {
        $buttons_cells.each(function () {
          var $this = $(this);

          diff = diff - 1;
          if (diff > 0) {
            $this.css('width', (width + 1) + '%');
          } else {
            $this.css('width', width + '%');
          }
        });
      } else {
        $buttons_cells.css('width', width + '%');
      }
    };

    //------------------------------------------------------------------------------------------------------------------
    /**
     * Registers tables that matches a jQuery selector as a InputTable.
     *
     * @param selector  {string} The jQuery selector.
     */
    InputTable.registerTable = function (selector) {
      var class_name = 'SetBased/Abc/Core/InputTable';

      $(selector).each(function () {
        var $this1 = $(this);

        if ($this1.is('table')) {
          // Selector is a table.
          if (!$this1.hasClass('registered')) {
            require([class_name],
              function (Constructor) {
                InputTable.ourTables[InputTable.ourTables.length] = new Constructor($this1);
              });
            $this1.addClass('registered');
          }
        } else {
          // Selector is not a table. Find the table below the selector.
          $this1.find('table').first().each(function () {
            var $this2 = $(this);
            if (!$this2.hasClass('registered')) {
              require([class_name],
                function (Constructor) {
                  InputTable.ourTables[InputTable.ourTables.length] = new Constructor($this2);
                });
              $this2.addClass('registered');
            }
          });
        }
      });
    };

    //------------------------------------------------------------------------------------------------------------------
    InputTable.setCsrfValue = function () {
      $('input[type=hidden][name=ses_csrf_token]').val(Cookies.get('ses_csrf_token'));
    };

    //------------------------------------------------------------------------------------------------------------------
    return InputTable;

    //------------------------------------------------------------------------------------------------------------------
  }
);

//----------------------------------------------------------------------------------------------------------------------
