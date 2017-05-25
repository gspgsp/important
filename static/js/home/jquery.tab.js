$.fn.extend({
    Tab: function (a, b) {
        var closest = this;
        $(this).find(a.div + ":gt(0)").hide();
		var Event = a.Event? a.Event : "click";
        $(this).find(a.tab).each(function (i) {
            $(this).bind(Event, function () {
                $(closest).find(a.tab).not($(this).addClass(a.tabCss)).removeClass(a.tabCss);
                var d = undefined;
                if (a.div) {
                    d = $(closest).find(a.div).stop().hide()[i];
                    $(d).show();
                }
                if (b) { b(i, this, d); }
				return false;
            });
        }).first().addClass(a.tabCss);
    }
});