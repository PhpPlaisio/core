/*jslint browser: true, vars: true, indent: 2, maxlen: 120 */
/*global require */
/*global requirejs */
/*global define */

//----------------------------------------------------------------------------------------------------------------------
requirejs.config({
  baseUrl: '/js',
  paths: {
    'jquery': 'jquery/jquery',
    'jquery.cookie': 'js-cookie/js.cookie',
    'js-cookie': 'js-cookie/js.cookie',
    'jquery-ui': 'jquery-ui/jquery-ui.min',
    'jquery-ui.js': 'jquery-ui/jquery-ui.min.js',
    'tinyMCE': 'tinymce/tinymce.min'
  },
  shim: {
    tinyMCE: {
      exports: 'tinyMCE',
      init: function () {
        "use strict";
        this.tinyMCE.DOM.events.domLoaded = true;
        return this.tinyMCE;
      }
    }
  }
});

//----------------------------------------------------------------------------------------------------------------------
require(["SetBased/Abc/Core/Page/CorePage"]);
