!function(){var e;e=window.jQuery,wp.customize.bind("ready",(function(){function o(o){"off"!==o?e(".customize-control.-header-overlay").removeClass("-hide"):e(".customize-control.-header-overlay").addClass("-hide")}wp.customize("arkhe_settings[header_overlay]",(function(e){o(e.get()),e.bind((function(e){o(e)}))}))}))}();