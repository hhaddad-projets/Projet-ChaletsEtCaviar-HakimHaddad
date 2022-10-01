(function(jQuery, $, window){
// INIT CODE
// INIT CODE

// Use the window to make sure it is in the main scope, I do not trust IE
window.ASL = window.ASL || {};

window.ASL.getScope = function() {
    /**
     * Explanation:
     * If the sript is scoped, the first argument is always passed in a localized jQuery
     * variable, while the actual parameter can be aspjQuery or jQuery (or anything) as well.
     */
    if (typeof jQuery !== "undefined") return jQuery;

    // The code should never reach this point, but sometimes magic happens (unloaded or undefined jQuery??)
    // .. I am almost positive at this point this is going to fail anyways, but worth a try.
    if (typeof window[ASL.js_scope] !== "undefined")
        return window[ASL.js_scope];
    else
        return eval(ASL.js_scope);
};

window.ASL.initialized = false;

// Call this function if you need to initialize an instance that is printed after an AJAX call
// Calling without an argument initializes all instances found.
window.ASL.initialize = function(id) {
    // this here is either window.ASL or window._ASL
    var _this = this;

    // Some weird ajax loader problem prevention
    if ( typeof _this.getScope == 'undefined' )
        return false;

    // Yeah I could use $ or jQuery as the scope variable, but I like to avoid magical errors..
    var scope = _this.getScope();
    var selector = ".asl_init_data";

    if ((typeof ASL_INSTANCES != "undefined") && Object.keys(ASL_INSTANCES).length > 0) {
        scope.each(ASL_INSTANCES, function(k, v){
            if ( typeof v == "undefined" ) return false;
            // Return if it is already initialized
            if ( scope("#ajaxsearchlite" + k).hasClass("hasASL") )
                return false;
            else
                scope("#ajaxsearchlite" + k).addClass("hasASL");

            return scope("#ajaxsearchlite" + k).ajaxsearchlite(v);
        });
    } else {
        if (typeof id !== 'undefined')
            selector = "div[id*=asl_init_id_" + id + "]";

        function b64_utf8_decode(utftext) {
            var string = "";
            var i = 0;
            var c = c1 = c2 = 0;

            while ( i < utftext.length ) {

                c = utftext.charCodeAt(i);

                if (c < 128) {
                    string += String.fromCharCode(c);
                    i++;
                }
                else if((c > 191) && (c < 224)) {
                    c2 = utftext.charCodeAt(i+1);
                    string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                    i += 2;
                }
                else {
                    c2 = utftext.charCodeAt(i+1);
                    c3 = utftext.charCodeAt(i+2);
                    string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                    i += 3;
                }

            }

            return string;
        }

        function b64_decode(input) {
            var output = "";
            var chr1, chr2, chr3;
            var enc1, enc2, enc3, enc4;
            var i = 0;
            var _keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";

            input = input.replace(/[^A-Za-z0-9\+\/\=]/g, "");

            while (i < input.length) {

                enc1 = _keyStr.indexOf(input.charAt(i++));
                enc2 = _keyStr.indexOf(input.charAt(i++));
                enc3 = _keyStr.indexOf(input.charAt(i++));
                enc4 = _keyStr.indexOf(input.charAt(i++));

                chr1 = (enc1 << 2) | (enc2 >> 4);
                chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                chr3 = ((enc3 & 3) << 6) | enc4;

                output = output + String.fromCharCode(chr1);

                if (enc3 != 64) {
                    output = output + String.fromCharCode(chr2);
                }
                if (enc4 != 64) {
                    output = output + String.fromCharCode(chr3);
                }

            }
            output = b64_utf8_decode(output);
            return output;
        }

        /**
         * Getting around inline script declarations with this solution.
         * So these new, invisible divs contains a JSON object with the parameters.
         * Parse all of them and do the declaration.
         */
        scope(selector).each(function (index, value) {
            var rid = scope(this).attr('id').match(/^asl_init_id_(.*)/)[1];

            var jsonData = scope(this).data("asldata");
            if (typeof jsonData === "undefined") return false;

            jsonData = b64_decode(jsonData);
            if (typeof jsonData === "undefined" || jsonData == "") return false;

            var args = JSON.parse(jsonData);

            return scope("#ajaxsearchlite" + rid).ajaxsearchlite(args);
        });
    }

    _this.initialized = true;
};

window.ASL.ready = function() {
    var _this = this;
    var scope = _this.getScope();
    var t = null;

    scope(document).ready(function () {
        _this.initialize();
    });

    // Redundancy for safety
    scope(window).on('load', function () {
        // It should be initialized at this point, but you never know..
        if ( !_this.initialized ) {
            _this.initialize();
            console.log("ASL initialized via window.load");
        }
    });

    // DOM tree modification detection to re-initialize automatically if enabled
    if (typeof(ASL.detect_ajax) != "undefined" && ASL.detect_ajax == 1) {
        scope("body").bind("DOMSubtreeModified", function() {
            clearTimeout(t);
            t = setTimeout(function(){
                _this.initialize();
            }, 500);
        });
    }
};

// Make a reference clone, just in case if an ajax page loader decides to override
window._ASL = ASL;

// Call the ready method
window._ASL.ready();
})(asljQuery, asljQuery, window);