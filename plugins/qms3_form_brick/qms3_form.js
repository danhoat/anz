(function ($$1) {
  'use strict';

  function _interopDefaultLegacy (e) { return e && typeof e === 'object' && 'default' in e ? e : { 'default': e }; }

  var $__default = /*#__PURE__*/_interopDefaultLegacy($$1);

  function _typeof(obj) {
    "@babel/helpers - typeof";

    if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") {
      _typeof = function (obj) {
        return typeof obj;
      };
    } else {
      _typeof = function (obj) {
        return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
      };
    }

    return _typeof(obj);
  }

  function _slicedToArray(arr, i) {
    return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest();
  }

  function _arrayWithHoles(arr) {
    if (Array.isArray(arr)) return arr;
  }

  function _iterableToArrayLimit(arr, i) {
    if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return;
    var _arr = [];
    var _n = true;
    var _d = false;
    var _e = undefined;

    try {
      for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) {
        _arr.push(_s.value);

        if (i && _arr.length === i) break;
      }
    } catch (err) {
      _d = true;
      _e = err;
    } finally {
      try {
        if (!_n && _i["return"] != null) _i["return"]();
      } finally {
        if (_d) throw _e;
      }
    }

    return _arr;
  }

  function _unsupportedIterableToArray(o, minLen) {
    if (!o) return;
    if (typeof o === "string") return _arrayLikeToArray(o, minLen);
    var n = Object.prototype.toString.call(o).slice(8, -1);
    if (n === "Object" && o.constructor) n = o.constructor.name;
    if (n === "Map" || n === "Set") return Array.from(o);
    if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen);
  }

  function _arrayLikeToArray(arr, len) {
    if (len == null || len > arr.length) len = arr.length;

    for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i];

    return arr2;
  }

  function _nonIterableRest() {
    throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
  }

  /* eslint-disable max-len, camelcase */

  /*!
   * jQuery UI Datepicker 1.13.2
   * http://jqueryui.com
   *
   * Copyright jQuery Foundation and other contributors
   * Released under the MIT license.
   * http://jquery.org/license
   */
  //>>label: Datepicker
  //>>group: Widgets
  //>>description: Displays a calendar from an input or inline for selecting dates.
  //>>docs: http://api.jqueryui.com/datepicker/
  //>>demos: http://jqueryui.com/datepicker/
  //>>css.structure: ../../themes/base/core.css
  //>>css.structure: ../../themes/base/datepicker.css
  //>>css.theme: ../../themes/base/theme.css
  (function (factory) {

    if (typeof define === "function" && define.amd) {
      // AMD. Register as an anonymous module.
      define(["jquery", "../version", "../keycode"], factory);
    } else {
      // Browser globals
      factory(jQuery);
    }
  })(function ($) {

    $.extend($.ui, {
      datepicker: {
        version: "1.13.2"
      }
    });
    var datepicker_instActive;

    function datepicker_getZindex(elem) {
      var position, value;

      while (elem.length && elem[0] !== document) {
        // Ignore z-index if position is set to a value where z-index is ignored by the browser
        // This makes behavior of this function consistent across browsers
        // WebKit always returns auto if the element is positioned
        position = elem.css("position");

        if (position === "absolute" || position === "relative" || position === "fixed") {
          // IE returns 0 when zIndex is not specified
          // other browsers return a string
          // we ignore the case of nested elements with an explicit value of 0
          // <div style="z-index: -10;"><div style="z-index: 0;"></div></div>
          value = parseInt(elem.css("zIndex"), 10);

          if (!isNaN(value) && value !== 0) {
            return value;
          }
        }

        elem = elem.parent();
      }

      return 0;
    }
    /* Date picker manager.
       Use the singleton instance of this class, $.datepicker, to interact with the date picker.
       Settings for (groups of) date pickers are maintained in an instance object,
       allowing multiple different settings on the same page. */


    function Datepicker() {
      this._curInst = null; // The current instance in use

      this._keyEvent = false; // If the last event was a key event

      this._disabledInputs = []; // List of date picker inputs that have been disabled

      this._datepickerShowing = false; // True if the popup picker is showing , false if not

      this._inDialog = false; // True if showing within a "dialog", false if not

      this._mainDivId = "ui-datepicker-div"; // The ID of the main datepicker division

      this._inlineClass = "ui-datepicker-inline"; // The name of the inline marker class

      this._appendClass = "ui-datepicker-append"; // The name of the append marker class

      this._triggerClass = "ui-datepicker-trigger"; // The name of the trigger marker class

      this._dialogClass = "ui-datepicker-dialog"; // The name of the dialog marker class

      this._disableClass = "ui-datepicker-disabled"; // The name of the disabled covering marker class

      this._unselectableClass = "ui-datepicker-unselectable"; // The name of the unselectable cell marker class

      this._currentClass = "ui-datepicker-current-day"; // The name of the current day marker class

      this._dayOverClass = "ui-datepicker-days-cell-over"; // The name of the day hover marker class

      this.regional = []; // Available regional settings, indexed by language code

      this.regional[""] = {
        // Default regional settings
        closeText: "Done",
        // Display text for close link
        prevText: "Prev",
        // Display text for previous month link
        nextText: "Next",
        // Display text for next month link
        currentText: "Today",
        // Display text for current month link
        monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
        // Names of months for drop-down and formatting
        monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        // For formatting
        dayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        // For formatting
        dayNamesShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
        // For formatting
        dayNamesMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
        // Column headings for days starting at Sunday
        weekHeader: "Wk",
        // Column header for week of the year
        dateFormat: "mm/dd/yy",
        // See format options on parseDate
        firstDay: 0,
        // The first day of the week, Sun = 0, Mon = 1, ...
        isRTL: false,
        // True if right-to-left language, false if left-to-right
        showMonthAfterYear: false,
        // True if the year select precedes month, false for month then year
        yearSuffix: "",
        // Additional text to append to the year in the month headers,
        selectMonthLabel: "Select month",
        // Invisible label for month selector
        selectYearLabel: "Select year" // Invisible label for year selector

      };
      this._defaults = {
        // Global defaults for all the date picker instances
        showOn: "focus",
        // "focus" for popup on focus,
        // "button" for trigger button, or "both" for either
        showAnim: "fadeIn",
        // Name of jQuery animation for popup
        showOptions: {},
        // Options for enhanced animations
        defaultDate: null,
        // Used when field is blank: actual date,
        // +/-number for offset from today, null for today
        appendText: "",
        // Display text following the input box, e.g. showing the format
        buttonText: "...",
        // Text for trigger button
        buttonImage: "",
        // URL for trigger button image
        buttonImageOnly: false,
        // True if the image appears alone, false if it appears on a button
        hideIfNoPrevNext: false,
        // True to hide next/previous month links
        // if not applicable, false to just disable them
        navigationAsDateFormat: false,
        // True if date formatting applied to prev/today/next links
        gotoCurrent: false,
        // True if today link goes back to current selection instead
        changeMonth: false,
        // True if month can be selected directly, false if only prev/next
        changeYear: false,
        // True if year can be selected directly, false if only prev/next
        yearRange: "c-10:c+10",
        // Range of years to display in drop-down,
        // either relative to today's year (-nn:+nn), relative to currently displayed year
        // (c-nn:c+nn), absolute (nnnn:nnnn), or a combination of the above (nnnn:-n)
        showOtherMonths: false,
        // True to show dates in other months, false to leave blank
        selectOtherMonths: false,
        // True to allow selection of dates in other months, false for unselectable
        showWeek: false,
        // True to show week of the year, false to not show it
        calculateWeek: this.iso8601Week,
        // How to calculate the week of the year,
        // takes a Date and returns the number of the week for it
        shortYearCutoff: "+10",
        // Short year values < this are in the current century,
        // > this are in the previous century,
        // string value starting with "+" for current year + value
        minDate: null,
        // The earliest selectable date, or null for no limit
        maxDate: null,
        // The latest selectable date, or null for no limit
        duration: "fast",
        // Duration of display/closure
        beforeShowDay: null,
        // Function that takes a date and returns an array with
        // [0] = true if selectable, false if not, [1] = custom CSS class name(s) or "",
        // [2] = cell title (optional), e.g. $.datepicker.noWeekends
        beforeShow: null,
        // Function that takes an input field and
        // returns a set of custom settings for the date picker
        onSelect: null,
        // Define a callback function when a date is selected
        onChangeMonthYear: null,
        // Define a callback function when the month or year is changed
        onClose: null,
        // Define a callback function when the datepicker is closed
        onUpdateDatepicker: null,
        // Define a callback function when the datepicker is updated
        numberOfMonths: 1,
        // Number of months to show at a time
        showCurrentAtPos: 0,
        // The position in multipe months at which to show the current month (starting at 0)
        stepMonths: 1,
        // Number of months to step back/forward
        stepBigMonths: 12,
        // Number of months to step back/forward for the big links
        altField: "",
        // Selector for an alternate field to store selected dates into
        altFormat: "",
        // The date format to use for the alternate field
        constrainInput: true,
        // The input is constrained by the current date format
        showButtonPanel: false,
        // True to show button panel, false to not show it
        autoSize: false,
        // True to size the input for the date format, false to leave as is
        disabled: false // The initial disabled state

      };
      $.extend(this._defaults, this.regional[""]);
      this.regional.en = $.extend(true, {}, this.regional[""]);
      this.regional["en-US"] = $.extend(true, {}, this.regional.en);
      this.dpDiv = datepicker_bindHover($("<div id='" + this._mainDivId + "' class='ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>"));
    }

    $.extend(Datepicker.prototype, {
      /* Class name added to elements to indicate already configured with a date picker. */
      markerClassName: "hasDatepicker",
      //Keep track of the maximum number of rows displayed (see #7043)
      maxRows: 4,
      // TODO rename to "widget" when switching to widget factory
      _widgetDatepicker: function _widgetDatepicker() {
        return this.dpDiv;
      },

      /* Override the default settings for all instances of the date picker.
       * @param  settings  object - the new settings to use as defaults (anonymous object)
       * @return the manager object
       */
      setDefaults: function setDefaults(settings) {
        datepicker_extendRemove(this._defaults, settings || {});
        return this;
      },

      /* Attach the date picker to a jQuery selection.
       * @param  target	element - the target input field or division or span
       * @param  settings  object - the new settings to use for this date picker instance (anonymous)
       */
      _attachDatepicker: function _attachDatepicker(target, settings) {
        var nodeName, inline, inst;
        nodeName = target.nodeName.toLowerCase();
        inline = nodeName === "div" || nodeName === "span";

        if (!target.id) {
          this.uuid += 1;
          target.id = "dp" + this.uuid;
        }

        inst = this._newInst($(target), inline);
        inst.settings = $.extend({}, settings || {});

        if (nodeName === "input") {
          this._connectDatepicker(target, inst);
        } else if (inline) {
          this._inlineDatepicker(target, inst);
        }
      },

      /* Create a new instance object. */
      _newInst: function _newInst(target, inline) {
        var id = target[0].id.replace(/([^A-Za-z0-9_\-])/g, "\\\\$1"); // escape jQuery meta chars

        return {
          id: id,
          input: target,
          // associated target
          selectedDay: 0,
          selectedMonth: 0,
          selectedYear: 0,
          // current selection
          drawMonth: 0,
          drawYear: 0,
          // month being drawn
          inline: inline,
          // is datepicker inline or not
          dpDiv: !inline ? this.dpDiv : // presentation div
          datepicker_bindHover($("<div class='" + this._inlineClass + " ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all'></div>"))
        };
      },

      /* Attach the date picker to an input field. */
      _connectDatepicker: function _connectDatepicker(target, inst) {
        var input = $(target);
        inst.append = $([]);
        inst.trigger = $([]);

        if (input.hasClass(this.markerClassName)) {
          return;
        }

        this._attachments(input, inst);

        input.addClass(this.markerClassName).on("keydown", this._doKeyDown).on("keypress", this._doKeyPress).on("keyup", this._doKeyUp);

        this._autoSize(inst);

        $.data(target, "datepicker", inst); //If disabled option is true, disable the datepicker once it has been attached to the input (see ticket #5665)

        if (inst.settings.disabled) {
          this._disableDatepicker(target);
        }
      },

      /* Make attachments based on settings. */
      _attachments: function _attachments(input, inst) {
        var showOn,
            buttonText,
            buttonImage,
            appendText = this._get(inst, "appendText"),
            isRTL = this._get(inst, "isRTL");

        if (inst.append) {
          inst.append.remove();
        }

        if (appendText) {
          inst.append = $("<span>").addClass(this._appendClass).text(appendText);
          input[isRTL ? "before" : "after"](inst.append);
        }

        input.off("focus", this._showDatepicker);

        if (inst.trigger) {
          inst.trigger.remove();
        }

        showOn = this._get(inst, "showOn");

        if (showOn === "focus" || showOn === "both") {
          // pop-up date picker when in the marked field
          input.on("focus", this._showDatepicker);
        }

        if (showOn === "button" || showOn === "both") {
          // pop-up date picker when button clicked
          buttonText = this._get(inst, "buttonText");
          buttonImage = this._get(inst, "buttonImage");

          if (this._get(inst, "buttonImageOnly")) {
            inst.trigger = $("<img>").addClass(this._triggerClass).attr({
              src: buttonImage,
              alt: buttonText,
              title: buttonText
            });
          } else {
            inst.trigger = $("<button type='button'>").addClass(this._triggerClass);

            if (buttonImage) {
              inst.trigger.html($("<img>").attr({
                src: buttonImage,
                alt: buttonText,
                title: buttonText
              }));
            } else {
              inst.trigger.text(buttonText);
            }
          }

          input[isRTL ? "before" : "after"](inst.trigger);
          inst.trigger.on("click", function () {
            if ($.datepicker._datepickerShowing && $.datepicker._lastInput === input[0]) {
              $.datepicker._hideDatepicker();
            } else if ($.datepicker._datepickerShowing && $.datepicker._lastInput !== input[0]) {
              $.datepicker._hideDatepicker();

              $.datepicker._showDatepicker(input[0]);
            } else {
              $.datepicker._showDatepicker(input[0]);
            }

            return false;
          });
        }
      },

      /* Apply the maximum length for the date format. */
      _autoSize: function _autoSize(inst) {
        if (this._get(inst, "autoSize") && !inst.inline) {
          var findMax,
              max,
              maxI,
              i,
              date = new Date(2009, 12 - 1, 20),
              // Ensure double digits
          dateFormat = this._get(inst, "dateFormat");

          if (dateFormat.match(/[DM]/)) {
            findMax = function findMax(names) {
              max = 0;
              maxI = 0;

              for (i = 0; i < names.length; i++) {
                if (names[i].length > max) {
                  max = names[i].length;
                  maxI = i;
                }
              }

              return maxI;
            };

            date.setMonth(findMax(this._get(inst, dateFormat.match(/MM/) ? "monthNames" : "monthNamesShort")));
            date.setDate(findMax(this._get(inst, dateFormat.match(/DD/) ? "dayNames" : "dayNamesShort")) + 20 - date.getDay());
          }

          inst.input.attr("size", this._formatDate(inst, date).length);
        }
      },

      /* Attach an inline date picker to a div. */
      _inlineDatepicker: function _inlineDatepicker(target, inst) {
        var divSpan = $(target);

        if (divSpan.hasClass(this.markerClassName)) {
          return;
        }

        divSpan.addClass(this.markerClassName).append(inst.dpDiv);
        $.data(target, "datepicker", inst);

        this._setDate(inst, this._getDefaultDate(inst), true);

        this._updateDatepicker(inst);

        this._updateAlternate(inst); //If disabled option is true, disable the datepicker before showing it (see ticket #5665)


        if (inst.settings.disabled) {
          this._disableDatepicker(target);
        } // Set display:block in place of inst.dpDiv.show() which won't work on disconnected elements
        // http://bugs.jqueryui.com/ticket/7552 - A Datepicker created on a detached div has zero height


        inst.dpDiv.css("display", "block");
      },

      /* Pop-up the date picker in a "dialog" box.
       * @param  input element - ignored
       * @param  date	string or Date - the initial date to display
       * @param  onSelect  function - the function to call when a date is selected
       * @param  settings  object - update the dialog date picker instance's settings (anonymous object)
       * @param  pos int[2] - coordinates for the dialog's position within the screen or
       *					event - with x/y coordinates or
       *					leave empty for default (screen centre)
       * @return the manager object
       */
      _dialogDatepicker: function _dialogDatepicker(input, date, onSelect, settings, pos) {
        var id,
            browserWidth,
            browserHeight,
            scrollX,
            scrollY,
            inst = this._dialogInst; // internal instance

        if (!inst) {
          this.uuid += 1;
          id = "dp" + this.uuid;
          this._dialogInput = $("<input type='text' id='" + id + "' style='position: absolute; top: -100px; width: 0px;'/>");

          this._dialogInput.on("keydown", this._doKeyDown);

          $("body").append(this._dialogInput);
          inst = this._dialogInst = this._newInst(this._dialogInput, false);
          inst.settings = {};
          $.data(this._dialogInput[0], "datepicker", inst);
        }

        datepicker_extendRemove(inst.settings, settings || {});
        date = date && date.constructor === Date ? this._formatDate(inst, date) : date;

        this._dialogInput.val(date);

        this._pos = pos ? pos.length ? pos : [pos.pageX, pos.pageY] : null;

        if (!this._pos) {
          browserWidth = document.documentElement.clientWidth;
          browserHeight = document.documentElement.clientHeight;
          scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
          scrollY = document.documentElement.scrollTop || document.body.scrollTop;
          this._pos = // should use actual width/height below
          [browserWidth / 2 - 100 + scrollX, browserHeight / 2 - 150 + scrollY];
        } // Move input on screen for focus, but hidden behind dialog


        this._dialogInput.css("left", this._pos[0] + 20 + "px").css("top", this._pos[1] + "px");

        inst.settings.onSelect = onSelect;
        this._inDialog = true;
        this.dpDiv.addClass(this._dialogClass);

        this._showDatepicker(this._dialogInput[0]);

        if ($.blockUI) {
          $.blockUI(this.dpDiv);
        }

        $.data(this._dialogInput[0], "datepicker", inst);
        return this;
      },

      /* Detach a datepicker from its control.
       * @param  target	element - the target input field or division or span
       */
      _destroyDatepicker: function _destroyDatepicker(target) {
        var nodeName,
            $target = $(target),
            inst = $.data(target, "datepicker");

        if (!$target.hasClass(this.markerClassName)) {
          return;
        }

        nodeName = target.nodeName.toLowerCase();
        $.removeData(target, "datepicker");

        if (nodeName === "input") {
          inst.append.remove();
          inst.trigger.remove();
          $target.removeClass(this.markerClassName).off("focus", this._showDatepicker).off("keydown", this._doKeyDown).off("keypress", this._doKeyPress).off("keyup", this._doKeyUp);
        } else if (nodeName === "div" || nodeName === "span") {
          $target.removeClass(this.markerClassName).empty();
        }

        if (datepicker_instActive === inst) {
          datepicker_instActive = null;
          this._curInst = null;
        }
      },

      /* Enable the date picker to a jQuery selection.
       * @param  target	element - the target input field or division or span
       */
      _enableDatepicker: function _enableDatepicker(target) {
        var nodeName,
            inline,
            $target = $(target),
            inst = $.data(target, "datepicker");

        if (!$target.hasClass(this.markerClassName)) {
          return;
        }

        nodeName = target.nodeName.toLowerCase();

        if (nodeName === "input") {
          target.disabled = false;
          inst.trigger.filter("button").each(function () {
            this.disabled = false;
          }).end().filter("img").css({
            opacity: "1.0",
            cursor: ""
          });
        } else if (nodeName === "div" || nodeName === "span") {
          inline = $target.children("." + this._inlineClass);
          inline.children().removeClass("ui-state-disabled");
          inline.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled", false);
        }

        this._disabledInputs = $.map(this._disabledInputs, // Delete entry
        function (value) {
          return value === target ? null : value;
        });
      },

      /* Disable the date picker to a jQuery selection.
       * @param  target	element - the target input field or division or span
       */
      _disableDatepicker: function _disableDatepicker(target) {
        var nodeName,
            inline,
            $target = $(target),
            inst = $.data(target, "datepicker");

        if (!$target.hasClass(this.markerClassName)) {
          return;
        }

        nodeName = target.nodeName.toLowerCase();

        if (nodeName === "input") {
          target.disabled = true;
          inst.trigger.filter("button").each(function () {
            this.disabled = true;
          }).end().filter("img").css({
            opacity: "0.5",
            cursor: "default"
          });
        } else if (nodeName === "div" || nodeName === "span") {
          inline = $target.children("." + this._inlineClass);
          inline.children().addClass("ui-state-disabled");
          inline.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled", true);
        }

        this._disabledInputs = $.map(this._disabledInputs, // Delete entry
        function (value) {
          return value === target ? null : value;
        });
        this._disabledInputs[this._disabledInputs.length] = target;
      },

      /* Is the first field in a jQuery collection disabled as a datepicker?
       * @param  target	element - the target input field or division or span
       * @return boolean - true if disabled, false if enabled
       */
      _isDisabledDatepicker: function _isDisabledDatepicker(target) {
        if (!target) {
          return false;
        }

        for (var i = 0; i < this._disabledInputs.length; i++) {
          if (this._disabledInputs[i] === target) {
            return true;
          }
        }

        return false;
      },

      /* Retrieve the instance data for the target control.
       * @param  target  element - the target input field or division or span
       * @return  object - the associated instance data
       * @throws  error if a jQuery problem getting data
       */
      _getInst: function _getInst(target) {
        try {
          return $.data(target, "datepicker");
        } catch (err) {
          throw "Missing instance data for this datepicker";
        }
      },

      /* Update or retrieve the settings for a date picker attached to an input field or division.
       * @param  target  element - the target input field or division or span
       * @param  name	object - the new settings to update or
       *				string - the name of the setting to change or retrieve,
       *				when retrieving also "all" for all instance settings or
       *				"defaults" for all global defaults
       * @param  value   any - the new value for the setting
       *				(omit if above is an object or to retrieve a value)
       */
      _optionDatepicker: function _optionDatepicker(target, name, value) {
        var settings,
            date,
            minDate,
            maxDate,
            inst = this._getInst(target);

        if (arguments.length === 2 && typeof name === "string") {
          return name === "defaults" ? $.extend({}, $.datepicker._defaults) : inst ? name === "all" ? $.extend({}, inst.settings) : this._get(inst, name) : null;
        }

        settings = name || {};

        if (typeof name === "string") {
          settings = {};
          settings[name] = value;
        }

        if (inst) {
          if (this._curInst === inst) {
            this._hideDatepicker();
          }

          date = this._getDateDatepicker(target, true);
          minDate = this._getMinMaxDate(inst, "min");
          maxDate = this._getMinMaxDate(inst, "max");
          datepicker_extendRemove(inst.settings, settings); // reformat the old minDate/maxDate values if dateFormat changes and a new minDate/maxDate isn't provided

          if (minDate !== null && settings.dateFormat !== undefined && settings.minDate === undefined) {
            inst.settings.minDate = this._formatDate(inst, minDate);
          }

          if (maxDate !== null && settings.dateFormat !== undefined && settings.maxDate === undefined) {
            inst.settings.maxDate = this._formatDate(inst, maxDate);
          }

          if ("disabled" in settings) {
            if (settings.disabled) {
              this._disableDatepicker(target);
            } else {
              this._enableDatepicker(target);
            }
          }

          this._attachments($(target), inst);

          this._autoSize(inst);

          this._setDate(inst, date);

          this._updateAlternate(inst);

          this._updateDatepicker(inst);
        }
      },
      // Change method deprecated
      _changeDatepicker: function _changeDatepicker(target, name, value) {
        this._optionDatepicker(target, name, value);
      },

      /* Redraw the date picker attached to an input field or division.
       * @param  target  element - the target input field or division or span
       */
      _refreshDatepicker: function _refreshDatepicker(target) {
        var inst = this._getInst(target);

        if (inst) {
          this._updateDatepicker(inst);
        }
      },

      /* Set the dates for a jQuery selection.
       * @param  target element - the target input field or division or span
       * @param  date	Date - the new date
       */
      _setDateDatepicker: function _setDateDatepicker(target, date) {
        var inst = this._getInst(target);

        if (inst) {
          this._setDate(inst, date);

          this._updateDatepicker(inst);

          this._updateAlternate(inst);
        }
      },

      /* Get the date(s) for the first entry in a jQuery selection.
       * @param  target element - the target input field or division or span
       * @param  noDefault boolean - true if no default date is to be used
       * @return Date - the current date
       */
      _getDateDatepicker: function _getDateDatepicker(target, noDefault) {
        var inst = this._getInst(target);

        if (inst && !inst.inline) {
          this._setDateFromField(inst, noDefault);
        }

        return inst ? this._getDate(inst) : null;
      },

      /* Handle keystrokes. */
      _doKeyDown: function _doKeyDown(event) {
        var onSelect,
            dateStr,
            sel,
            inst = $.datepicker._getInst(event.target),
            handled = true,
            isRTL = inst.dpDiv.is(".ui-datepicker-rtl");

        inst._keyEvent = true;

        if ($.datepicker._datepickerShowing) {
          switch (event.keyCode) {
            case 9:
              $.datepicker._hideDatepicker();

              handled = false;
              break;
            // hide on tab out

            case 13:
              sel = $("td." + $.datepicker._dayOverClass + ":not(." + $.datepicker._currentClass + ")", inst.dpDiv);

              if (sel[0]) {
                $.datepicker._selectDay(event.target, inst.selectedMonth, inst.selectedYear, sel[0]);
              }

              onSelect = $.datepicker._get(inst, "onSelect");

              if (onSelect) {
                dateStr = $.datepicker._formatDate(inst); // Trigger custom callback

                onSelect.apply(inst.input ? inst.input[0] : null, [dateStr, inst]);
              } else {
                $.datepicker._hideDatepicker();
              }

              return false;
            // don't submit the form

            case 27:
              $.datepicker._hideDatepicker();

              break;
            // hide on escape

            case 33:
              $.datepicker._adjustDate(event.target, event.ctrlKey ? -$.datepicker._get(inst, "stepBigMonths") : -$.datepicker._get(inst, "stepMonths"), "M");

              break;
            // previous month/year on page up/+ ctrl

            case 34:
              $.datepicker._adjustDate(event.target, event.ctrlKey ? +$.datepicker._get(inst, "stepBigMonths") : +$.datepicker._get(inst, "stepMonths"), "M");

              break;
            // next month/year on page down/+ ctrl

            case 35:
              if (event.ctrlKey || event.metaKey) {
                $.datepicker._clearDate(event.target);
              }

              handled = event.ctrlKey || event.metaKey;
              break;
            // clear on ctrl or command +end

            case 36:
              if (event.ctrlKey || event.metaKey) {
                $.datepicker._gotoToday(event.target);
              }

              handled = event.ctrlKey || event.metaKey;
              break;
            // current on ctrl or command +home

            case 37:
              if (event.ctrlKey || event.metaKey) {
                $.datepicker._adjustDate(event.target, isRTL ? +1 : -1, "D");
              }

              handled = event.ctrlKey || event.metaKey; // -1 day on ctrl or command +left

              if (event.originalEvent.altKey) {
                $.datepicker._adjustDate(event.target, event.ctrlKey ? -$.datepicker._get(inst, "stepBigMonths") : -$.datepicker._get(inst, "stepMonths"), "M");
              } // next month/year on alt +left on Mac


              break;

            case 38:
              if (event.ctrlKey || event.metaKey) {
                $.datepicker._adjustDate(event.target, -7, "D");
              }

              handled = event.ctrlKey || event.metaKey;
              break;
            // -1 week on ctrl or command +up

            case 39:
              if (event.ctrlKey || event.metaKey) {
                $.datepicker._adjustDate(event.target, isRTL ? -1 : +1, "D");
              }

              handled = event.ctrlKey || event.metaKey; // +1 day on ctrl or command +right

              if (event.originalEvent.altKey) {
                $.datepicker._adjustDate(event.target, event.ctrlKey ? +$.datepicker._get(inst, "stepBigMonths") : +$.datepicker._get(inst, "stepMonths"), "M");
              } // next month/year on alt +right


              break;

            case 40:
              if (event.ctrlKey || event.metaKey) {
                $.datepicker._adjustDate(event.target, +7, "D");
              }

              handled = event.ctrlKey || event.metaKey;
              break;
            // +1 week on ctrl or command +down

            default:
              handled = false;
          }
        } else if (event.keyCode === 36 && event.ctrlKey) {
          // display the date picker on ctrl+home
          $.datepicker._showDatepicker(this);
        } else {
          handled = false;
        }

        if (handled) {
          event.preventDefault();
          event.stopPropagation();
        }
      },

      /* Filter entered characters - based on date format. */
      _doKeyPress: function _doKeyPress(event) {
        var chars,
            chr,
            inst = $.datepicker._getInst(event.target);

        if ($.datepicker._get(inst, "constrainInput")) {
          chars = $.datepicker._possibleChars($.datepicker._get(inst, "dateFormat"));
          chr = String.fromCharCode(event.charCode == null ? event.keyCode : event.charCode);
          return event.ctrlKey || event.metaKey || chr < " " || !chars || chars.indexOf(chr) > -1;
        }
      },

      /* Synchronise manual entry and field/alternate field. */
      _doKeyUp: function _doKeyUp(event) {
        var date,
            inst = $.datepicker._getInst(event.target);

        if (inst.input.val() !== inst.lastVal) {
          try {
            date = $.datepicker.parseDate($.datepicker._get(inst, "dateFormat"), inst.input ? inst.input.val() : null, $.datepicker._getFormatConfig(inst));

            if (date) {
              // only if valid
              $.datepicker._setDateFromField(inst);

              $.datepicker._updateAlternate(inst);

              $.datepicker._updateDatepicker(inst);
            }
          } catch (err) {}
        }

        return true;
      },

      /* Pop-up the date picker for a given input field.
       * If false returned from beforeShow event handler do not show.
       * @param  input  element - the input field attached to the date picker or
       *					event - if triggered by focus
       */
      _showDatepicker: function _showDatepicker(input) {
        input = input.target || input;

        if (input.nodeName.toLowerCase() !== "input") {
          // find from button/image trigger
          input = $("input", input.parentNode)[0];
        }

        if ($.datepicker._isDisabledDatepicker(input) || $.datepicker._lastInput === input) {
          // already here
          return;
        }

        var inst, beforeShow, beforeShowSettings, isFixed, offset, showAnim, duration;
        inst = $.datepicker._getInst(input);

        if ($.datepicker._curInst && $.datepicker._curInst !== inst) {
          $.datepicker._curInst.dpDiv.stop(true, true);

          if (inst && $.datepicker._datepickerShowing) {
            $.datepicker._hideDatepicker($.datepicker._curInst.input[0]);
          }
        }

        beforeShow = $.datepicker._get(inst, "beforeShow");
        beforeShowSettings = beforeShow ? beforeShow.apply(input, [input, inst]) : {};

        if (beforeShowSettings === false) {
          return;
        }

        datepicker_extendRemove(inst.settings, beforeShowSettings);
        inst.lastVal = null;
        $.datepicker._lastInput = input;

        $.datepicker._setDateFromField(inst);

        if ($.datepicker._inDialog) {
          // hide cursor
          input.value = "";
        }

        if (!$.datepicker._pos) {
          // position below input
          $.datepicker._pos = $.datepicker._findPos(input);
          $.datepicker._pos[1] += input.offsetHeight; // add the height
        }

        isFixed = false;
        $(input).parents().each(function () {
          isFixed |= $(this).css("position") === "fixed";
          return !isFixed;
        });
        offset = {
          left: $.datepicker._pos[0],
          top: $.datepicker._pos[1]
        };
        $.datepicker._pos = null; //to avoid flashes on Firefox

        inst.dpDiv.empty(); // determine sizing offscreen

        inst.dpDiv.css({
          position: "absolute",
          display: "block",
          top: "-1000px"
        });

        $.datepicker._updateDatepicker(inst); // fix width for dynamic number of date pickers
        // and adjust position before showing


        offset = $.datepicker._checkOffset(inst, offset, isFixed);
        inst.dpDiv.css({
          position: $.datepicker._inDialog && $.blockUI ? "static" : isFixed ? "fixed" : "absolute",
          display: "none",
          left: offset.left + "px",
          top: offset.top + "px"
        });

        if (!inst.inline) {
          showAnim = $.datepicker._get(inst, "showAnim");
          duration = $.datepicker._get(inst, "duration");
          inst.dpDiv.css("z-index", datepicker_getZindex($(input)) + 1);
          $.datepicker._datepickerShowing = true;

          if ($.effects && $.effects.effect[showAnim]) {
            inst.dpDiv.show(showAnim, $.datepicker._get(inst, "showOptions"), duration);
          } else {
            inst.dpDiv[showAnim || "show"](showAnim ? duration : null);
          }

          if ($.datepicker._shouldFocusInput(inst)) {
            inst.input.trigger("focus");
          }

          $.datepicker._curInst = inst;
        }
      },

      /* Generate the date picker content. */
      _updateDatepicker: function _updateDatepicker(inst) {
        this.maxRows = 4; //Reset the max number of rows being displayed (see #7043)

        datepicker_instActive = inst; // for delegate hover events

        inst.dpDiv.empty().append(this._generateHTML(inst));

        this._attachHandlers(inst);

        var origyearshtml,
            numMonths = this._getNumberOfMonths(inst),
            cols = numMonths[1],
            width = 17,
            activeCell = inst.dpDiv.find("." + this._dayOverClass + " a"),
            onUpdateDatepicker = $.datepicker._get(inst, "onUpdateDatepicker");

        if (activeCell.length > 0) {
          datepicker_handleMouseover.apply(activeCell.get(0));
        }

        inst.dpDiv.removeClass("ui-datepicker-multi-2 ui-datepicker-multi-3 ui-datepicker-multi-4").width("");

        if (cols > 1) {
          inst.dpDiv.addClass("ui-datepicker-multi-" + cols).css("width", width * cols + "em");
        }

        inst.dpDiv[(numMonths[0] !== 1 || numMonths[1] !== 1 ? "add" : "remove") + "Class"]("ui-datepicker-multi");
        inst.dpDiv[(this._get(inst, "isRTL") ? "add" : "remove") + "Class"]("ui-datepicker-rtl");

        if (inst === $.datepicker._curInst && $.datepicker._datepickerShowing && $.datepicker._shouldFocusInput(inst)) {
          inst.input.trigger("focus");
        } // Deffered render of the years select (to avoid flashes on Firefox)


        if (inst.yearshtml) {
          origyearshtml = inst.yearshtml;
          setTimeout(function () {
            //assure that inst.yearshtml didn't change.
            if (origyearshtml === inst.yearshtml && inst.yearshtml) {
              inst.dpDiv.find("select.ui-datepicker-year").first().replaceWith(inst.yearshtml);
            }

            origyearshtml = inst.yearshtml = null;
          }, 0);
        }

        if (onUpdateDatepicker) {
          onUpdateDatepicker.apply(inst.input ? inst.input[0] : null, [inst]);
        }
      },
      // #6694 - don't focus the input if it's already focused
      // this breaks the change event in IE
      // Support: IE and jQuery <1.9
      _shouldFocusInput: function _shouldFocusInput(inst) {
        return inst.input && inst.input.is(":visible") && !inst.input.is(":disabled") && !inst.input.is(":focus");
      },

      /* Check positioning to remain on screen. */
      _checkOffset: function _checkOffset(inst, offset, isFixed) {
        var dpWidth = inst.dpDiv.outerWidth(),
            dpHeight = inst.dpDiv.outerHeight(),
            inputWidth = inst.input ? inst.input.outerWidth() : 0,
            inputHeight = inst.input ? inst.input.outerHeight() : 0,
            viewWidth = document.documentElement.clientWidth + (isFixed ? 0 : $(document).scrollLeft()),
            viewHeight = document.documentElement.clientHeight + (isFixed ? 0 : $(document).scrollTop());
        offset.left -= this._get(inst, "isRTL") ? dpWidth - inputWidth : 0;
        offset.left -= isFixed && offset.left === inst.input.offset().left ? $(document).scrollLeft() : 0;
        offset.top -= isFixed && offset.top === inst.input.offset().top + inputHeight ? $(document).scrollTop() : 0; // Now check if datepicker is showing outside window viewport - move to a better place if so.

        offset.left -= Math.min(offset.left, offset.left + dpWidth > viewWidth && viewWidth > dpWidth ? Math.abs(offset.left + dpWidth - viewWidth) : 0);
        offset.top -= Math.min(offset.top, offset.top + dpHeight > viewHeight && viewHeight > dpHeight ? Math.abs(dpHeight + inputHeight) : 0);
        return offset;
      },

      /* Find an object's position on the screen. */
      _findPos: function _findPos(obj) {
        var position,
            inst = this._getInst(obj),
            isRTL = this._get(inst, "isRTL");

        while (obj && (obj.type === "hidden" || obj.nodeType !== 1 || $.expr.pseudos.hidden(obj))) {
          obj = obj[isRTL ? "previousSibling" : "nextSibling"];
        }

        position = $(obj).offset();
        return [position.left, position.top];
      },

      /* Hide the date picker from view.
       * @param  input  element - the input field attached to the date picker
       */
      _hideDatepicker: function _hideDatepicker(input) {
        var showAnim,
            duration,
            postProcess,
            onClose,
            inst = this._curInst;

        if (!inst || input && inst !== $.data(input, "datepicker")) {
          return;
        }

        if (this._datepickerShowing) {
          showAnim = this._get(inst, "showAnim");
          duration = this._get(inst, "duration");

          postProcess = function postProcess() {
            $.datepicker._tidyDialog(inst);
          }; // DEPRECATED: after BC for 1.8.x $.effects[ showAnim ] is not needed


          if ($.effects && ($.effects.effect[showAnim] || $.effects[showAnim])) {
            inst.dpDiv.hide(showAnim, $.datepicker._get(inst, "showOptions"), duration, postProcess);
          } else {
            inst.dpDiv[showAnim === "slideDown" ? "slideUp" : showAnim === "fadeIn" ? "fadeOut" : "hide"](showAnim ? duration : null, postProcess);
          }

          if (!showAnim) {
            postProcess();
          }

          this._datepickerShowing = false;
          onClose = this._get(inst, "onClose");

          if (onClose) {
            onClose.apply(inst.input ? inst.input[0] : null, [inst.input ? inst.input.val() : "", inst]);
          }

          this._lastInput = null;

          if (this._inDialog) {
            this._dialogInput.css({
              position: "absolute",
              left: "0",
              top: "-100px"
            });

            if ($.blockUI) {
              $.unblockUI();
              $("body").append(this.dpDiv);
            }
          }

          this._inDialog = false;
        }
      },

      /* Tidy up after a dialog display. */
      _tidyDialog: function _tidyDialog(inst) {
        inst.dpDiv.removeClass(this._dialogClass).off(".ui-datepicker-calendar");
      },

      /* Close date picker if clicked elsewhere. */
      _checkExternalClick: function _checkExternalClick(event) {
        if (!$.datepicker._curInst) {
          return;
        }

        var $target = $(event.target),
            inst = $.datepicker._getInst($target[0]);

        if ($target[0].id !== $.datepicker._mainDivId && $target.parents("#" + $.datepicker._mainDivId).length === 0 && !$target.hasClass($.datepicker.markerClassName) && !$target.closest("." + $.datepicker._triggerClass).length && $.datepicker._datepickerShowing && !($.datepicker._inDialog && $.blockUI) || $target.hasClass($.datepicker.markerClassName) && $.datepicker._curInst !== inst) {
          $.datepicker._hideDatepicker();
        }
      },

      /* Adjust one of the date sub-fields. */
      _adjustDate: function _adjustDate(id, offset, period) {
        var target = $(id),
            inst = this._getInst(target[0]);

        if (this._isDisabledDatepicker(target[0])) {
          return;
        }

        this._adjustInstDate(inst, offset, period);

        this._updateDatepicker(inst);
      },

      /* Action for current link. */
      _gotoToday: function _gotoToday(id) {
        var date,
            target = $(id),
            inst = this._getInst(target[0]);

        if (this._get(inst, "gotoCurrent") && inst.currentDay) {
          inst.selectedDay = inst.currentDay;
          inst.drawMonth = inst.selectedMonth = inst.currentMonth;
          inst.drawYear = inst.selectedYear = inst.currentYear;
        } else {
          date = new Date();
          inst.selectedDay = date.getDate();
          inst.drawMonth = inst.selectedMonth = date.getMonth();
          inst.drawYear = inst.selectedYear = date.getFullYear();
        }

        this._notifyChange(inst);

        this._adjustDate(target);
      },

      /* Action for selecting a new month/year. */
      _selectMonthYear: function _selectMonthYear(id, select, period) {
        var target = $(id),
            inst = this._getInst(target[0]);

        inst["selected" + (period === "M" ? "Month" : "Year")] = inst["draw" + (period === "M" ? "Month" : "Year")] = parseInt(select.options[select.selectedIndex].value, 10);

        this._notifyChange(inst);

        this._adjustDate(target);
      },

      /* Action for selecting a day. */
      _selectDay: function _selectDay(id, month, year, td) {
        var inst,
            target = $(id);

        if ($(td).hasClass(this._unselectableClass) || this._isDisabledDatepicker(target[0])) {
          return;
        }

        inst = this._getInst(target[0]);
        inst.selectedDay = inst.currentDay = parseInt($("a", td).attr("data-date"));
        inst.selectedMonth = inst.currentMonth = month;
        inst.selectedYear = inst.currentYear = year;

        this._selectDate(id, this._formatDate(inst, inst.currentDay, inst.currentMonth, inst.currentYear));
      },

      /* Erase the input field and hide the date picker. */
      _clearDate: function _clearDate(id) {
        var target = $(id);

        this._selectDate(target, "");
      },

      /* Update the input field with the selected date. */
      _selectDate: function _selectDate(id, dateStr) {
        var onSelect,
            target = $(id),
            inst = this._getInst(target[0]);

        dateStr = dateStr != null ? dateStr : this._formatDate(inst);

        if (inst.input) {
          inst.input.val(dateStr);
        }

        this._updateAlternate(inst);

        onSelect = this._get(inst, "onSelect");

        if (onSelect) {
          onSelect.apply(inst.input ? inst.input[0] : null, [dateStr, inst]); // trigger custom callback
        } else if (inst.input) {
          inst.input.trigger("change"); // fire the change event
        }

        if (inst.inline) {
          this._updateDatepicker(inst);
        } else {
          this._hideDatepicker();

          this._lastInput = inst.input[0];

          if (_typeof(inst.input[0]) !== "object") {
            inst.input.trigger("focus"); // restore focus
          }

          this._lastInput = null;
        }
      },

      /* Update any alternate field to synchronise with the main field. */
      _updateAlternate: function _updateAlternate(inst) {
        var altFormat,
            date,
            dateStr,
            altField = this._get(inst, "altField");

        if (altField) {
          // update alternate field too
          altFormat = this._get(inst, "altFormat") || this._get(inst, "dateFormat");
          date = this._getDate(inst);
          dateStr = this.formatDate(altFormat, date, this._getFormatConfig(inst));
          $(document).find(altField).val(dateStr);
        }
      },

      /* Set as beforeShowDay function to prevent selection of weekends.
       * @param  date  Date - the date to customise
       * @return [boolean, string] - is this date selectable?, what is its CSS class?
       */
      noWeekends: function noWeekends(date) {
        var day = date.getDay();
        return [day > 0 && day < 6, ""];
      },

      /* Set as calculateWeek to determine the week of the year based on the ISO 8601 definition.
       * @param  date  Date - the date to get the week for
       * @return  number - the number of the week within the year that contains this date
       */
      iso8601Week: function iso8601Week(date) {
        var time,
            checkDate = new Date(date.getTime()); // Find Thursday of this week starting on Monday

        checkDate.setDate(checkDate.getDate() + 4 - (checkDate.getDay() || 7));
        time = checkDate.getTime();
        checkDate.setMonth(0); // Compare with Jan 1

        checkDate.setDate(1);
        return Math.floor(Math.round((time - checkDate) / 86400000) / 7) + 1;
      },

      /* Parse a string value into a date object.
       * See formatDate below for the possible formats.
       *
       * @param  format string - the expected format of the date
       * @param  value string - the date in the above format
       * @param  settings Object - attributes include:
       *					shortYearCutoff  number - the cutoff year for determining the century (optional)
       *					dayNamesShort	string[7] - abbreviated names of the days from Sunday (optional)
       *					dayNames		string[7] - names of the days from Sunday (optional)
       *					monthNamesShort string[12] - abbreviated names of the months (optional)
       *					monthNames		string[12] - names of the months (optional)
       * @return  Date - the extracted date value or null if value is blank
       */
      parseDate: function parseDate(format, value, settings) {
        if (format == null || value == null) {
          throw "Invalid arguments";
        }

        value = _typeof(value) === "object" ? value.toString() : value + "";

        if (value === "") {
          return null;
        }

        var iFormat,
            dim,
            extra,
            iValue = 0,
            shortYearCutoffTemp = (settings ? settings.shortYearCutoff : null) || this._defaults.shortYearCutoff,
            shortYearCutoff = typeof shortYearCutoffTemp !== "string" ? shortYearCutoffTemp : new Date().getFullYear() % 100 + parseInt(shortYearCutoffTemp, 10),
            dayNamesShort = (settings ? settings.dayNamesShort : null) || this._defaults.dayNamesShort,
            dayNames = (settings ? settings.dayNames : null) || this._defaults.dayNames,
            monthNamesShort = (settings ? settings.monthNamesShort : null) || this._defaults.monthNamesShort,
            monthNames = (settings ? settings.monthNames : null) || this._defaults.monthNames,
            year = -1,
            month = -1,
            day = -1,
            doy = -1,
            literal = false,
            date,
            // Check whether a format character is doubled
        lookAhead = function lookAhead(match) {
          var matches = iFormat + 1 < format.length && format.charAt(iFormat + 1) === match;

          if (matches) {
            iFormat++;
          }

          return matches;
        },
            // Extract a number from the string value
        getNumber = function getNumber(match) {
          var isDoubled = lookAhead(match),
              size = match === "@" ? 14 : match === "!" ? 20 : match === "y" && isDoubled ? 4 : match === "o" ? 3 : 2,
              minSize = match === "y" ? size : 1,
              digits = new RegExp("^\\d{" + minSize + "," + size + "}"),
              num = value.substring(iValue).match(digits);

          if (!num) {
            throw "Missing number at position " + iValue;
          }

          iValue += num[0].length;
          return parseInt(num[0], 10);
        },
            // Extract a name from the string value and convert to an index
        getName = function getName(match, shortNames, longNames) {
          var index = -1,
              names = $.map(lookAhead(match) ? longNames : shortNames, function (v, k) {
            return [[k, v]];
          }).sort(function (a, b) {
            return -(a[1].length - b[1].length);
          });
          $.each(names, function (i, pair) {
            var name = pair[1];

            if (value.substr(iValue, name.length).toLowerCase() === name.toLowerCase()) {
              index = pair[0];
              iValue += name.length;
              return false;
            }
          });

          if (index !== -1) {
            return index + 1;
          } else {
            throw "Unknown name at position " + iValue;
          }
        },
            // Confirm that a literal character matches the string value
        checkLiteral = function checkLiteral() {
          if (value.charAt(iValue) !== format.charAt(iFormat)) {
            throw "Unexpected literal at position " + iValue;
          }

          iValue++;
        };

        for (iFormat = 0; iFormat < format.length; iFormat++) {
          if (literal) {
            if (format.charAt(iFormat) === "'" && !lookAhead("'")) {
              literal = false;
            } else {
              checkLiteral();
            }
          } else {
            switch (format.charAt(iFormat)) {
              case "d":
                day = getNumber("d");
                break;

              case "D":
                getName("D", dayNamesShort, dayNames);
                break;

              case "o":
                doy = getNumber("o");
                break;

              case "m":
                month = getNumber("m");
                break;

              case "M":
                month = getName("M", monthNamesShort, monthNames);
                break;

              case "y":
                year = getNumber("y");
                break;

              case "@":
                date = new Date(getNumber("@"));
                year = date.getFullYear();
                month = date.getMonth() + 1;
                day = date.getDate();
                break;

              case "!":
                date = new Date((getNumber("!") - this._ticksTo1970) / 10000);
                year = date.getFullYear();
                month = date.getMonth() + 1;
                day = date.getDate();
                break;

              case "'":
                if (lookAhead("'")) {
                  checkLiteral();
                } else {
                  literal = true;
                }

                break;

              default:
                checkLiteral();
            }
          }
        }

        if (iValue < value.length) {
          extra = value.substr(iValue);

          if (!/^\s+/.test(extra)) {
            throw "Extra/unparsed characters found in date: " + extra;
          }
        }

        if (year === -1) {
          year = new Date().getFullYear();
        } else if (year < 100) {
          year += new Date().getFullYear() - new Date().getFullYear() % 100 + (year <= shortYearCutoff ? 0 : -100);
        }

        if (doy > -1) {
          month = 1;
          day = doy;

          do {
            dim = this._getDaysInMonth(year, month - 1);

            if (day <= dim) {
              break;
            }

            month++;
            day -= dim;
          } while (true);
        }

        date = this._daylightSavingAdjust(new Date(year, month - 1, day));

        if (date.getFullYear() !== year || date.getMonth() + 1 !== month || date.getDate() !== day) {
          throw "Invalid date"; // E.g. 31/02/00
        }

        return date;
      },

      /* Standard date formats. */
      ATOM: "yy-mm-dd",
      // RFC 3339 (ISO 8601)
      COOKIE: "D, dd M yy",
      ISO_8601: "yy-mm-dd",
      RFC_822: "D, d M y",
      RFC_850: "DD, dd-M-y",
      RFC_1036: "D, d M y",
      RFC_1123: "D, d M yy",
      RFC_2822: "D, d M yy",
      RSS: "D, d M y",
      // RFC 822
      TICKS: "!",
      TIMESTAMP: "@",
      W3C: "yy-mm-dd",
      // ISO 8601
      _ticksTo1970: ((1970 - 1) * 365 + Math.floor(1970 / 4) - Math.floor(1970 / 100) + Math.floor(1970 / 400)) * 24 * 60 * 60 * 10000000,

      /* Format a date object into a string value.
       * The format can be combinations of the following:
       * d  - day of month (no leading zero)
       * dd - day of month (two digit)
       * o  - day of year (no leading zeros)
       * oo - day of year (three digit)
       * D  - day name short
       * DD - day name long
       * m  - month of year (no leading zero)
       * mm - month of year (two digit)
       * M  - month name short
       * MM - month name long
       * y  - year (two digit)
       * yy - year (four digit)
       * @ - Unix timestamp (ms since 01/01/1970)
       * ! - Windows ticks (100ns since 01/01/0001)
       * "..." - literal text
       * '' - single quote
       *
       * @param  format string - the desired format of the date
       * @param  date Date - the date value to format
       * @param  settings Object - attributes include:
       *					dayNamesShort	string[7] - abbreviated names of the days from Sunday (optional)
       *					dayNames		string[7] - names of the days from Sunday (optional)
       *					monthNamesShort string[12] - abbreviated names of the months (optional)
       *					monthNames		string[12] - names of the months (optional)
       * @return  string - the date in the above format
       */
      formatDate: function formatDate(format, date, settings) {
        if (!date) {
          return "";
        }

        var iFormat,
            dayNamesShort = (settings ? settings.dayNamesShort : null) || this._defaults.dayNamesShort,
            dayNames = (settings ? settings.dayNames : null) || this._defaults.dayNames,
            monthNamesShort = (settings ? settings.monthNamesShort : null) || this._defaults.monthNamesShort,
            monthNames = (settings ? settings.monthNames : null) || this._defaults.monthNames,
            // Check whether a format character is doubled
        lookAhead = function lookAhead(match) {
          var matches = iFormat + 1 < format.length && format.charAt(iFormat + 1) === match;

          if (matches) {
            iFormat++;
          }

          return matches;
        },
            // Format a number, with leading zero if necessary
        formatNumber = function formatNumber(match, value, len) {
          var num = "" + value;

          if (lookAhead(match)) {
            while (num.length < len) {
              num = "0" + num;
            }
          }

          return num;
        },
            // Format a name, short or long as requested
        formatName = function formatName(match, value, shortNames, longNames) {
          return lookAhead(match) ? longNames[value] : shortNames[value];
        },
            output = "",
            literal = false;

        if (date) {
          for (iFormat = 0; iFormat < format.length; iFormat++) {
            if (literal) {
              if (format.charAt(iFormat) === "'" && !lookAhead("'")) {
                literal = false;
              } else {
                output += format.charAt(iFormat);
              }
            } else {
              switch (format.charAt(iFormat)) {
                case "d":
                  output += formatNumber("d", date.getDate(), 2);
                  break;

                case "D":
                  output += formatName("D", date.getDay(), dayNamesShort, dayNames);
                  break;

                case "o":
                  output += formatNumber("o", Math.round((new Date(date.getFullYear(), date.getMonth(), date.getDate()).getTime() - new Date(date.getFullYear(), 0, 0).getTime()) / 86400000), 3);
                  break;

                case "m":
                  output += formatNumber("m", date.getMonth() + 1, 2);
                  break;

                case "M":
                  output += formatName("M", date.getMonth(), monthNamesShort, monthNames);
                  break;

                case "y":
                  output += lookAhead("y") ? date.getFullYear() : (date.getFullYear() % 100 < 10 ? "0" : "") + date.getFullYear() % 100;
                  break;

                case "@":
                  output += date.getTime();
                  break;

                case "!":
                  output += date.getTime() * 10000 + this._ticksTo1970;
                  break;

                case "'":
                  if (lookAhead("'")) {
                    output += "'";
                  } else {
                    literal = true;
                  }

                  break;

                default:
                  output += format.charAt(iFormat);
              }
            }
          }
        }

        return output;
      },

      /* Extract all possible characters from the date format. */
      _possibleChars: function _possibleChars(format) {
        var iFormat,
            chars = "",
            literal = false,
            // Check whether a format character is doubled
        lookAhead = function lookAhead(match) {
          var matches = iFormat + 1 < format.length && format.charAt(iFormat + 1) === match;

          if (matches) {
            iFormat++;
          }

          return matches;
        };

        for (iFormat = 0; iFormat < format.length; iFormat++) {
          if (literal) {
            if (format.charAt(iFormat) === "'" && !lookAhead("'")) {
              literal = false;
            } else {
              chars += format.charAt(iFormat);
            }
          } else {
            switch (format.charAt(iFormat)) {
              case "d":
              case "m":
              case "y":
              case "@":
                chars += "0123456789";
                break;

              case "D":
              case "M":
                return null;
              // Accept anything

              case "'":
                if (lookAhead("'")) {
                  chars += "'";
                } else {
                  literal = true;
                }

                break;

              default:
                chars += format.charAt(iFormat);
            }
          }
        }

        return chars;
      },

      /* Get a setting value, defaulting if necessary. */
      _get: function _get(inst, name) {
        return inst.settings[name] !== undefined ? inst.settings[name] : this._defaults[name];
      },

      /* Parse existing date and initialise date picker. */
      _setDateFromField: function _setDateFromField(inst, noDefault) {
        if (inst.input.val() === inst.lastVal) {
          return;
        }

        var dateFormat = this._get(inst, "dateFormat"),
            dates = inst.lastVal = inst.input ? inst.input.val() : null,
            defaultDate = this._getDefaultDate(inst),
            date = defaultDate,
            settings = this._getFormatConfig(inst);

        try {
          date = this.parseDate(dateFormat, dates, settings) || defaultDate;
        } catch (event) {
          dates = noDefault ? "" : dates;
        }

        inst.selectedDay = date.getDate();
        inst.drawMonth = inst.selectedMonth = date.getMonth();
        inst.drawYear = inst.selectedYear = date.getFullYear();
        inst.currentDay = dates ? date.getDate() : 0;
        inst.currentMonth = dates ? date.getMonth() : 0;
        inst.currentYear = dates ? date.getFullYear() : 0;

        this._adjustInstDate(inst);
      },

      /* Retrieve the default date shown on opening. */
      _getDefaultDate: function _getDefaultDate(inst) {
        return this._restrictMinMax(inst, this._determineDate(inst, this._get(inst, "defaultDate"), new Date()));
      },

      /* A date may be specified as an exact value or a relative one. */
      _determineDate: function _determineDate(inst, date, defaultDate) {
        var offsetNumeric = function offsetNumeric(offset) {
          var date = new Date();
          date.setDate(date.getDate() + offset);
          return date;
        },
            offsetString = function offsetString(offset) {
          try {
            return $.datepicker.parseDate($.datepicker._get(inst, "dateFormat"), offset, $.datepicker._getFormatConfig(inst));
          } catch (e) {// Ignore
          }

          var date = (offset.toLowerCase().match(/^c/) ? $.datepicker._getDate(inst) : null) || new Date(),
              year = date.getFullYear(),
              month = date.getMonth(),
              day = date.getDate(),
              pattern = /([+\-]?[0-9]+)\s*(d|D|w|W|m|M|y|Y)?/g,
              matches = pattern.exec(offset);

          while (matches) {
            switch (matches[2] || "d") {
              case "d":
              case "D":
                day += parseInt(matches[1], 10);
                break;

              case "w":
              case "W":
                day += parseInt(matches[1], 10) * 7;
                break;

              case "m":
              case "M":
                month += parseInt(matches[1], 10);
                day = Math.min(day, $.datepicker._getDaysInMonth(year, month));
                break;

              case "y":
              case "Y":
                year += parseInt(matches[1], 10);
                day = Math.min(day, $.datepicker._getDaysInMonth(year, month));
                break;
            }

            matches = pattern.exec(offset);
          }

          return new Date(year, month, day);
        },
            newDate = date == null || date === "" ? defaultDate : typeof date === "string" ? offsetString(date) : typeof date === "number" ? isNaN(date) ? defaultDate : offsetNumeric(date) : new Date(date.getTime());

        newDate = newDate && newDate.toString() === "Invalid Date" ? defaultDate : newDate;

        if (newDate) {
          newDate.setHours(0);
          newDate.setMinutes(0);
          newDate.setSeconds(0);
          newDate.setMilliseconds(0);
        }

        return this._daylightSavingAdjust(newDate);
      },

      /* Handle switch to/from daylight saving.
       * Hours may be non-zero on daylight saving cut-over:
       * > 12 when midnight changeover, but then cannot generate
       * midnight datetime, so jump to 1AM, otherwise reset.
       * @param  date  (Date) the date to check
       * @return  (Date) the corrected date
       */
      _daylightSavingAdjust: function _daylightSavingAdjust(date) {
        if (!date) {
          return null;
        }

        date.setHours(date.getHours() > 12 ? date.getHours() + 2 : 0);
        return date;
      },

      /* Set the date(s) directly. */
      _setDate: function _setDate(inst, date, noChange) {
        var clear = !date,
            origMonth = inst.selectedMonth,
            origYear = inst.selectedYear,
            newDate = this._restrictMinMax(inst, this._determineDate(inst, date, new Date()));

        inst.selectedDay = inst.currentDay = newDate.getDate();
        inst.drawMonth = inst.selectedMonth = inst.currentMonth = newDate.getMonth();
        inst.drawYear = inst.selectedYear = inst.currentYear = newDate.getFullYear();

        if ((origMonth !== inst.selectedMonth || origYear !== inst.selectedYear) && !noChange) {
          this._notifyChange(inst);
        }

        this._adjustInstDate(inst);

        if (inst.input) {
          inst.input.val(clear ? "" : this._formatDate(inst));
        }
      },

      /* Retrieve the date(s) directly. */
      _getDate: function _getDate(inst) {
        var startDate = !inst.currentYear || inst.input && inst.input.val() === "" ? null : this._daylightSavingAdjust(new Date(inst.currentYear, inst.currentMonth, inst.currentDay));
        return startDate;
      },

      /* Attach the onxxx handlers.  These are declared statically so
       * they work with static code transformers like Caja.
       */
      _attachHandlers: function _attachHandlers(inst) {
        var stepMonths = this._get(inst, "stepMonths"),
            id = "#" + inst.id.replace(/\\\\/g, "\\");

        inst.dpDiv.find("[data-handler]").map(function () {
          var handler = {
            prev: function prev() {
              $.datepicker._adjustDate(id, -stepMonths, "M");
            },
            next: function next() {
              $.datepicker._adjustDate(id, +stepMonths, "M");
            },
            hide: function hide() {
              $.datepicker._hideDatepicker();
            },
            today: function today() {
              $.datepicker._gotoToday(id);
            },
            selectDay: function selectDay() {
              $.datepicker._selectDay(id, +this.getAttribute("data-month"), +this.getAttribute("data-year"), this);

              return false;
            },
            selectMonth: function selectMonth() {
              $.datepicker._selectMonthYear(id, this, "M");

              return false;
            },
            selectYear: function selectYear() {
              $.datepicker._selectMonthYear(id, this, "Y");

              return false;
            }
          };
          $(this).on(this.getAttribute("data-event"), handler[this.getAttribute("data-handler")]);
        });
      },

      /* Generate the HTML for the current state of the date picker. */
      _generateHTML: function _generateHTML(inst) {
        var maxDraw,
            prevText,
            prev,
            nextText,
            next,
            currentText,
            gotoDate,
            controls,
            buttonPanel,
            firstDay,
            showWeek,
            dayNames,
            dayNamesMin,
            monthNames,
            monthNamesShort,
            beforeShowDay,
            showOtherMonths,
            selectOtherMonths,
            defaultDate,
            html,
            dow,
            row,
            group,
            col,
            selectedDate,
            cornerClass,
            calender,
            thead,
            day,
            daysInMonth,
            leadDays,
            curRows,
            numRows,
            printDate,
            dRow,
            tbody,
            daySettings,
            otherMonth,
            unselectable,
            tempDate = new Date(),
            today = this._daylightSavingAdjust(new Date(tempDate.getFullYear(), tempDate.getMonth(), tempDate.getDate())),
            // clear time
        isRTL = this._get(inst, "isRTL"),
            showButtonPanel = this._get(inst, "showButtonPanel"),
            hideIfNoPrevNext = this._get(inst, "hideIfNoPrevNext"),
            navigationAsDateFormat = this._get(inst, "navigationAsDateFormat"),
            numMonths = this._getNumberOfMonths(inst),
            showCurrentAtPos = this._get(inst, "showCurrentAtPos"),
            stepMonths = this._get(inst, "stepMonths"),
            isMultiMonth = numMonths[0] !== 1 || numMonths[1] !== 1,
            currentDate = this._daylightSavingAdjust(!inst.currentDay ? new Date(9999, 9, 9) : new Date(inst.currentYear, inst.currentMonth, inst.currentDay)),
            minDate = this._getMinMaxDate(inst, "min"),
            maxDate = this._getMinMaxDate(inst, "max"),
            drawMonth = inst.drawMonth - showCurrentAtPos,
            drawYear = inst.drawYear;

        if (drawMonth < 0) {
          drawMonth += 12;
          drawYear--;
        }

        if (maxDate) {
          maxDraw = this._daylightSavingAdjust(new Date(maxDate.getFullYear(), maxDate.getMonth() - numMonths[0] * numMonths[1] + 1, maxDate.getDate()));
          maxDraw = minDate && maxDraw < minDate ? minDate : maxDraw;

          while (this._daylightSavingAdjust(new Date(drawYear, drawMonth, 1)) > maxDraw) {
            drawMonth--;

            if (drawMonth < 0) {
              drawMonth = 11;
              drawYear--;
            }
          }
        }

        inst.drawMonth = drawMonth;
        inst.drawYear = drawYear;
        prevText = this._get(inst, "prevText");
        prevText = !navigationAsDateFormat ? prevText : this.formatDate(prevText, this._daylightSavingAdjust(new Date(drawYear, drawMonth - stepMonths, 1)), this._getFormatConfig(inst));

        if (this._canAdjustMonth(inst, -1, drawYear, drawMonth)) {
          prev = $("<a>").attr({
            "class": "ui-datepicker-prev ui-corner-all",
            "data-handler": "prev",
            "data-event": "click",
            title: prevText
          }).append($("<span>").addClass("ui-icon ui-icon-circle-triangle-" + (isRTL ? "e" : "w")).text(prevText))[0].outerHTML;
        } else if (hideIfNoPrevNext) {
          prev = "";
        } else {
          prev = $("<a>").attr({
            "class": "ui-datepicker-prev ui-corner-all ui-state-disabled",
            title: prevText
          }).append($("<span>").addClass("ui-icon ui-icon-circle-triangle-" + (isRTL ? "e" : "w")).text(prevText))[0].outerHTML;
        }

        nextText = this._get(inst, "nextText");
        nextText = !navigationAsDateFormat ? nextText : this.formatDate(nextText, this._daylightSavingAdjust(new Date(drawYear, drawMonth + stepMonths, 1)), this._getFormatConfig(inst));

        if (this._canAdjustMonth(inst, +1, drawYear, drawMonth)) {
          next = $("<a>").attr({
            "class": "ui-datepicker-next ui-corner-all",
            "data-handler": "next",
            "data-event": "click",
            title: nextText
          }).append($("<span>").addClass("ui-icon ui-icon-circle-triangle-" + (isRTL ? "w" : "e")).text(nextText))[0].outerHTML;
        } else if (hideIfNoPrevNext) {
          next = "";
        } else {
          next = $("<a>").attr({
            "class": "ui-datepicker-next ui-corner-all ui-state-disabled",
            title: nextText
          }).append($("<span>").attr("class", "ui-icon ui-icon-circle-triangle-" + (isRTL ? "w" : "e")).text(nextText))[0].outerHTML;
        }

        currentText = this._get(inst, "currentText");
        gotoDate = this._get(inst, "gotoCurrent") && inst.currentDay ? currentDate : today;
        currentText = !navigationAsDateFormat ? currentText : this.formatDate(currentText, gotoDate, this._getFormatConfig(inst));
        controls = "";

        if (!inst.inline) {
          controls = $("<button>").attr({
            type: "button",
            "class": "ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all",
            "data-handler": "hide",
            "data-event": "click"
          }).text(this._get(inst, "closeText"))[0].outerHTML;
        }

        buttonPanel = "";

        if (showButtonPanel) {
          buttonPanel = $("<div class='ui-datepicker-buttonpane ui-widget-content'>").append(isRTL ? controls : "").append(this._isInRange(inst, gotoDate) ? $("<button>").attr({
            type: "button",
            "class": "ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all",
            "data-handler": "today",
            "data-event": "click"
          }).text(currentText) : "").append(isRTL ? "" : controls)[0].outerHTML;
        }

        firstDay = parseInt(this._get(inst, "firstDay"), 10);
        firstDay = isNaN(firstDay) ? 0 : firstDay;
        showWeek = this._get(inst, "showWeek");
        dayNames = this._get(inst, "dayNames");
        dayNamesMin = this._get(inst, "dayNamesMin");
        monthNames = this._get(inst, "monthNames");
        monthNamesShort = this._get(inst, "monthNamesShort");
        beforeShowDay = this._get(inst, "beforeShowDay");
        showOtherMonths = this._get(inst, "showOtherMonths");
        selectOtherMonths = this._get(inst, "selectOtherMonths");
        defaultDate = this._getDefaultDate(inst);
        html = "";

        for (row = 0; row < numMonths[0]; row++) {
          group = "";
          this.maxRows = 4;

          for (col = 0; col < numMonths[1]; col++) {
            selectedDate = this._daylightSavingAdjust(new Date(drawYear, drawMonth, inst.selectedDay));
            cornerClass = " ui-corner-all";
            calender = "";

            if (isMultiMonth) {
              calender += "<div class='ui-datepicker-group";

              if (numMonths[1] > 1) {
                switch (col) {
                  case 0:
                    calender += " ui-datepicker-group-first";
                    cornerClass = " ui-corner-" + (isRTL ? "right" : "left");
                    break;

                  case numMonths[1] - 1:
                    calender += " ui-datepicker-group-last";
                    cornerClass = " ui-corner-" + (isRTL ? "left" : "right");
                    break;

                  default:
                    calender += " ui-datepicker-group-middle";
                    cornerClass = "";
                    break;
                }
              }

              calender += "'>";
            }

            calender += "<div class='ui-datepicker-header ui-widget-header ui-helper-clearfix" + cornerClass + "'>" + (/all|left/.test(cornerClass) && row === 0 ? isRTL ? next : prev : "") + (/all|right/.test(cornerClass) && row === 0 ? isRTL ? prev : next : "") + this._generateMonthYearHeader(inst, drawMonth, drawYear, minDate, maxDate, row > 0 || col > 0, monthNames, monthNamesShort) + // draw month headers
            "</div><table class='ui-datepicker-calendar'><thead>" + "<tr>";
            thead = showWeek ? "<th class='ui-datepicker-week-col'>" + this._get(inst, "weekHeader") + "</th>" : "";

            for (dow = 0; dow < 7; dow++) {
              // days of the week
              day = (dow + firstDay) % 7;
              thead += "<th scope='col'" + ((dow + firstDay + 6) % 7 >= 5 ? " class='ui-datepicker-week-end'" : "") + ">" + "<span title='" + dayNames[day] + "'>" + dayNamesMin[day] + "</span></th>";
            }

            calender += thead + "</tr></thead><tbody>";
            daysInMonth = this._getDaysInMonth(drawYear, drawMonth);

            if (drawYear === inst.selectedYear && drawMonth === inst.selectedMonth) {
              inst.selectedDay = Math.min(inst.selectedDay, daysInMonth);
            }

            leadDays = (this._getFirstDayOfMonth(drawYear, drawMonth) - firstDay + 7) % 7;
            curRows = Math.ceil((leadDays + daysInMonth) / 7); // calculate the number of rows to generate

            numRows = isMultiMonth ? this.maxRows > curRows ? this.maxRows : curRows : curRows; //If multiple months, use the higher number of rows (see #7043)

            this.maxRows = numRows;
            printDate = this._daylightSavingAdjust(new Date(drawYear, drawMonth, 1 - leadDays));

            for (dRow = 0; dRow < numRows; dRow++) {
              // create date picker rows
              calender += "<tr>";
              tbody = !showWeek ? "" : "<td class='ui-datepicker-week-col'>" + this._get(inst, "calculateWeek")(printDate) + "</td>";

              for (dow = 0; dow < 7; dow++) {
                // create date picker days
                daySettings = beforeShowDay ? beforeShowDay.apply(inst.input ? inst.input[0] : null, [printDate]) : [true, ""];
                otherMonth = printDate.getMonth() !== drawMonth;
                unselectable = otherMonth && !selectOtherMonths || !daySettings[0] || minDate && printDate < minDate || maxDate && printDate > maxDate;
                tbody += "<td class='" + ((dow + firstDay + 6) % 7 >= 5 ? " ui-datepicker-week-end" : "") + ( // highlight weekends
                otherMonth ? " ui-datepicker-other-month" : "") + ( // highlight days from other months
                printDate.getTime() === selectedDate.getTime() && drawMonth === inst.selectedMonth && inst._keyEvent || // user pressed key
                defaultDate.getTime() === printDate.getTime() && defaultDate.getTime() === selectedDate.getTime() ? // or defaultDate is current printedDate and defaultDate is selectedDate
                " " + this._dayOverClass : "") + ( // highlight selected day
                unselectable ? " " + this._unselectableClass + " ui-state-disabled" : "") + ( // highlight unselectable days
                otherMonth && !showOtherMonths ? "" : " " + daySettings[1] + ( // highlight custom dates
                printDate.getTime() === currentDate.getTime() ? " " + this._currentClass : "") + ( // highlight selected day
                printDate.getTime() === today.getTime() ? " ui-datepicker-today" : "")) + "'" + ( // highlight today (if different)
                (!otherMonth || showOtherMonths) && daySettings[2] ? " title='" + daySettings[2].replace(/'/g, "&#39;") + "'" : "") + ( // cell title
                unselectable ? "" : " data-handler='selectDay' data-event='click' data-month='" + printDate.getMonth() + "' data-year='" + printDate.getFullYear() + "'") + ">" + ( // actions
                otherMonth && !showOtherMonths ? "&#xa0;" : // display for other months
                unselectable ? "<span class='ui-state-default'>" + printDate.getDate() + "</span>" : "<a class='ui-state-default" + (printDate.getTime() === today.getTime() ? " ui-state-highlight" : "") + (printDate.getTime() === currentDate.getTime() ? " ui-state-active" : "") + ( // highlight selected day
                otherMonth ? " ui-priority-secondary" : "") + // distinguish dates from other months
                "' href='#' aria-current='" + (printDate.getTime() === currentDate.getTime() ? "true" : "false") + // mark date as selected for screen reader
                "' data-date='" + printDate.getDate() + // store date as data
                "'>" + printDate.getDate() + "</a>") + "</td>"; // display selectable date

                printDate.setDate(printDate.getDate() + 1);
                printDate = this._daylightSavingAdjust(printDate);
              }

              calender += tbody + "</tr>";
            }

            drawMonth++;

            if (drawMonth > 11) {
              drawMonth = 0;
              drawYear++;
            }

            calender += "</tbody></table>" + (isMultiMonth ? "</div>" + (numMonths[0] > 0 && col === numMonths[1] - 1 ? "<div class='ui-datepicker-row-break'></div>" : "") : "");
            group += calender;
          }

          html += group;
        }

        html += buttonPanel;
        inst._keyEvent = false;
        return html;
      },

      /* Generate the month and year header. */
      _generateMonthYearHeader: function _generateMonthYearHeader(inst, drawMonth, drawYear, minDate, maxDate, secondary, monthNames, monthNamesShort) {
        var inMinYear,
            inMaxYear,
            month,
            years,
            thisYear,
            determineYear,
            year,
            endYear,
            changeMonth = this._get(inst, "changeMonth"),
            changeYear = this._get(inst, "changeYear"),
            showMonthAfterYear = this._get(inst, "showMonthAfterYear"),
            selectMonthLabel = this._get(inst, "selectMonthLabel"),
            selectYearLabel = this._get(inst, "selectYearLabel"),
            html = "<div class='ui-datepicker-title'>",
            monthHtml = ""; // Month selection


        if (secondary || !changeMonth) {
          monthHtml += "<span class='ui-datepicker-month'>" + monthNames[drawMonth] + "</span>";
        } else {
          inMinYear = minDate && minDate.getFullYear() === drawYear;
          inMaxYear = maxDate && maxDate.getFullYear() === drawYear;
          monthHtml += "<select class='ui-datepicker-month' aria-label='" + selectMonthLabel + "' data-handler='selectMonth' data-event='change'>";

          for (month = 0; month < 12; month++) {
            if ((!inMinYear || month >= minDate.getMonth()) && (!inMaxYear || month <= maxDate.getMonth())) {
              monthHtml += "<option value='" + month + "'" + (month === drawMonth ? " selected='selected'" : "") + ">" + monthNamesShort[month] + "</option>";
            }
          }

          monthHtml += "</select>";
        }

        if (!showMonthAfterYear) {
          html += monthHtml + (secondary || !(changeMonth && changeYear) ? "&#xa0;" : "");
        } // Year selection


        if (!inst.yearshtml) {
          inst.yearshtml = "";

          if (secondary || !changeYear) {
            html += "<span class='ui-datepicker-year'>" + drawYear + "</span>";
          } else {
            // determine range of years to display
            years = this._get(inst, "yearRange").split(":");
            thisYear = new Date().getFullYear();

            determineYear = function determineYear(value) {
              var year = value.match(/c[+\-].*/) ? drawYear + parseInt(value.substring(1), 10) : value.match(/[+\-].*/) ? thisYear + parseInt(value, 10) : parseInt(value, 10);
              return isNaN(year) ? thisYear : year;
            };

            year = determineYear(years[0]);
            endYear = Math.max(year, determineYear(years[1] || ""));
            year = minDate ? Math.max(year, minDate.getFullYear()) : year;
            endYear = maxDate ? Math.min(endYear, maxDate.getFullYear()) : endYear;
            inst.yearshtml += "<select class='ui-datepicker-year' aria-label='" + selectYearLabel + "' data-handler='selectYear' data-event='change'>";

            for (; year <= endYear; year++) {
              inst.yearshtml += "<option value='" + year + "'" + (year === drawYear ? " selected='selected'" : "") + ">" + year + "</option>";
            }

            inst.yearshtml += "</select>";
            html += inst.yearshtml;
            inst.yearshtml = null;
          }
        }

        html += this._get(inst, "yearSuffix");

        if (showMonthAfterYear) {
          html += (secondary || !(changeMonth && changeYear) ? "&#xa0;" : "") + monthHtml;
        }

        html += "</div>"; // Close datepicker_header

        return html;
      },

      /* Adjust one of the date sub-fields. */
      _adjustInstDate: function _adjustInstDate(inst, offset, period) {
        var year = inst.selectedYear + (period === "Y" ? offset : 0),
            month = inst.selectedMonth + (period === "M" ? offset : 0),
            day = Math.min(inst.selectedDay, this._getDaysInMonth(year, month)) + (period === "D" ? offset : 0),
            date = this._restrictMinMax(inst, this._daylightSavingAdjust(new Date(year, month, day)));

        inst.selectedDay = date.getDate();
        inst.drawMonth = inst.selectedMonth = date.getMonth();
        inst.drawYear = inst.selectedYear = date.getFullYear();

        if (period === "M" || period === "Y") {
          this._notifyChange(inst);
        }
      },

      /* Ensure a date is within any min/max bounds. */
      _restrictMinMax: function _restrictMinMax(inst, date) {
        var minDate = this._getMinMaxDate(inst, "min"),
            maxDate = this._getMinMaxDate(inst, "max"),
            newDate = minDate && date < minDate ? minDate : date;

        return maxDate && newDate > maxDate ? maxDate : newDate;
      },

      /* Notify change of month/year. */
      _notifyChange: function _notifyChange(inst) {
        var onChange = this._get(inst, "onChangeMonthYear");

        if (onChange) {
          onChange.apply(inst.input ? inst.input[0] : null, [inst.selectedYear, inst.selectedMonth + 1, inst]);
        }
      },

      /* Determine the number of months to show. */
      _getNumberOfMonths: function _getNumberOfMonths(inst) {
        var numMonths = this._get(inst, "numberOfMonths");

        return numMonths == null ? [1, 1] : typeof numMonths === "number" ? [1, numMonths] : numMonths;
      },

      /* Determine the current maximum date - ensure no time components are set. */
      _getMinMaxDate: function _getMinMaxDate(inst, minMax) {
        return this._determineDate(inst, this._get(inst, minMax + "Date"), null);
      },

      /* Find the number of days in a given month. */
      _getDaysInMonth: function _getDaysInMonth(year, month) {
        return 32 - this._daylightSavingAdjust(new Date(year, month, 32)).getDate();
      },

      /* Find the day of the week of the first of a month. */
      _getFirstDayOfMonth: function _getFirstDayOfMonth(year, month) {
        return new Date(year, month, 1).getDay();
      },

      /* Determines if we should allow a "next/prev" month display change. */
      _canAdjustMonth: function _canAdjustMonth(inst, offset, curYear, curMonth) {
        var numMonths = this._getNumberOfMonths(inst),
            date = this._daylightSavingAdjust(new Date(curYear, curMonth + (offset < 0 ? offset : numMonths[0] * numMonths[1]), 1));

        if (offset < 0) {
          date.setDate(this._getDaysInMonth(date.getFullYear(), date.getMonth()));
        }

        return this._isInRange(inst, date);
      },

      /* Is the given date in the accepted range? */
      _isInRange: function _isInRange(inst, date) {
        var yearSplit,
            currentYear,
            minDate = this._getMinMaxDate(inst, "min"),
            maxDate = this._getMinMaxDate(inst, "max"),
            minYear = null,
            maxYear = null,
            years = this._get(inst, "yearRange");

        if (years) {
          yearSplit = years.split(":");
          currentYear = new Date().getFullYear();
          minYear = parseInt(yearSplit[0], 10);
          maxYear = parseInt(yearSplit[1], 10);

          if (yearSplit[0].match(/[+\-].*/)) {
            minYear += currentYear;
          }

          if (yearSplit[1].match(/[+\-].*/)) {
            maxYear += currentYear;
          }
        }

        return (!minDate || date.getTime() >= minDate.getTime()) && (!maxDate || date.getTime() <= maxDate.getTime()) && (!minYear || date.getFullYear() >= minYear) && (!maxYear || date.getFullYear() <= maxYear);
      },

      /* Provide the configuration settings for formatting/parsing. */
      _getFormatConfig: function _getFormatConfig(inst) {
        var shortYearCutoff = this._get(inst, "shortYearCutoff");

        shortYearCutoff = typeof shortYearCutoff !== "string" ? shortYearCutoff : new Date().getFullYear() % 100 + parseInt(shortYearCutoff, 10);
        return {
          shortYearCutoff: shortYearCutoff,
          dayNamesShort: this._get(inst, "dayNamesShort"),
          dayNames: this._get(inst, "dayNames"),
          monthNamesShort: this._get(inst, "monthNamesShort"),
          monthNames: this._get(inst, "monthNames")
        };
      },

      /* Format the given date for display. */
      _formatDate: function _formatDate(inst, day, month, year) {
        if (!day) {
          inst.currentDay = inst.selectedDay;
          inst.currentMonth = inst.selectedMonth;
          inst.currentYear = inst.selectedYear;
        }

        var date = day ? _typeof(day) === "object" ? day : this._daylightSavingAdjust(new Date(year, month, day)) : this._daylightSavingAdjust(new Date(inst.currentYear, inst.currentMonth, inst.currentDay));
        return this.formatDate(this._get(inst, "dateFormat"), date, this._getFormatConfig(inst));
      }
    });
    /*
     * Bind hover events for datepicker elements.
     * Done via delegate so the binding only occurs once in the lifetime of the parent div.
     * Global datepicker_instActive, set by _updateDatepicker allows the handlers to find their way back to the active picker.
     */

    function datepicker_bindHover(dpDiv) {
      var selector = "button, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-calendar td a";
      return dpDiv.on("mouseout", selector, function () {
        $(this).removeClass("ui-state-hover");

        if (this.className.indexOf("ui-datepicker-prev") !== -1) {
          $(this).removeClass("ui-datepicker-prev-hover");
        }

        if (this.className.indexOf("ui-datepicker-next") !== -1) {
          $(this).removeClass("ui-datepicker-next-hover");
        }
      }).on("mouseover", selector, datepicker_handleMouseover);
    }

    function datepicker_handleMouseover() {
      if (!$.datepicker._isDisabledDatepicker(datepicker_instActive.inline ? datepicker_instActive.dpDiv.parent()[0] : datepicker_instActive.input[0])) {
        $(this).parents(".ui-datepicker-calendar").find("a").removeClass("ui-state-hover");
        $(this).addClass("ui-state-hover");

        if (this.className.indexOf("ui-datepicker-prev") !== -1) {
          $(this).addClass("ui-datepicker-prev-hover");
        }

        if (this.className.indexOf("ui-datepicker-next") !== -1) {
          $(this).addClass("ui-datepicker-next-hover");
        }
      }
    }
    /* jQuery extend now ignores nulls! */


    function datepicker_extendRemove(target, props) {
      $.extend(target, props);

      for (var name in props) {
        if (props[name] == null) {
          target[name] = props[name];
        }
      }

      return target;
    }
    /* Invoke the datepicker functionality.
       @param  options  string - a command, optionally followed by additional parameters or
    					Object - settings for attaching new datepicker functionality
       @return  jQuery object */


    $.fn.datepicker = function (options) {
      /* Verify an empty collection wasn't passed - Fixes #6976 */
      if (!this.length) {
        return this;
      }
      /* Initialise the date picker. */


      if (!$.datepicker.initialized) {
        $(document).on("mousedown", $.datepicker._checkExternalClick);
        $.datepicker.initialized = true;
      }
      /* Append datepicker main container to body if not exist. */


      if ($("#" + $.datepicker._mainDivId).length === 0) {
        $("body").append($.datepicker.dpDiv);
      }

      var otherArgs = Array.prototype.slice.call(arguments, 1);

      if (typeof options === "string" && (options === "isDisabled" || options === "getDate" || options === "widget")) {
        return $.datepicker["_" + options + "Datepicker"].apply($.datepicker, [this[0]].concat(otherArgs));
      }

      if (options === "option" && arguments.length === 2 && typeof arguments[1] === "string") {
        return $.datepicker["_" + options + "Datepicker"].apply($.datepicker, [this[0]].concat(otherArgs));
      }

      return this.each(function () {
        if (typeof options === "string") {
          $.datepicker["_" + options + "Datepicker"].apply($.datepicker, [this].concat(otherArgs));
        } else {
          $.datepicker._attachDatepicker(this, options);
        }
      });
    };

    $.datepicker = new Datepicker(); // singleton instance

    $.datepicker.initialized = false;
    $.datepicker.uuid = new Date().getTime();
    $.datepicker.version = "1.13.2";
    return $.datepicker;
  });

  /* Japanese initialisation for the jQuery UI date picker plugin. */

  /* Written by Kentaro SATO (kentaro@ranvis.com). */
  (function (factory) {

    if (typeof define === "function" && define.amd) {
      // AMD. Register as an anonymous module.
      define(["../widgets/datepicker"], factory);
    } else {
      // Browser globals
      factory(jQuery.datepicker);
    }
  })(function (datepicker) {

    datepicker.regional.ja = {
      closeText: "閉じる",
      prevText: "前",
      nextText: "次",
      currentText: "今日",
      monthNames: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
      monthNamesShort: ["1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月"],
      dayNames: ["日曜日", "月曜日", "火曜日", "水曜日", "木曜日", "金曜日", "土曜日"],
      dayNamesShort: ["日", "月", "火", "水", "木", "金", "土"],
      dayNamesMin: ["日", "月", "火", "水", "木", "金", "土"],
      weekHeader: "週",
      dateFormat: "yy/mm/dd",
      firstDay: 0,
      isRTL: false,
      showMonthAfterYear: true,
      yearSuffix: "年"
    };
    datepicker.setDefaults(datepicker.regional.ja);
    return datepicker.regional.ja;
  });

  function createCommonjsModule(fn) {
    var module = { exports: {} };
  	return fn(module, module.exports), module.exports;
  }

  createCommonjsModule(function (module) {
    (function (factory) {
      if (module.exports) {
        module.exports = factory($__default['default']);
      } else {
        factory(jQuery);
      }
    })(function ($) {
      $.extend($.fn, {
        // https://jqueryvalidation.org/validate/
        validate: function validate(options) {
          // If nothing is selected, return nothing; can't chain anyway
          if (!this.length) {
            if (options && options.debug && window.console) {
              console.warn("Nothing selected, can't validate, returning nothing.");
            }

            return;
          } // Check if a validator for this form was already created


          var validator = $.data(this[0], "validator");

          if (validator) {
            return validator;
          } // Add novalidate tag if HTML5.


          this.attr("novalidate", "novalidate");
          validator = new $.validator(options, this[0]);
          $.data(this[0], "validator", validator);

          if (validator.settings.onsubmit) {
            this.on("click.validate", ":submit", function (event) {
              // Track the used submit button to properly handle scripted
              // submits later.
              validator.submitButton = event.currentTarget; // Allow suppressing validation by adding a cancel class to the submit button

              if ($(this).hasClass("cancel")) {
                validator.cancelSubmit = true;
              } // Allow suppressing validation by adding the html5 formnovalidate attribute to the submit button


              if ($(this).attr("formnovalidate") !== undefined) {
                validator.cancelSubmit = true;
              }
            }); // Validate the form on submit

            this.on("submit.validate", function (event) {
              if (validator.settings.debug) {
                // Prevent form submit to be able to see console output
                event.preventDefault();
              }

              function handle() {
                var hidden, result; // Insert a hidden input as a replacement for the missing submit button
                // The hidden input is inserted in two cases:
                //   - A user defined a `submitHandler`
                //   - There was a pending request due to `remote` method and `stopRequest()`
                //     was called to submit the form in case it's valid

                if (validator.submitButton && (validator.settings.submitHandler || validator.formSubmitted)) {
                  hidden = $("<input type='hidden'/>").attr("name", validator.submitButton.name).val($(validator.submitButton).val()).appendTo(validator.currentForm);
                }

                if (validator.settings.submitHandler && !validator.settings.debug) {
                  result = validator.settings.submitHandler.call(validator, validator.currentForm, event);

                  if (hidden) {
                    // And clean up afterwards; thanks to no-block-scope, hidden can be referenced
                    hidden.remove();
                  }

                  if (result !== undefined) {
                    return result;
                  }

                  return false;
                }

                return true;
              } // Prevent submit for invalid forms or custom submit handlers


              if (validator.cancelSubmit) {
                validator.cancelSubmit = false;
                return handle();
              }

              if (validator.form()) {
                if (validator.pendingRequest) {
                  validator.formSubmitted = true;
                  return false;
                }

                return handle();
              } else {
                validator.focusInvalid();
                return false;
              }
            });
          }

          return validator;
        },
        // https://jqueryvalidation.org/valid/
        valid: function valid() {
          var valid, validator, errorList;

          if ($(this[0]).is("form")) {
            valid = this.validate().form();
          } else {
            errorList = [];
            valid = true;
            validator = $(this[0].form).validate();
            this.each(function () {
              valid = validator.element(this) && valid;

              if (!valid) {
                errorList = errorList.concat(validator.errorList);
              }
            });
            validator.errorList = errorList;
          }

          return valid;
        },
        // https://jqueryvalidation.org/rules/
        rules: function rules(command, argument) {
          var element = this[0],
              isContentEditable = typeof this.attr("contenteditable") !== "undefined" && this.attr("contenteditable") !== "false",
              settings,
              staticRules,
              existingRules,
              data,
              param,
              filtered; // If nothing is selected, return empty object; can't chain anyway

          if (element == null) {
            return;
          }

          if (!element.form && isContentEditable) {
            element.form = this.closest("form")[0];
            element.name = this.attr("name");
          }

          if (element.form == null) {
            return;
          }

          if (command) {
            settings = $.data(element.form, "validator").settings;
            staticRules = settings.rules;
            existingRules = $.validator.staticRules(element);

            switch (command) {
              case "add":
                $.extend(existingRules, $.validator.normalizeRule(argument)); // Remove messages from rules, but allow them to be set separately

                delete existingRules.messages;
                staticRules[element.name] = existingRules;

                if (argument.messages) {
                  settings.messages[element.name] = $.extend(settings.messages[element.name], argument.messages);
                }

                break;

              case "remove":
                if (!argument) {
                  delete staticRules[element.name];
                  return existingRules;
                }

                filtered = {};
                $.each(argument.split(/\s/), function (index, method) {
                  filtered[method] = existingRules[method];
                  delete existingRules[method];
                });
                return filtered;
            }
          }

          data = $.validator.normalizeRules($.extend({}, $.validator.classRules(element), $.validator.attributeRules(element), $.validator.dataRules(element), $.validator.staticRules(element)), element); // Make sure required is at front

          if (data.required) {
            param = data.required;
            delete data.required;
            data = $.extend({
              required: param
            }, data);
          } // Make sure remote is at back


          if (data.remote) {
            param = data.remote;
            delete data.remote;
            data = $.extend(data, {
              remote: param
            });
          }

          return data;
        }
      }); // JQuery trim is deprecated, provide a trim method based on String.prototype.trim

      var trim = function trim(str) {
        // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/String/trim#Polyfill
        return str.replace(/^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g, "");
      }; // Custom selectors


      $.extend($.expr.pseudos || $.expr[":"], {
        // '|| $.expr[ ":" ]' here enables backwards compatibility to jQuery 1.7. Can be removed when dropping jQ 1.7.x support
        // https://jqueryvalidation.org/blank-selector/
        blank: function blank(a) {
          return !trim("" + $(a).val());
        },
        // https://jqueryvalidation.org/filled-selector/
        filled: function filled(a) {
          var val = $(a).val();
          return val !== null && !!trim("" + val);
        },
        // https://jqueryvalidation.org/unchecked-selector/
        unchecked: function unchecked(a) {
          return !$(a).prop("checked");
        }
      }); // Constructor for validator

      $.validator = function (options, form) {
        this.settings = $.extend(true, {}, $.validator.defaults, options);
        this.currentForm = form;
        this.init();
      }; // https://jqueryvalidation.org/jQuery.validator.format/


      $.validator.format = function (source, params) {
        if (arguments.length === 1) {
          return function () {
            var args = $.makeArray(arguments);
            args.unshift(source);
            return $.validator.format.apply(this, args);
          };
        }

        if (params === undefined) {
          return source;
        }

        if (arguments.length > 2 && params.constructor !== Array) {
          params = $.makeArray(arguments).slice(1);
        }

        if (params.constructor !== Array) {
          params = [params];
        }

        $.each(params, function (i, n) {
          source = source.replace(new RegExp("\\{" + i + "\\}", "g"), function () {
            return n;
          });
        });
        return source;
      };

      $.extend($.validator, {
        defaults: {
          messages: {},
          groups: {},
          rules: {},
          errorClass: "error",
          pendingClass: "pending",
          validClass: "valid",
          errorElement: "label",
          focusCleanup: false,
          focusInvalid: true,
          errorContainer: $([]),
          errorLabelContainer: $([]),
          onsubmit: true,
          ignore: ":hidden",
          ignoreTitle: false,
          onfocusin: function onfocusin(element) {
            this.lastActive = element; // Hide error label and remove error class on focus if enabled

            if (this.settings.focusCleanup) {
              if (this.settings.unhighlight) {
                this.settings.unhighlight.call(this, element, this.settings.errorClass, this.settings.validClass);
              }

              this.hideThese(this.errorsFor(element));
            }
          },
          onfocusout: function onfocusout(element) {
            if (!this.checkable(element) && (element.name in this.submitted || !this.optional(element))) {
              this.element(element);
            }
          },
          onkeyup: function onkeyup(element, event) {
            // Avoid revalidate the field when pressing one of the following keys
            // Shift       => 16
            // Ctrl        => 17
            // Alt         => 18
            // Caps lock   => 20
            // End         => 35
            // Home        => 36
            // Left arrow  => 37
            // Up arrow    => 38
            // Right arrow => 39
            // Down arrow  => 40
            // Insert      => 45
            // Num lock    => 144
            // AltGr key   => 225
            var excludedKeys = [16, 17, 18, 20, 35, 36, 37, 38, 39, 40, 45, 144, 225];

            if (event.which === 9 && this.elementValue(element) === "" || $.inArray(event.keyCode, excludedKeys) !== -1) {
              return;
            } else if (element.name in this.submitted || element.name in this.invalid) {
              this.element(element);
            }
          },
          onclick: function onclick(element) {
            // Click on selects, radiobuttons and checkboxes
            if (element.name in this.submitted) {
              this.element(element); // Or option elements, check parent select in that case
            } else if (element.parentNode.name in this.submitted) {
              this.element(element.parentNode);
            }
          },
          highlight: function highlight(element, errorClass, validClass) {
            if (element.type === "radio") {
              this.findByName(element.name).addClass(errorClass).removeClass(validClass);
            } else {
              $(element).addClass(errorClass).removeClass(validClass);
            }
          },
          unhighlight: function unhighlight(element, errorClass, validClass) {
            if (element.type === "radio") {
              this.findByName(element.name).removeClass(errorClass).addClass(validClass);
            } else {
              $(element).removeClass(errorClass).addClass(validClass);
            }
          }
        },
        // https://jqueryvalidation.org/jQuery.validator.setDefaults/
        setDefaults: function setDefaults(settings) {
          $.extend($.validator.defaults, settings);
        },
        messages: {
          required: "This field is required.",
          remote: "Please fix this field.",
          email: "Please enter a valid email address.",
          url: "Please enter a valid URL.",
          date: "Please enter a valid date.",
          dateISO: "Please enter a valid date (ISO).",
          number: "Please enter a valid number.",
          digits: "Please enter only digits.",
          equalTo: "Please enter the same value again.",
          maxlength: $.validator.format("Please enter no more than {0} characters."),
          minlength: $.validator.format("Please enter at least {0} characters."),
          rangelength: $.validator.format("Please enter a value between {0} and {1} characters long."),
          range: $.validator.format("Please enter a value between {0} and {1}."),
          max: $.validator.format("Please enter a value less than or equal to {0}."),
          min: $.validator.format("Please enter a value greater than or equal to {0}."),
          step: $.validator.format("Please enter a multiple of {0}.")
        },
        autoCreateRanges: false,
        prototype: {
          init: function init() {
            this.labelContainer = $(this.settings.errorLabelContainer);
            this.errorContext = this.labelContainer.length && this.labelContainer || $(this.currentForm);
            this.containers = $(this.settings.errorContainer).add(this.settings.errorLabelContainer);
            this.submitted = {};
            this.valueCache = {};
            this.pendingRequest = 0;
            this.pending = {};
            this.invalid = {};
            this.reset();
            var currentForm = this.currentForm,
                groups = this.groups = {},
                rules;
            $.each(this.settings.groups, function (key, value) {
              if (typeof value === "string") {
                value = value.split(/\s/);
              }

              $.each(value, function (index, name) {
                groups[name] = key;
              });
            });
            rules = this.settings.rules;
            $.each(rules, function (key, value) {
              rules[key] = $.validator.normalizeRule(value);
            });

            function delegate(event) {
              var isContentEditable = typeof $(this).attr("contenteditable") !== "undefined" && $(this).attr("contenteditable") !== "false"; // Set form expando on contenteditable

              if (!this.form && isContentEditable) {
                this.form = $(this).closest("form")[0];
                this.name = $(this).attr("name");
              } // Ignore the element if it belongs to another form. This will happen mainly
              // when setting the `form` attribute of an input to the id of another form.


              if (currentForm !== this.form) {
                return;
              }

              var validator = $.data(this.form, "validator"),
                  eventType = "on" + event.type.replace(/^validate/, ""),
                  settings = validator.settings;

              if (settings[eventType] && !$(this).is(settings.ignore)) {
                settings[eventType].call(validator, this, event);
              }
            }

            $(this.currentForm).on("focusin.validate focusout.validate keyup.validate", ":text, [type='password'], [type='file'], select, textarea, [type='number'], [type='search'], " + "[type='tel'], [type='url'], [type='email'], [type='datetime'], [type='date'], [type='month'], " + "[type='week'], [type='time'], [type='datetime-local'], [type='range'], [type='color'], " + "[type='radio'], [type='checkbox'], [contenteditable], [type='button']", delegate) // Support: Chrome, oldIE
            // "select" is provided as event.target when clicking a option
            .on("click.validate", "select, option, [type='radio'], [type='checkbox']", delegate);

            if (this.settings.invalidHandler) {
              $(this.currentForm).on("invalid-form.validate", this.settings.invalidHandler);
            }
          },
          // https://jqueryvalidation.org/Validator.form/
          form: function form() {
            this.checkForm();
            $.extend(this.submitted, this.errorMap);
            this.invalid = $.extend({}, this.errorMap);

            if (!this.valid()) {
              $(this.currentForm).triggerHandler("invalid-form", [this]);
            }

            this.showErrors();
            return this.valid();
          },
          checkForm: function checkForm() {
            this.prepareForm();

            for (var i = 0, elements = this.currentElements = this.elements(); elements[i]; i++) {
              this.check(elements[i]);
            }

            return this.valid();
          },
          // https://jqueryvalidation.org/Validator.element/
          element: function element(_element) {
            var cleanElement = this.clean(_element),
                checkElement = this.validationTargetFor(cleanElement),
                v = this,
                result = true,
                rs,
                group;

            if (checkElement === undefined) {
              delete this.invalid[cleanElement.name];
            } else {
              this.prepareElement(checkElement);
              this.currentElements = $(checkElement); // If this element is grouped, then validate all group elements already
              // containing a value

              group = this.groups[checkElement.name];

              if (group) {
                $.each(this.groups, function (name, testgroup) {
                  if (testgroup === group && name !== checkElement.name) {
                    cleanElement = v.validationTargetFor(v.clean(v.findByName(name)));

                    if (cleanElement && cleanElement.name in v.invalid) {
                      v.currentElements.push(cleanElement);
                      result = v.check(cleanElement) && result;
                    }
                  }
                });
              }

              rs = this.check(checkElement) !== false;
              result = result && rs;

              if (rs) {
                this.invalid[checkElement.name] = false;
              } else {
                this.invalid[checkElement.name] = true;
              }

              if (!this.numberOfInvalids()) {
                // Hide error containers on last error
                this.toHide = this.toHide.add(this.containers);
              }

              this.showErrors(); // Add aria-invalid status for screen readers

              $(_element).attr("aria-invalid", !rs);
            }

            return result;
          },
          // https://jqueryvalidation.org/Validator.showErrors/
          showErrors: function showErrors(errors) {
            if (errors) {
              var validator = this; // Add items to error list and map

              $.extend(this.errorMap, errors);
              this.errorList = $.map(this.errorMap, function (message, name) {
                return {
                  message: message,
                  element: validator.findByName(name)[0]
                };
              }); // Remove items from success list

              this.successList = $.grep(this.successList, function (element) {
                return !(element.name in errors);
              });
            }

            if (this.settings.showErrors) {
              this.settings.showErrors.call(this, this.errorMap, this.errorList);
            } else {
              this.defaultShowErrors();
            }
          },
          // https://jqueryvalidation.org/Validator.resetForm/
          resetForm: function resetForm() {
            if ($.fn.resetForm) {
              $(this.currentForm).resetForm();
            }

            this.invalid = {};
            this.submitted = {};
            this.prepareForm();
            this.hideErrors();
            var elements = this.elements().removeData("previousValue").removeAttr("aria-invalid");
            this.resetElements(elements);
          },
          resetElements: function resetElements(elements) {
            var i;

            if (this.settings.unhighlight) {
              for (i = 0; elements[i]; i++) {
                this.settings.unhighlight.call(this, elements[i], this.settings.errorClass, "");
                this.findByName(elements[i].name).removeClass(this.settings.validClass);
              }
            } else {
              elements.removeClass(this.settings.errorClass).removeClass(this.settings.validClass);
            }
          },
          numberOfInvalids: function numberOfInvalids() {
            return this.objectLength(this.invalid);
          },
          objectLength: function objectLength(obj) {
            /* jshint unused: false */
            var count = 0,
                i;

            for (i in obj) {
              // This check allows counting elements with empty error
              // message as invalid elements
              if (obj[i] !== undefined && obj[i] !== null && obj[i] !== false) {
                count++;
              }
            }

            return count;
          },
          hideErrors: function hideErrors() {
            this.hideThese(this.toHide);
          },
          hideThese: function hideThese(errors) {
            errors.not(this.containers).text("");
            this.addWrapper(errors).hide();
          },
          valid: function valid() {
            return this.size() === 0;
          },
          size: function size() {
            return this.errorList.length;
          },
          focusInvalid: function focusInvalid() {
            if (this.settings.focusInvalid) {
              try {
                $(this.findLastActive() || this.errorList.length && this.errorList[0].element || []).filter(":visible").trigger("focus") // Manually trigger focusin event; without it, focusin handler isn't called, findLastActive won't have anything to find
                .trigger("focusin");
              } catch (e) {// Ignore IE throwing errors when focusing hidden elements
              }
            }
          },
          findLastActive: function findLastActive() {
            var lastActive = this.lastActive;
            return lastActive && $.grep(this.errorList, function (n) {
              return n.element.name === lastActive.name;
            }).length === 1 && lastActive;
          },
          elements: function elements() {
            var validator = this,
                rulesCache = {}; // Select all valid inputs inside the form (no submit or reset buttons)

            return $(this.currentForm).find("input, select, textarea, [contenteditable]").not(":submit, :reset, :image, :disabled").not(this.settings.ignore).filter(function () {
              var name = this.name || $(this).attr("name"); // For contenteditable

              var isContentEditable = typeof $(this).attr("contenteditable") !== "undefined" && $(this).attr("contenteditable") !== "false";

              if (!name && validator.settings.debug && window.console) {
                console.error("%o has no name assigned", this);
              } // Set form expando on contenteditable


              if (isContentEditable) {
                this.form = $(this).closest("form")[0];
                this.name = name;
              } // Ignore elements that belong to other/nested forms


              if (this.form !== validator.currentForm) {
                return false;
              } // Select only the first element for each name, and only those with rules specified


              if (name in rulesCache || !validator.objectLength($(this).rules())) {
                return false;
              }

              rulesCache[name] = true;
              return true;
            });
          },
          clean: function clean(selector) {
            return $(selector)[0];
          },
          errors: function errors() {
            var errorClass = this.settings.errorClass.split(" ").join(".");
            return $(this.settings.errorElement + "." + errorClass, this.errorContext);
          },
          resetInternals: function resetInternals() {
            this.successList = [];
            this.errorList = [];
            this.errorMap = {};
            this.toShow = $([]);
            this.toHide = $([]);
          },
          reset: function reset() {
            this.resetInternals();
            this.currentElements = $([]);
          },
          prepareForm: function prepareForm() {
            this.reset();
            this.toHide = this.errors().add(this.containers);
          },
          prepareElement: function prepareElement(element) {
            this.reset();
            this.toHide = this.errorsFor(element);
          },
          elementValue: function elementValue(element) {
            var $element = $(element),
                type = element.type,
                isContentEditable = typeof $element.attr("contenteditable") !== "undefined" && $element.attr("contenteditable") !== "false",
                val,
                idx;

            if (type === "radio" || type === "checkbox") {
              return this.findByName(element.name).filter(":checked").val();
            } else if (type === "number" && typeof element.validity !== "undefined") {
              return element.validity.badInput ? "NaN" : $element.val();
            }

            if (isContentEditable) {
              val = $element.text();
            } else {
              val = $element.val();
            }

            if (type === "file") {
              // Modern browser (chrome & safari)
              if (val.substr(0, 12) === "C:\\fakepath\\") {
                return val.substr(12);
              } // Legacy browsers
              // Unix-based path


              idx = val.lastIndexOf("/");

              if (idx >= 0) {
                return val.substr(idx + 1);
              } // Windows-based path


              idx = val.lastIndexOf("\\");

              if (idx >= 0) {
                return val.substr(idx + 1);
              } // Just the file name


              return val;
            }

            if (typeof val === "string") {
              return val.replace(/\r/g, "");
            }

            return val;
          },
          check: function check(element) {
            element = this.validationTargetFor(this.clean(element));
            var rules = $(element).rules(),
                rulesCount = $.map(rules, function (n, i) {
              return i;
            }).length,
                dependencyMismatch = false,
                val = this.elementValue(element),
                result,
                method,
                rule,
                normalizer; // Prioritize the local normalizer defined for this element over the global one
            // if the former exists, otherwise user the global one in case it exists.

            if (typeof rules.normalizer === "function") {
              normalizer = rules.normalizer;
            } else if (typeof this.settings.normalizer === "function") {
              normalizer = this.settings.normalizer;
            } // If normalizer is defined, then call it to retreive the changed value instead
            // of using the real one.
            // Note that `this` in the normalizer is `element`.


            if (normalizer) {
              val = normalizer.call(element, val); // Delete the normalizer from rules to avoid treating it as a pre-defined method.

              delete rules.normalizer;
            }

            for (method in rules) {
              rule = {
                method: method,
                parameters: rules[method]
              };

              try {
                result = $.validator.methods[method].call(this, val, element, rule.parameters); // If a method indicates that the field is optional and therefore valid,
                // don't mark it as valid when there are no other rules

                if (result === "dependency-mismatch" && rulesCount === 1) {
                  dependencyMismatch = true;
                  continue;
                }

                dependencyMismatch = false;

                if (result === "pending") {
                  this.toHide = this.toHide.not(this.errorsFor(element));
                  return;
                }

                if (!result) {
                  this.formatAndAdd(element, rule);
                  return false;
                }
              } catch (e) {
                if (this.settings.debug && window.console) {
                  console.log("Exception occurred when checking element " + element.id + ", check the '" + rule.method + "' method.", e);
                }

                if (e instanceof TypeError) {
                  e.message += ".  Exception occurred when checking element " + element.id + ", check the '" + rule.method + "' method.";
                }

                throw e;
              }
            }

            if (dependencyMismatch) {
              return;
            }

            if (this.objectLength(rules)) {
              this.successList.push(element);
            }

            return true;
          },
          // Return the custom message for the given element and validation method
          // specified in the element's HTML5 data attribute
          // return the generic message if present and no method specific message is present
          customDataMessage: function customDataMessage(element, method) {
            return $(element).data("msg" + method.charAt(0).toUpperCase() + method.substring(1).toLowerCase()) || $(element).data("msg");
          },
          // Return the custom message for the given element name and validation method
          customMessage: function customMessage(name, method) {
            var m = this.settings.messages[name];
            return m && (m.constructor === String ? m : m[method]);
          },
          // Return the first defined argument, allowing empty strings
          findDefined: function findDefined() {
            for (var i = 0; i < arguments.length; i++) {
              if (arguments[i] !== undefined) {
                return arguments[i];
              }
            }

            return undefined;
          },
          // The second parameter 'rule' used to be a string, and extended to an object literal
          // of the following form:
          // rule = {
          //     method: "method name",
          //     parameters: "the given method parameters"
          // }
          //
          // The old behavior still supported, kept to maintain backward compatibility with
          // old code, and will be removed in the next major release.
          defaultMessage: function defaultMessage(element, rule) {
            if (typeof rule === "string") {
              rule = {
                method: rule
              };
            }

            var message = this.findDefined(this.customMessage(element.name, rule.method), this.customDataMessage(element, rule.method), // 'title' is never undefined, so handle empty string as undefined
            !this.settings.ignoreTitle && element.title || undefined, $.validator.messages[rule.method], "<strong>Warning: No message defined for " + element.name + "</strong>"),
                theregex = /\$?\{(\d+)\}/g;

            if (typeof message === "function") {
              message = message.call(this, rule.parameters, element);
            } else if (theregex.test(message)) {
              message = $.validator.format(message.replace(theregex, "{$1}"), rule.parameters);
            }

            return message;
          },
          formatAndAdd: function formatAndAdd(element, rule) {
            var message = this.defaultMessage(element, rule);
            this.errorList.push({
              message: message,
              element: element,
              method: rule.method
            });
            this.errorMap[element.name] = message;
            this.submitted[element.name] = message;
          },
          addWrapper: function addWrapper(toToggle) {
            if (this.settings.wrapper) {
              toToggle = toToggle.add(toToggle.parent(this.settings.wrapper));
            }

            return toToggle;
          },
          defaultShowErrors: function defaultShowErrors() {
            var i, elements, error;

            for (i = 0; this.errorList[i]; i++) {
              error = this.errorList[i];

              if (this.settings.highlight) {
                this.settings.highlight.call(this, error.element, this.settings.errorClass, this.settings.validClass);
              }

              this.showLabel(error.element, error.message);
            }

            if (this.errorList.length) {
              this.toShow = this.toShow.add(this.containers);
            }

            if (this.settings.success) {
              for (i = 0; this.successList[i]; i++) {
                this.showLabel(this.successList[i]);
              }
            }

            if (this.settings.unhighlight) {
              for (i = 0, elements = this.validElements(); elements[i]; i++) {
                this.settings.unhighlight.call(this, elements[i], this.settings.errorClass, this.settings.validClass);
              }
            }

            this.toHide = this.toHide.not(this.toShow);
            this.hideErrors();
            this.addWrapper(this.toShow).show();
          },
          validElements: function validElements() {
            return this.currentElements.not(this.invalidElements());
          },
          invalidElements: function invalidElements() {
            return $(this.errorList).map(function () {
              return this.element;
            });
          },
          showLabel: function showLabel(element, message) {
            var place,
                group,
                errorID,
                v,
                error = this.errorsFor(element),
                elementID = this.idOrName(element),
                describedBy = $(element).attr("aria-describedby");

            if (error.length) {
              // Refresh error/success class
              error.removeClass(this.settings.validClass).addClass(this.settings.errorClass); // Replace message on existing label

              error.html(message);
            } else {
              // Create error element
              error = $("<" + this.settings.errorElement + ">").attr("id", elementID + "-error").addClass(this.settings.errorClass).html(message || ""); // Maintain reference to the element to be placed into the DOM

              place = error;

              if (this.settings.wrapper) {
                // Make sure the element is visible, even in IE
                // actually showing the wrapped element is handled elsewhere
                place = error.hide().show().wrap("<" + this.settings.wrapper + "/>").parent();
              }

              if (this.labelContainer.length) {
                this.labelContainer.append(place);
              } else if (this.settings.errorPlacement) {
                this.settings.errorPlacement.call(this, place, $(element));
              } else {
                place.insertAfter(element);
              } // Link error back to the element


              if (error.is("label")) {
                // If the error is a label, then associate using 'for'
                error.attr("for", elementID); // If the element is not a child of an associated label, then it's necessary
                // to explicitly apply aria-describedby
              } else if (error.parents("label[for='" + this.escapeCssMeta(elementID) + "']").length === 0) {
                errorID = error.attr("id"); // Respect existing non-error aria-describedby

                if (!describedBy) {
                  describedBy = errorID;
                } else if (!describedBy.match(new RegExp("\\b" + this.escapeCssMeta(errorID) + "\\b"))) {
                  // Add to end of list if not already present
                  describedBy += " " + errorID;
                }

                $(element).attr("aria-describedby", describedBy); // If this element is grouped, then assign to all elements in the same group

                group = this.groups[element.name];

                if (group) {
                  v = this;
                  $.each(v.groups, function (name, testgroup) {
                    if (testgroup === group) {
                      $("[name='" + v.escapeCssMeta(name) + "']", v.currentForm).attr("aria-describedby", error.attr("id"));
                    }
                  });
                }
              }
            }

            if (!message && this.settings.success) {
              error.text("");

              if (typeof this.settings.success === "string") {
                error.addClass(this.settings.success);
              } else {
                this.settings.success(error, element);
              }
            }

            this.toShow = this.toShow.add(error);
          },
          errorsFor: function errorsFor(element) {
            var name = this.escapeCssMeta(this.idOrName(element)),
                describer = $(element).attr("aria-describedby"),
                selector = "label[for='" + name + "'], label[for='" + name + "'] *"; // 'aria-describedby' should directly reference the error element

            if (describer) {
              selector = selector + ", #" + this.escapeCssMeta(describer).replace(/\s+/g, ", #");
            }

            return this.errors().filter(selector);
          },
          // See https://api.jquery.com/category/selectors/, for CSS
          // meta-characters that should be escaped in order to be used with JQuery
          // as a literal part of a name/id or any selector.
          escapeCssMeta: function escapeCssMeta(string) {
            if (string === undefined) {
              return "";
            }

            return string.replace(/([\\!"#$%&'()*+,./:;<=>?@\[\]^`{|}~])/g, "\\$1");
          },
          idOrName: function idOrName(element) {
            return this.groups[element.name] || (this.checkable(element) ? element.name : element.id || element.name);
          },
          validationTargetFor: function validationTargetFor(element) {
            // If radio/checkbox, validate first element in group instead
            if (this.checkable(element)) {
              element = this.findByName(element.name);
            } // Always apply ignore filter


            return $(element).not(this.settings.ignore)[0];
          },
          checkable: function checkable(element) {
            return /radio|checkbox/i.test(element.type);
          },
          findByName: function findByName(name) {
            return $(this.currentForm).find("[name='" + this.escapeCssMeta(name) + "']");
          },
          getLength: function getLength(value, element) {
            switch (element.nodeName.toLowerCase()) {
              case "select":
                return $("option:selected", element).length;

              case "input":
                if (this.checkable(element)) {
                  return this.findByName(element.name).filter(":checked").length;
                }

            }

            return value.length;
          },
          depend: function depend(param, element) {
            return this.dependTypes[_typeof(param)] ? this.dependTypes[_typeof(param)](param, element) : true;
          },
          dependTypes: {
            "boolean": function boolean(param) {
              return param;
            },
            "string": function string(param, element) {
              return !!$(param, element.form).length;
            },
            "function": function _function(param, element) {
              return param(element);
            }
          },
          optional: function optional(element) {
            var val = this.elementValue(element);
            return !$.validator.methods.required.call(this, val, element) && "dependency-mismatch";
          },
          startRequest: function startRequest(element) {
            if (!this.pending[element.name]) {
              this.pendingRequest++;
              $(element).addClass(this.settings.pendingClass);
              this.pending[element.name] = true;
            }
          },
          stopRequest: function stopRequest(element, valid) {
            this.pendingRequest--; // Sometimes synchronization fails, make sure pendingRequest is never < 0

            if (this.pendingRequest < 0) {
              this.pendingRequest = 0;
            }

            delete this.pending[element.name];
            $(element).removeClass(this.settings.pendingClass);

            if (valid && this.pendingRequest === 0 && this.formSubmitted && this.form() && this.pendingRequest === 0) {
              $(this.currentForm).trigger("submit"); // Remove the hidden input that was used as a replacement for the
              // missing submit button. The hidden input is added by `handle()`
              // to ensure that the value of the used submit button is passed on
              // for scripted submits triggered by this method

              if (this.submitButton) {
                $("input:hidden[name='" + this.submitButton.name + "']", this.currentForm).remove();
              }

              this.formSubmitted = false;
            } else if (!valid && this.pendingRequest === 0 && this.formSubmitted) {
              $(this.currentForm).triggerHandler("invalid-form", [this]);
              this.formSubmitted = false;
            }
          },
          previousValue: function previousValue(element, method) {
            method = typeof method === "string" && method || "remote";
            return $.data(element, "previousValue") || $.data(element, "previousValue", {
              old: null,
              valid: true,
              message: this.defaultMessage(element, {
                method: method
              })
            });
          },
          // Cleans up all forms and elements, removes validator-specific events
          destroy: function destroy() {
            this.resetForm();
            $(this.currentForm).off(".validate").removeData("validator").find(".validate-equalTo-blur").off(".validate-equalTo").removeClass("validate-equalTo-blur").find(".validate-lessThan-blur").off(".validate-lessThan").removeClass("validate-lessThan-blur").find(".validate-lessThanEqual-blur").off(".validate-lessThanEqual").removeClass("validate-lessThanEqual-blur").find(".validate-greaterThanEqual-blur").off(".validate-greaterThanEqual").removeClass("validate-greaterThanEqual-blur").find(".validate-greaterThan-blur").off(".validate-greaterThan").removeClass("validate-greaterThan-blur");
          }
        },
        classRuleSettings: {
          required: {
            required: true
          },
          email: {
            email: true
          },
          url: {
            url: true
          },
          date: {
            date: true
          },
          dateISO: {
            dateISO: true
          },
          number: {
            number: true
          },
          digits: {
            digits: true
          },
          creditcard: {
            creditcard: true
          }
        },
        addClassRules: function addClassRules(className, rules) {
          if (className.constructor === String) {
            this.classRuleSettings[className] = rules;
          } else {
            $.extend(this.classRuleSettings, className);
          }
        },
        classRules: function classRules(element) {
          var rules = {},
              classes = $(element).attr("class");

          if (classes) {
            $.each(classes.split(" "), function () {
              if (this in $.validator.classRuleSettings) {
                $.extend(rules, $.validator.classRuleSettings[this]);
              }
            });
          }

          return rules;
        },
        normalizeAttributeRule: function normalizeAttributeRule(rules, type, method, value) {
          // Convert the value to a number for number inputs, and for text for backwards compability
          // allows type="date" and others to be compared as strings
          if (/min|max|step/.test(method) && (type === null || /number|range|text/.test(type))) {
            value = Number(value); // Support Opera Mini, which returns NaN for undefined minlength

            if (isNaN(value)) {
              value = undefined;
            }
          }

          if (value || value === 0) {
            rules[method] = value;
          } else if (type === method && type !== "range") {
            // Exception: the jquery validate 'range' method
            // does not test for the html5 'range' type
            rules[type === "date" ? "dateISO" : method] = true;
          }
        },
        attributeRules: function attributeRules(element) {
          var rules = {},
              $element = $(element),
              type = element.getAttribute("type"),
              method,
              value;

          for (method in $.validator.methods) {
            // Support for <input required> in both html5 and older browsers
            if (method === "required") {
              value = element.getAttribute(method); // Some browsers return an empty string for the required attribute
              // and non-HTML5 browsers might have required="" markup

              if (value === "") {
                value = true;
              } // Force non-HTML5 browsers to return bool


              value = !!value;
            } else {
              value = $element.attr(method);
            }

            this.normalizeAttributeRule(rules, type, method, value);
          } // 'maxlength' may be returned as -1, 2147483647 ( IE ) and 524288 ( safari ) for text inputs


          if (rules.maxlength && /-1|2147483647|524288/.test(rules.maxlength)) {
            delete rules.maxlength;
          }

          return rules;
        },
        dataRules: function dataRules(element) {
          var rules = {},
              $element = $(element),
              type = element.getAttribute("type"),
              method,
              value;

          for (method in $.validator.methods) {
            value = $element.data("rule" + method.charAt(0).toUpperCase() + method.substring(1).toLowerCase()); // Cast empty attributes like `data-rule-required` to `true`

            if (value === "") {
              value = true;
            }

            this.normalizeAttributeRule(rules, type, method, value);
          }

          return rules;
        },
        staticRules: function staticRules(element) {
          var rules = {},
              validator = $.data(element.form, "validator");

          if (validator.settings.rules) {
            rules = $.validator.normalizeRule(validator.settings.rules[element.name]) || {};
          }

          return rules;
        },
        normalizeRules: function normalizeRules(rules, element) {
          // Handle dependency check
          $.each(rules, function (prop, val) {
            // Ignore rule when param is explicitly false, eg. required:false
            if (val === false) {
              delete rules[prop];
              return;
            }

            if (val.param || val.depends) {
              var keepRule = true;

              switch (_typeof(val.depends)) {
                case "string":
                  keepRule = !!$(val.depends, element.form).length;
                  break;

                case "function":
                  keepRule = val.depends.call(element, element);
                  break;
              }

              if (keepRule) {
                rules[prop] = val.param !== undefined ? val.param : true;
              } else {
                $.data(element.form, "validator").resetElements($(element));
                delete rules[prop];
              }
            }
          }); // Evaluate parameters

          $.each(rules, function (rule, parameter) {
            rules[rule] = typeof parameter === "function" && rule !== "normalizer" ? parameter(element) : parameter;
          }); // Clean number parameters

          $.each(["minlength", "maxlength"], function () {
            if (rules[this]) {
              rules[this] = Number(rules[this]);
            }
          });
          $.each(["rangelength", "range"], function () {
            var parts;

            if (rules[this]) {
              if (Array.isArray(rules[this])) {
                rules[this] = [Number(rules[this][0]), Number(rules[this][1])];
              } else if (typeof rules[this] === "string") {
                parts = rules[this].replace(/[\[\]]/g, "").split(/[\s,]+/);
                rules[this] = [Number(parts[0]), Number(parts[1])];
              }
            }
          });

          if ($.validator.autoCreateRanges) {
            // Auto-create ranges
            if (rules.min != null && rules.max != null) {
              rules.range = [rules.min, rules.max];
              delete rules.min;
              delete rules.max;
            }

            if (rules.minlength != null && rules.maxlength != null) {
              rules.rangelength = [rules.minlength, rules.maxlength];
              delete rules.minlength;
              delete rules.maxlength;
            }
          }

          return rules;
        },
        // Converts a simple string to a {string: true} rule, e.g., "required" to {required:true}
        normalizeRule: function normalizeRule(data) {
          if (typeof data === "string") {
            var transformed = {};
            $.each(data.split(/\s/), function () {
              transformed[this] = true;
            });
            data = transformed;
          }

          return data;
        },
        // https://jqueryvalidation.org/jQuery.validator.addMethod/
        addMethod: function addMethod(name, method, message) {
          $.validator.methods[name] = method;
          $.validator.messages[name] = message !== undefined ? message : $.validator.messages[name];

          if (method.length < 3) {
            $.validator.addClassRules(name, $.validator.normalizeRule(name));
          }
        },
        // https://jqueryvalidation.org/jQuery.validator.methods/
        methods: {
          // https://jqueryvalidation.org/required-method/
          required: function required(value, element, param) {
            // Check if dependency is met
            if (!this.depend(param, element)) {
              return "dependency-mismatch";
            }

            if (element.nodeName.toLowerCase() === "select") {
              // Could be an array for select-multiple or a string, both are fine this way
              var val = $(element).val();
              return val && val.length > 0;
            }

            if (this.checkable(element)) {
              return this.getLength(value, element) > 0;
            }

            return value !== undefined && value !== null && value.length > 0;
          },
          // https://jqueryvalidation.org/email-method/
          email: function email(value, element) {
            // From https://html.spec.whatwg.org/multipage/forms.html#valid-e-mail-address
            // Retrieved 2014-01-14
            // If you have a problem with this implementation, report a bug against the above spec
            // Or use custom methods to implement your own email validation
            return this.optional(element) || /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(value);
          },
          // https://jqueryvalidation.org/url-method/
          url: function url(value, element) {
            // Copyright (c) 2010-2013 Diego Perini, MIT licensed
            // https://gist.github.com/dperini/729294
            // see also https://mathiasbynens.be/demo/url-regex
            // modified to allow protocol-relative URLs
            return this.optional(element) || /^(?:(?:(?:https?|ftp):)?\/\/)(?:(?:[^\]\[?\/<~#`!@$^&*()+=}|:";',>{ ]|%[0-9A-Fa-f]{2})+(?::(?:[^\]\[?\/<~#`!@$^&*()+=}|:";',>{ ]|%[0-9A-Fa-f]{2})*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z0-9\u00a1-\uffff][a-z0-9\u00a1-\uffff_-]{0,62})?[a-z0-9\u00a1-\uffff]\.)+(?:[a-z\u00a1-\uffff]{2,}\.?))(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(value);
          },
          // https://jqueryvalidation.org/date-method/
          date: function () {
            var called = false;
            return function (value, element) {
              if (!called) {
                called = true;

                if (this.settings.debug && window.console) {
                  console.warn("The `date` method is deprecated and will be removed in version '2.0.0'.\n" + "Please don't use it, since it relies on the Date constructor, which\n" + "behaves very differently across browsers and locales. Use `dateISO`\n" + "instead or one of the locale specific methods in `localizations/`\n" + "and `additional-methods.js`.");
                }
              }

              return this.optional(element) || !/Invalid|NaN/.test(new Date(value).toString());
            };
          }(),
          // https://jqueryvalidation.org/dateISO-method/
          dateISO: function dateISO(value, element) {
            return this.optional(element) || /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$/.test(value);
          },
          // https://jqueryvalidation.org/number-method/
          number: function number(value, element) {
            return this.optional(element) || /^(?:-?\d+|-?\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test(value);
          },
          // https://jqueryvalidation.org/digits-method/
          digits: function digits(value, element) {
            return this.optional(element) || /^\d+$/.test(value);
          },
          // https://jqueryvalidation.org/minlength-method/
          minlength: function minlength(value, element, param) {
            var length = Array.isArray(value) ? value.length : this.getLength(value, element);
            return this.optional(element) || length >= param;
          },
          // https://jqueryvalidation.org/maxlength-method/
          maxlength: function maxlength(value, element, param) {
            var length = Array.isArray(value) ? value.length : this.getLength(value, element);
            return this.optional(element) || length <= param;
          },
          // https://jqueryvalidation.org/rangelength-method/
          rangelength: function rangelength(value, element, param) {
            var length = Array.isArray(value) ? value.length : this.getLength(value, element);
            return this.optional(element) || length >= param[0] && length <= param[1];
          },
          // https://jqueryvalidation.org/min-method/
          min: function min(value, element, param) {
            return this.optional(element) || value >= param;
          },
          // https://jqueryvalidation.org/max-method/
          max: function max(value, element, param) {
            return this.optional(element) || value <= param;
          },
          // https://jqueryvalidation.org/range-method/
          range: function range(value, element, param) {
            return this.optional(element) || value >= param[0] && value <= param[1];
          },
          // https://jqueryvalidation.org/step-method/
          step: function step(value, element, param) {
            var type = $(element).attr("type"),
                errorMessage = "Step attribute on input type " + type + " is not supported.",
                supportedTypes = ["text", "number", "range"],
                re = new RegExp("\\b" + type + "\\b"),
                notSupported = type && !re.test(supportedTypes.join()),
                decimalPlaces = function decimalPlaces(num) {
              var match = ("" + num).match(/(?:\.(\d+))?$/);

              if (!match) {
                return 0;
              } // Number of digits right of decimal point.


              return match[1] ? match[1].length : 0;
            },
                toInt = function toInt(num) {
              return Math.round(num * Math.pow(10, decimals));
            },
                valid = true,
                decimals; // Works only for text, number and range input types
            // TODO find a way to support input types date, datetime, datetime-local, month, time and week


            if (notSupported) {
              throw new Error(errorMessage);
            }

            decimals = decimalPlaces(param); // Value can't have too many decimals

            if (decimalPlaces(value) > decimals || toInt(value) % toInt(param) !== 0) {
              valid = false;
            }

            return this.optional(element) || valid;
          },
          // https://jqueryvalidation.org/equalTo-method/
          equalTo: function equalTo(value, element, param) {
            // Bind to the blur event of the target in order to revalidate whenever the target field is updated
            var target = $(param);

            if (this.settings.onfocusout && target.not(".validate-equalTo-blur").length) {
              target.addClass("validate-equalTo-blur").on("blur.validate-equalTo", function () {
                $(element).valid();
              });
            }

            return value === target.val();
          },
          // https://jqueryvalidation.org/remote-method/
          remote: function remote(value, element, param, method) {
            if (this.optional(element)) {
              return "dependency-mismatch";
            }

            method = typeof method === "string" && method || "remote";
            var previous = this.previousValue(element, method),
                validator,
                data,
                optionDataString;

            if (!this.settings.messages[element.name]) {
              this.settings.messages[element.name] = {};
            }

            previous.originalMessage = previous.originalMessage || this.settings.messages[element.name][method];
            this.settings.messages[element.name][method] = previous.message;
            param = typeof param === "string" && {
              url: param
            } || param;
            optionDataString = $.param($.extend({
              data: value
            }, param.data));

            if (previous.old === optionDataString) {
              return previous.valid;
            }

            previous.old = optionDataString;
            validator = this;
            this.startRequest(element);
            data = {};
            data[element.name] = value;
            $.ajax($.extend(true, {
              mode: "abort",
              port: "validate" + element.name,
              dataType: "json",
              data: data,
              context: validator.currentForm,
              success: function success(response) {
                var valid = response === true || response === "true",
                    errors,
                    message,
                    submitted;
                validator.settings.messages[element.name][method] = previous.originalMessage;

                if (valid) {
                  submitted = validator.formSubmitted;
                  validator.resetInternals();
                  validator.toHide = validator.errorsFor(element);
                  validator.formSubmitted = submitted;
                  validator.successList.push(element);
                  validator.invalid[element.name] = false;
                  validator.showErrors();
                } else {
                  errors = {};
                  message = response || validator.defaultMessage(element, {
                    method: method,
                    parameters: value
                  });
                  errors[element.name] = previous.message = message;
                  validator.invalid[element.name] = true;
                  validator.showErrors(errors);
                }

                previous.valid = valid;
                validator.stopRequest(element, valid);
              }
            }, param));
            return "pending";
          }
        }
      }); // Ajax mode: abort
      // usage: $.ajax({ mode: "abort"[, port: "uniqueport"]});
      // if mode:"abort" is used, the previous request on that port (port can be undefined) is aborted via XMLHttpRequest.abort()

      var pendingRequests = {},
          ajax; // Use a prefilter if available (1.5+)

      if ($.ajaxPrefilter) {
        $.ajaxPrefilter(function (settings, _, xhr) {
          var port = settings.port;

          if (settings.mode === "abort") {
            if (pendingRequests[port]) {
              pendingRequests[port].abort();
            }

            pendingRequests[port] = xhr;
          }
        });
      } else {
        // Proxy ajax
        ajax = $.ajax;

        $.ajax = function (settings) {
          var mode = ("mode" in settings ? settings : $.ajaxSettings).mode,
              port = ("port" in settings ? settings : $.ajaxSettings).port;

          if (mode === "abort") {
            if (pendingRequests[port]) {
              pendingRequests[port].abort();
            }

            pendingRequests[port] = ajax.apply(this, arguments);
            return pendingRequests[port];
          }

          return ajax.apply(this, arguments);
        };
      }

      return $;
    });
  });

  // Copyright (c) 2013 Keith Perhac @ DelfiNet (http://delfi-net.com)
  //
  // Based on the AutoRuby library created by:
  // Copyright (c) 2005-2008 spinelz.org (http://script.spinelz.org/)
  //
  // Permission is hereby granted, free of charge, to any person obtaining
  // a copy of this software and associated documentation files (the
  // "Software"), to deal in the Software without restriction, including
  // without limitation the rights to use, copy, modify, merge, publish,
  // distribute, sublicense, and/or sell copies of the Software, and to
  // permit persons to whom the Software is furnished to do so, subject to
  // the following conditions:
  //
  // The above copyright notice and this permission notice shall be
  // included in all copies or substantial portions of the Software.
  //
  // THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
  // EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
  // MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
  // NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
  // LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
  // OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
  // WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
  (function ($) {
    $.fn.autoKana = function (element1, element2, passedOptions) {
      var options = $.extend({
        'katakana': false
      }, passedOptions);
      var kana_extraction_pattern = new RegExp('[^ 　ぁあ-んー]', 'g');
      var kana_compacting_pattern = new RegExp('[ぁぃぅぇぉっゃゅょ]', 'g');
      var elName,
          elKana,
          active = false,
          timer = null,
          flagConvert = true,
          input,
          values,
          ignoreString,
          baseKana;
      elName = $(element1);
      elKana = $(element2);
      active = true;

      _stateClear();

      elName.blur(_eventBlur);
      elName.focus(_eventFocus);
      elName.keydown(_eventKeyDown);

      function _checkConvert(new_values) {
        if (!flagConvert) {
          if (Math.abs(values.length - new_values.length) > 1) {
            var tmp_values = new_values.join('').replace(kana_compacting_pattern, '').split('');

            if (Math.abs(values.length - tmp_values.length) > 1) {
              _stateConvert();
            }
          } else {
            if (values.length == input.length && values.join('') != input) {
              if (input.match(kana_extraction_pattern)) {
                _stateConvert();
              }
            }
          }
        }
      }

      function _checkValue() {
        var new_input, new_values;
        new_input = elName.val();

        if (new_input == '') {
          _stateClear();

          _setKana();
        } else {
          new_input = _removeString(new_input);

          if (input == new_input) {
            return;
          } else {
            input = new_input;

            if (!flagConvert) {
              new_values = new_input.replace(kana_extraction_pattern, '').split('');

              _checkConvert(new_values);

              _setKana(new_values);
            }
          }
        }
      }

      function _clearInterval() {
        clearInterval(timer);
      }

      function _eventBlur(event) {
        _clearInterval();
      }

      function _eventFocus(event) {
        _stateInput();

        _setInterval();
      }

      function _eventKeyDown(event) {
        if (flagConvert) {
          _stateInput();
        }
      }

      function _isHiragana(chara) {
        return chara >= 12353 && chara <= 12435 || chara == 12445 || chara == 12446;
      }

      function _removeString(new_input) {
        if (new_input.indexOf(ignoreString) !== -1) {
          return new_input.replace(ignoreString, '');
        } else {
          var i, ignoreArray, inputArray;
          ignoreArray = ignoreString.split('');
          inputArray = new_input.split('');

          for (i = 0; i < ignoreArray.length; i++) {
            if (ignoreArray[i] == inputArray[i]) {
              inputArray[i] = '';
            }
          }

          return inputArray.join('');
        }
      }

      function _setInterval() {
        timer = setInterval(_checkValue, 30);
      }

      function _setKana(new_values) {
        if (!flagConvert) {
          if (new_values) {
            values = new_values;
          }

          if (active) {
            var _val = _toKatakana(baseKana + values.join(''));

            elKana.val(_val);
          }
        }
      }

      function _stateClear() {
        baseKana = '';
        flagConvert = false;
        ignoreString = '';
        input = '';
        values = [];
      }

      function _stateInput() {
        baseKana = elKana.val();
        flagConvert = false;
        ignoreString = elName.val();
      }

      function _stateConvert() {
        baseKana = baseKana + values.join('');
        flagConvert = true;
        values = [];
      }

      function _toKatakana(src) {
        if (options.katakana) {
          var c, i, str;
          str = new String();

          for (i = 0; i < src.length; i++) {
            c = src.charCodeAt(i);

            if (_isHiragana(c)) {
              str += String.fromCharCode(c + 96);
            } else {
              str += src.charAt(i);
            }
          }

          return str;
        } else {
          return src;
        }
      }
    };
  })(jQuery);

  /*
   * jQuery.zip2addr
   *
   * Copyright 2010, Kotaro Kokubo a.k.a kotarok kotaro@nodot.jp
   * Dual licensed under the MIT or GPL Version 2 licenses.
   *
   * https://github.com/kotarok/jQuery.zip2addr
   *
   * Depends:
   *	jQuery 1.4 or above
   */

  $__default['default'].fn.zip2addr = function (target) {
    var c = {
      api: '//www.google.com/transliterate?langpair=ja-Hira|ja&jsonp=?',
      prefectureToken: '(東京都|道|府|県)',
      zipDelimiter: '-'
    };
    var cache = $__default['default'].fn.zip2addr.cache;

    var getAddr = function getAddr(zip, callback) {
      $__default['default'].getJSON(c.api, {
        'text': zip
      }, function (json) {
        if (RegExp(c.prefectureToken).test(json[0][1][0])) {
          callback(json[0][1][0].replace(RegExp('(.*?' + c.prefectureToken + ')(.+)'), function (a, b, c, d) {
            return [b, d];
          }));
        }
      });
    };

    var fillAddr = function () {
      if (_typeof(target) == 'object' && target.pref) {
        return function (addr) {
          var addrs = addr.split(',');

          if (addrs) {
            if (!RegExp(addrs[1]).test($__default['default'](target.addr).val())) {
              $__default['default'](target.pref).val(addrs[0]);
              $__default['default'](target.addr).val(addrs[1]);
            }
          } else if (!RegExp(addrs[1]).test($__default['default'](target.addr).val())) {
            $__default['default'](target.pref).add(target.addr).val('');
          }
        };
      } else {
        return function (addr) {
          var addrStr = addr.replace(',', '');
          var addrField = target.addr || target;

          if (addrStr) {
            if (!RegExp(addrStr).test($__default['default'](addrField).val())) {
              $__default['default'](addrField).val(addrStr);
            }
          } else if (!RegExp(addrStr).test($__default['default'](addrField).val())) {
            $__default['default'](addrField).val('');
          }
        };
      }
    }(); //From http://liosk.blog103.fc2.com/blog-entry-72.html


    var fascii2ascii = function () {
      var pattern = /[\uFF01-\uFF5E]/g,
          replace = function replace(m) {
        return String.fromCharCode(m.charCodeAt() - 0xFEE0);
      };

      return function (s) {
        return s.replace(pattern, replace);
      };
    }();

    var check = function check(_val) {
      var val = fascii2ascii(_val).replace(/\D/g, '');

      if (val.length == 7) {
        if (cache[val] == undefined) {
          getAddr(val.replace(/(\d\d\d)(\d\d\d\d)/, '$1-$2'), function (json) {
            cache[val] = json;
            fillAddr(json);
          });
        } else {
          fillAddr(cache[val]);
        }
      }
    };

    this.each(function () {
      var elem = $__default['default'](this);

      if (_typeof(target) == 'object' && target.zip2) {
        elem.add($__default['default'](target.zip2)).bind('keyup.zip2addr change.zip2addr', function () {
          check(elem.val() + '' + $__default['default'](target.zip2).val());
        }).bind('blur.zip2addr', function () {
          $__default['default'](this).val(function () {
            return fascii2ascii($__default['default'](this).val());
          });
        });
      } else {
        elem.bind('keyup.zip2addr change.zip2addr', function () {
          check(elem.val());
        }).bind('blur.zip2addr', function () {
          $__default['default'](this).val(function () {
            return fascii2ascii($__default['default'](this).val()).replace(/\D/g, '').replace(/(\d\d\d)(\d\d\d\d)/, '$1' + c.zipDelimiter + '$2');
          });
        });
      }
    });
    return this;
  };

  $__default['default'].fn.zip2addr.cache = {};

  /**
   * 電話番号のバリデーションロジック
   *
   * @param {string} value
   * @param {HTMLInputElement|HTMLTextAreaElement|HTMLSelectElement} elem
   * @returns {boolean}
   */
  function is_email(value, elem) {
    if (this.optional(elem)) {
      return true;
    }

    return /^[a-zA-Z0-9!$&*.=^`|~#%'+\/?_{}-]+@([a-zA-Z0-9_-]+\.)+[a-zA-Z]{2,}$/.test(value.trim());
  }
  /**
   * 電話番号のバリデーションロジック
   *
   * @param {string} value
   * @param {HTMLInputElement|HTMLTextAreaElement|HTMLSelectElement} elem
   * @returns {boolean}
   */

  function is_tel(value, elem) {
    if (this.optional(elem)) {
      return true;
    }

    value = value.trim().replace(/[０-９]/g, function (s) {
      return String.fromCharCode(s.charCodeAt(0) - 0xFEE0); // 全角数字を半角数字に変換
    });
    return /^0(\d{1}[-(]?\d{4}|\d{2}[-(]?\d{3}|\d{3}[-(]?\d{2}|\d{4}[-(]?\d{1})[-)]?\d{4}$/.test(value) || /^\d{1,4}\-?\d{4}$/.test(value) || /^0[5789]0[-(]?\d{4}[-)]?\d{4}$/.test(value) || /^0120[-(]?\d{3}[-)]?\d{3}$/.test(value);
  }
  /**
   * 郵便番号のバリデーションロジック
   *
   * @param {string} value
   * @param {HTMLInputElement|HTMLTextAreaElement|HTMLSelectElement} elem
   * @returns {boolean}
   */

  function is_zip(value, elem) {
    if (this.optional(elem)) {
      return true;
    }

    value = value.trim().replace(/[０-９]/g, function (s) {
      return String.fromCharCode(s.charCodeAt(0) - 0xFEE0); // 全角数字を半角数字に変換
    }).replace(/[^\d]/g, '');
    return /^\d{7}$/.test(value);
  }
  /**
   * 住所のバリデーションロジック
   *
   * @param {string} value
   * @param {HTMLInputElement|HTMLTextAreaElement|HTMLSelectElement} elem
   * @returns {boolean}
   */

  function is_address(value, elem) {
    if (this.optional(elem)) {
      return true;
    }

    return /[^\x00-\x7e]/.test(value.trim());
  }
  /**
   * @param {any} value
   * @param {HTMLInputElement|HTMLTextAreaElement|HTMLSelectElement} elem
   * @param {[number,string]} options
   */

  function require_from_group(value, elem, options) {
    var _options = _slicedToArray(options, 2),
        num_min_completed = _options[0],
        selector = _options[1];

    var $fields = $(selector, elem.form);
    var $fieldsFirst = $fields.eq(0);
    var validator = $fieldsFirst.data('valid_req_grp') ? $fieldsFirst.data('valid_req_grp') : $.extend({}, this);
    return $fields.filter(function () {
      return validator.elementValue(this);
    }).length >= num_min_completed;
  }
  /**
   * @param {any} value
   * @param {HTMLInputElement|HTMLTextAreaElement|HTMLSelectElement} elem
   * @param {string|RegExp} pattern
   */

  function match_pattern(value, elem, pattern) {
    if (this.optional(elem)) {
      return true;
    }

    if (typeof pattern === 'string') {
      pattern = new RegExp("^(?:".concat(pattern, ")$"));
    }

    return pattern.test(value);
  }

  var messages = {
    required: '入力必須項目です',
    remote: 'このフィールドを修正してください',
    email: '正しいEメールアドレスを入力してください',
    url: '正しい URL を入力してください',
    date: '有効な日付を入力してください',
    dateISO: '有効な日付を入力してください',
    number: '有効な数字を入力してください',
    digits: '数字のみを入力してください',
    creditcard: '有効なクレジットカード番号を入力してください',
    equalTo: '同じ値をもう一度入力してください',
    extension: '有効な拡張子を含む値を入力してください',
    maxlength: $__default['default'].validator.format('{0} 文字以内で入力してください'),
    minlength: $__default['default'].validator.format('{0} 文字以上で入力してください'),
    rangelength: $__default['default'].validator.format('{0} 文字から {1} 文字までの値を入力してください'),
    range: $__default['default'].validator.format('{0} から {1} までの値を入力してください'),
    step: $__default['default'].validator.format('{0} の倍数を入力してください'),
    max: $__default['default'].validator.format('{0} 以下の値を入力してください'),
    min: $__default['default'].validator.format('{0} 以上の値を入力してください'),
    tel: '正しい電話番号を入力してください',
    zip: '正しい郵便番号を入力してください',
    address: 'ひらがな・カタカナ・漢字のいずれかで1文字以上入力してください'
  };

  var interval = 20;
  var timer;
  function update_submit_disabled(elem_either_updater, validator, first_time) {
    clearTimeout(timer);
    timer = setTimeout(function () {
      var submitted = $__default['default'].extend({}, validator.submitted);
      validator.checkForm();
      var num_remaining = Object.keys(validator.errorMap).length;
      validator.submitted = submitted;

      if (typeof elem_either_updater == 'function') {
        var updater = elem_either_updater;
        updater(num_remaining);
      } else if (elem_either_updater) {
        // elem_either_updater が CSS セレクター文字列, HTMLElement, jQuery Object の場合を想定
        var $elem = $__default['default'](elem_either_updater);
        $elem.prop('disabled', num_remaining != 0);
      }
    }, interval);
  }

  var interval$1 = 20;
  var timer$1;
  function update_num_remaining(elem_either_updater, validator, first_time) {
    clearTimeout(timer$1);
    timer$1 = setTimeout(function () {
      var submitted = $__default['default'].extend({}, validator.submitted);
      validator.checkForm();
      var num_remaining = Object.keys(validator.errorMap).length;
      validator.submitted = submitted;

      if (typeof elem_either_updater == 'function') {
        var updater = elem_either_updater;
        updater(num_remaining);
      } else if (elem_either_updater) {
        // elem_either_updater が CSS セレクター文字列, HTMLElement, jQuery Object の場合を想定
        var $elem = $__default['default'](elem_either_updater);
        $elem.text(num_remaining);
      }
    }, interval$1);
  }

  function recaptcha($form, $submit, sitekey) {
    $submit = $__default['default']($submit);
    var $script_api = $__default['default']('<script></script');
    $script_api.attr('src', '//www.google.com/recaptcha/api.js?render=' + sitekey);
    $__default['default']('body').append($script_api);
    $submit.on('click', function (event) {
      event.preventDefault();
      grecaptcha.ready(function () {
        grecaptcha.execute(sitekey, {
          action: 'submit'
        }) // TODO: 集計に役立つように適切な action を設定する https://developers.google.com/recaptcha/docs/v3?hl=en#actions
        .then(function (token) {
          $form.append('<input type="hidden" name="__recaptcha_token" value="' + token + '">').ready(function () {
            $form.trigger('submit');
          });
        });
      });
    });
  }

  $__default['default'].validator.addMethod('email', is_email);
  $__default['default'].validator.addMethod('tel', is_tel);
  $__default['default'].validator.addMethod('zip', is_zip);
  $__default['default'].validator.addMethod('address', is_address);
  $__default['default'].validator.addMethod('require_from_group', require_from_group);
  $__default['default'].validator.addMethod('pattern', match_pattern);
  $__default['default'].extend($__default['default'].validator.messages, messages);

  $__default['default'].fn.qms3_form = function (options) {
    // 適切なタグに対してスクリプトが起動しているかチェックする
    if (!this.get(0)) {
      console.error('⚠️ qms3_form() の起動に失敗しました\n\t対象タグが選択されていません');
      console.error('⚠️ qms3_form() は必ず <form> タグに対して実行してください\n\t起動スクリプトのセレクターに間違いがないか確認してください');
      return;
    }

    var tag_name = this.get(0).tagName.toLowerCase();

    if (tag_name != 'form') {
      console.error("\u26A0\uFE0F <".concat(tag_name, "> \u306B\u5BFE\u3057\u3066 qms3_form() \u3092\u5B9F\u884C\u3057\u3088\u3046\u3068\u3057\u3066\u3044\u307E\u3059"));
      console.error('⚠️ qms3_form() は必ず <form> タグに対して実行してください\n\t起動スクリプトのセレクターに間違いがないか確認してください');
      return;
    } // ======================================================================== //


    var $form = this; // ======================================================================== //
    // オプションのデフォルト値設定

    options = $__default['default'].extend({
      validation_groups: {},
      validation_rules: {},
      validation_messages: {},
      autokana: [],
      zip2addr: {},
      datepicker: {},
      button_submit: null,
      num_remaining: null,
      recaptcha_sitekey: null
    }, options);
    options.datepicker = $__default['default'].extend({
      __default: {}
    }, options.datepicker);
    options.datepicker.__default = $__default['default'].extend({
      firstDay: 1,
      minDate: 0
    }, options.datepicker.__default); // ======================================================================== //
    // バリデーション初期化

    var validator = this.validate({
      debug: false,
      groups: options.validation_groups,
      rules: options.validation_rules,
      messages: options.validation_messages,
      errorPlacement: function errorPlacement(err, elem) {
        $__default['default'](elem).parents('.brick-form__row-body').find('.brick-form__message').append(err);
      }
    }); // ======================================================================== //
    // 残り項目数表示

    if (options.num_remaining) {
      $form.on('mouseup keyup change', function () {
        update_num_remaining(options.num_remaining, validator);
      });
      update_num_remaining(options.num_remaining, validator);
    } // ======================================================================== //
    // 必須項目が全て入力されている場合に限ってサブミットボタンを押下可能にする


    if (options.button_submit) {
      $form.on('mouseup keyup change', function () {
        update_submit_disabled(options.button_submit, validator);
      });
      update_submit_disabled(options.button_submit, validator);
    } // ======================================================================== //
    // 二重送信防止
    // サブミットボタンが押下されたタイミングで { pointer-events: none; } を設定する


    if (options.button_submit) {
      // NOTE:
      // ここで unload イベントに空のリスナーをセットするのは history.back() で
      // ページを戻ったときにも JavaScript の処理が再実行されるようにするため
      //
      // history.back() で戻ったときに必ず css({ 'pointer-events': 'auto' }) が
      // 実行されなければいけない
      // さもなくばサブミットボタンに style="pointer-events: none" が設定されたまま
      // になり、Submit ボタンが押下できなくなる
      window.addEventListener('unload', function () {});
      var $button_submit = $__default['default'](options.button_submit);
      $button_submit.css({
        'pointer-events': 'auto'
      });
      $__default['default'](options.button_submit).one('click', function (event) {
        $button_submit.css({
          'pointer-events': 'none'
        });
      });
    } // ======================================================================== //
    // ふりがな自動入力


    for (var input in options.autokana) {
      if (!options.autokana.hasOwnProperty(input)) {
        continue;
      }

      var rule = options.autokana[input];
      var $input = $__default['default']("input[name='".concat(input, "']"));
      var $target = $__default['default']("input[name='".concat(rule.target, "']"));

      if ($input.length == 0 || $target.length == 0) {
        continue;
      }

      $__default['default'].fn.autoKana($input, $target, {
        katakana: rule.katakana
      });
    } // ======================================================================== //
    // 郵便番号から住所の自動反映


    for (var _input in options.zip2addr) {
      if (!options.zip2addr.hasOwnProperty(_input)) {
        continue;
      }

      var target = options.zip2addr[_input];

      if (target.length == 2) {
        $__default['default']("input[name='".concat(_input, "']")).zip2addr({
          pref: "[name='".concat(target[0], "']"),
          addr: "input[name='".concat(target[1], "']")
        });
      } else if (target.length == 1) {
        $__default['default']("input[name='".concat(_input, "']")).zip2addr("input[name='".concat(target[0], "']"));
      }
    } // ======================================================================== //
    // デイトピッカー


    {
      var defaultOption = $__default['default'].extend({}, $__default['default'].datepicker.regional['ja'], options.datepicker.__default);
      $__default['default'].datepicker.setDefaults(defaultOption);
      $__default['default']('[data-datepicker]').each(function () {
        var $this = $__default['default'](this);
        var name = $this.attr('name');
        var option = options.datepicker[name] || {};
        var onSelect = option.onSelect || defaultOption.onSelect;

        option.onSelect = function (dateStr, datepicker) {
          if (onSelect) {
            onSelect.call(this, dateStr, datepicker);
          } // 日付を選択したタイミングで「残り項目数表示」と「サブミットボタン有効化判定」を
          // 実行する


          validator.element(this);
          $form.trigger('change');
        };

        var onClose = option.onClose || defaultOption.onClose;
        option.onClose = function (dateStr, datepicker) {
          if (onClose) {
            onClose.call(this, dateStr, datepicker);
          } // タッチデバイスでキーボードを出さないようにするために設定した readonly 属性を
          // ここで外す


          $__default['default'](this).prop('readonly', false);
        }, $this.datepicker(option); // <input> にフォーカスしたときに入力履歴がサジェストされるのを抑止する
        //
        // 普通に <input autocomplete="off"> と書いてしまうと、history.back() で
        // 戻ってきたときに入力欄がリセットされてしまう
        // それではユーザービリティが低くなるので、<input> にフォーカスしたときだけ
        // autocomplete を無効にする
        //
        // @see {@link https://www.kop.co.jp/blog/website/872/}

        $this.on('focus', function (event) {
          $this.attr({
            autocomplete: 'off'
          });
        });
        $this.on('blur', function (event) {
          $this.removeAttr('autocomplete');
        }); // タッチデバイスで <input> にフォーカスしたときキーボードを出さないようにする
        // jQuery UI Datepicker によって日付選択用のカレンダーが表示されるので、
        // キーボードを出す必要は無い

        $this.on('touchstart', function (event) {
          var $input = $__default['default'](this);
          $input.prop('readonly', true);
          $input.trigger('blur');
        });
      });
    } // ======================================================================== //
    // <textarea> 高さ自動調整

    $form.find('textarea').on('change keyup keydown paste cut', function () {
      var $textarea = $__default['default'](this);

      while ($textarea.outerHeight() < this.scrollHeight) {
        $textarea.height($textarea.height() + 10);
      }
    }); // ======================================================================== //
    // パスワードの 表示/非表示 切り替え

    var $units = $form.find('.form__field-unit-password');
    $units.each(function () {
      var $unit = $__default['default'](this);
      var $input = $unit.find('input');
      var $button = $unit.find('button');
      var visibility = false;
      $button.on('click', function (event) {
        event.preventDefault();

        if (visibility) {
          $input.attr('type', 'text');
          $unit.removeClass('brick-form__field-unit-password--hidden');
          $unit.addClass('brick-form__field-unit-password--shown');
          $button.attr('title', 'パスワードを隠す');
          $button.attr('aria-label', 'パスワードを隠す');
        } else {
          $input.attr('type', 'password');
          $unit.removeClass('brick-form__field-unit-password--shown');
          $unit.addClass('brick-form__field-unit-password--hidden');
          $button.attr('title', 'パスワードを表示');
          $button.attr('aria-label', 'パスワードを表示');
        }

        visibility = !visibility;
      });
    }); // ======================================================================== //
    // reCAPTCHA 設定

    if (options.recaptcha_sitekey) {
      recaptcha($form, options.button_submit, options.recaptcha_sitekey);
    }
  };

}(jQuery));
