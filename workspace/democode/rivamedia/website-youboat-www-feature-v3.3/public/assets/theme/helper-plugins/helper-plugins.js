/* Debounce Resize */
(function(a){var d=a.event,b,c;b=d.special.debouncedresize={setup:function(){a(this).on("resize",b.handler)},teardown:function(){a(this).off("resize",b.handler)},handler:function(a,f){var g=this,h=arguments,e=function(){a.type="debouncedresize";d.dispatch.apply(g,h)};c&&clearTimeout(c);f?e():c=setTimeout(e,b.threshold)},threshold:150}})(jQuery);

/*
 * Isotope custom layout mode that extends masonry in order to work with percentage-sized columns
 */
if ('function' === typeof $.Isotope) {
    (function (window, $) {
        $.extend($.Isotope.prototype, {
            _sloppyMasonryReset: function () {
                // layout-specific props
                var containerSize = this.element.width(),
                    segmentSize = this.options.sloppyMasonry && this.options.sloppyMasonry.columnWidth ||
                            // or use the size of the first item, i.e. outerWidth
                        this.$filteredAtoms.outerWidth(true) ||
                            // if there's no items, use size of container
                        containerSize;
                this.sloppyMasonry = {
                    cols: Math.round(containerSize / segmentSize),
                    columnWidth: segmentSize
                };
                var i = this.sloppyMasonry.cols;
                this.sloppyMasonry.colYs = [];
                while (i--) {
                    this.sloppyMasonry.colYs.push(0);
                }
            },
            _sloppyMasonryLayout: function ($elems) {
                var instance = this, props = instance.sloppyMasonry;
                $elems.each(function () {
                    var $this = $(this),
                    // how many columns does this brick span
                        colSpan = Math.round($this.outerWidth(true) / props.columnWidth);
                    colSpan = Math.min(colSpan, props.cols);
                    if (colSpan === 1) {
                        // if brick spans only one column,
                        // just like singleMode
                        instance._sloppyMasonryPlaceBrick($this, props.colYs);
                    } else {
                        // brick spans more than one column
                        // how many different places could
                        // this brick fit horizontally
                        var groupCount = props.cols + 1 - colSpan, groupY = [], groupColY, i;

                        // for each group potential
                        // horizontal position
                        for (i = 0; i < groupCount; i++) {
                            // make an array of colY values
                            // for that one group
                            groupColY = props.colYs.slice(i, i + colSpan);
                            // and get the max value of the
                            // array
                            groupY[i] = Math.max.apply(Math, groupColY);
                        }

                        instance._sloppyMasonryPlaceBrick($this, groupY);
                    }
                });
            },
            _sloppyMasonryPlaceBrick: function ($brick, setY) {
                // get the minimum Y value from the columns
                var minimumY = Math.min.apply(Math, setY), shortCol = 0;

                // Find index of short column, the first from the left
                for (var i = 0, len = setY.length; i < len; i++) {
                    if (setY[i] === minimumY) {
                        shortCol = i;
                        break;
                    }
                }

                // position the brick
                var x = this.sloppyMasonry.columnWidth * shortCol, y = minimumY;
                this._pushPosition($brick, x, y);

                // apply setHeight to necessary columns
                var setHeight = minimumY + $brick.outerHeight(true), setSpan = this.sloppyMasonry.cols + 1 - len;
                for (i = 0; i < setSpan; i++) {
                    this.sloppyMasonry.colYs[shortCol + i] = setHeight;
                }

            },
            _sloppyMasonryGetContainerSize: function () {
                var containerHeight = Math.max.apply(Math, this.sloppyMasonry.colYs);
                return {
                    height: containerHeight
                };
            },
            _sloppyMasonryResizeChanged: function () {
                return true;
            }
        });
    })(this, this.jQuery);
}

// usage: log('inside coolFunc', this, arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function f(){ log.history = log.history || []; log.history.push(arguments); if(this.console) { var args = arguments, newarr; args.callee = args.callee.caller; newarr = [].slice.call(args); if (typeof console.log === 'object') log.apply.call(console.log, console, newarr); else console.log.apply(console, newarr);}};

// make it safe to use console.log always
(function(a){function b(){}for(var c="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,markTimeline,profile,profileEnd,time,timeEnd,trace,warn".split(","),d;!!(d=c.pop());){a[d]=a[d]||b;}})
(function(){try{console.log();return window.console;}catch(a){return (window.console={});}}());

/**
 * jQuery.browser.mobile (http://detectmobilebrowser.com/)
 *
 * jQuery.browser.mobile will be true if the browser is a mobile device
 *
 **/
(function(a){(jQuery.browser=jQuery.browser||{}).mobile=/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))})(navigator.userAgent||navigator.vendor||window.opera);