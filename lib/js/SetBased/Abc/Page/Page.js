/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global confirm */
/*global define */

//----------------------------------------------------------------------------------------------------------------------
define(
  'SetBased/Abc/Page/Page',

  ['jquery', 'jquery-ui'],

  function ($) {
    'use strict';
    //------------------------------------------------------------------------------------------------------------------
    function w3cValidate(url) {
      $(document).ready(function () {
        $('#w3c_validate').load(url,
          function () {
            $('#w3c_validate').click(function () {
              window.open($(this).attr('href'));
            });
          });
      });
    }

    //------------------------------------------------------------------------------------------------------------------
    function enableDatePicker() {
      // @todo make calender opties configurable..
      // See http://stackoverflow.com/questions/1073410/today-button-in-jquery-datepicker-doesnt-work

      $(function () {
        var d = new Date();
        var today = d.getDate() + '-' + (d.getMonth() + 1) + '-' + d.getFullYear();
        $('.datepicker').datepicker({
          changeMonth: true,
          changeYear: true,
          showButtonPanel: true,
          showOtherMonths: true,
          selectOtherMonths: true,
          showAnim: '',
          yearRange: '1900:c+10',
          altFormat: 'yy-mm-dd',
          dateFormat: 'dd-mm-yy',
          defaultDate: today,
          sticky: false,
          showOn: 'button',
          buttonImage: '/images/icons/16x16/calendar.png',
          buttonImageOnly: true
        });

        $.datepicker._gotoToday = function (id) {
          var target = $(id);
          var inst = this._getInst(target[0]);
          if (this._get(inst, 'gotoCurrent') && inst.currentDay) {
            inst.selectedDay = inst.currentDay;
            inst.drawMonth = inst.selectedMonth = inst.currentMonth;
            inst.drawYear = inst.selectedYear = inst.currentYear;
          } else {
            var date = new Date();
            inst.selectedDay = date.getDate();
            inst.drawMonth = inst.selectedMonth = date.getMonth();
            inst.drawYear = inst.selectedYear = date.getFullYear();
            // the below two lines are new
            this._setDateDatepicker(target, date);
            this._selectDate(id, this._getDateDatepicker(target));
          }

          this._notifyChange(inst);
          this._adjustDate(target);
        };

        $('.today').click(function () {
          if (!$(this).val()) {
            $(this).val($('.datepicker').datepicker('option', 'defaultDate'));
          }
        });
      });

      $.datepicker.setDefaults($.datepicker.regional.nl);
    }

    //------------------------------------------------------------------------------------------------------------------
    function showConfirmMessage(event) {
      var $message;

      // Attribute data-confirm-message is set ask for confirmation before following the link.
      $message = $(event.target).data('confirm-message');
      if ($message) {
        return confirm($message);
      }

      // Attribute data-confirm-message is not set following the link immediately.
      return true;
    }

    //------------------------------------------------------------------------------------------------------------------
    return {
      enableDatePicker: enableDatePicker,
      showConfirmMessage: showConfirmMessage,
      w3cValidate: w3cValidate
    };
  }

  //--------------------------------------------------------------------------------------------------------------------
);

//----------------------------------------------------------------------------------------------------------------------
