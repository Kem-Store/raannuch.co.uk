var Touno = {
    DEBUG: function () { return (location.hostname==='localhost') ?  true : false; },
    Browser: {
        Chrome : window.navigator.userAgent.indexOf('Chrome') || window.navigator.userAgent.indexOf('Firefox')>-1 ? true : false,
        IE : false
    },
    Timestamp : parseInt((new Date().getTime() / 1000)),
    Storage: function(key, setValue) {
        var getValue = null;
        try {
            if(setValue === undefined) {
                getValue = window.localStorage.getItem(key);
            } else {
                window.localStorage.setItem(key, setValue.toString());
            }
        } catch (e) { /* Browser not support localStorage function. */ }
        return getValue;
    },
    StorageClear: function(){
        try {$.each(window.localStorage, function(key,value){ window.localStorage.removeItem(key); }); } catch (e) { /* Browser not support localStorage function. */ }
    },
    Popup: function (url, target) {
        var d = new Date(), onHandler = 'onHandlerWindows_' + (d.getTime()), typeDownload = false, jWin = $(parent.window)
        var w = Math.round((screen.width || jWin.width()) * 0.86, 0), h = Math.round((screen.height || jWin.height()) * 0.82, 0);
        var x = +Math.round(((screen.width || jWin.width()) - w) / 2, 0), y = Math.round(((screen.height || jWin.height()) - h) / 2, 0);
        var f = $('<form>', {
            "id": "onHandler",
            "name": "onHandler",
            "method": (typeof (url) === "object") ? 'post' : 'get',
            "target": (target == undefined) ? onHandler : target
        });

        if (typeof (url) === "object") {
            $.each(url, function (key, value) { f.append($('<input name="' + key + '" id="' + key + '" type="hidden" value="' + value + '"/>')); });
            url = url.url;
        }
        var win;
        typeDownload = (/[.]/.exec(url)) ? /(.pdf|.html|.asp|.aspx)/.exec("." + /[^?]+/.exec(/[^.]+$/.exec(url))) ? false : true : false;
        f.attr('action', (url.indexOf('http://') > -1) ? url : /*Touno.Path()+*/ url);
        f.appendTo(document.body).submit(function () {
            var handler = (target == undefined) ? (onHandler == undefined) ? "onHandlerWindows" : onHandler : target;
            try {
                if (target == undefined) win = window.open('about:blank', handler, 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=1', true);
                if (typeDownload) {
                    win.document.write('<style>html,body,table{font-size:11px;font-family:Segoe UI;margin:0px;padding:12px;background-color:#232323;}</style>');
                    win.document.write('<b style="font-size:22px;color:#f47735">SUCCESS EXPORT...</b><br><span style="color:#FFF">Please save file from server response</span>');
                    win.document.write('<script> setTimeout(function(){ window.close(); }, 30000); </script>');
                    w = 400; h = 200;
                    x = +Math.round(((screen.width || jWin.width()) - w) / 2, 0); y = Math.round(((screen.height || jWin.height()) - h) / 2, 0);
                }
                if (target == undefined) {
                    win.moveTo(x, y);
                    win.resizeTo(w, h);
                    win.document.title = "Please wait..."
                }
            } catch (err) { console.log(err); /*FIXED IE BROWSER*/ }
        }).submit().focus().remove();

    },
    Cookie: function(k,v){
        if (typeof(v)=='undefined') {
            var c = document.cookie.split(';'), f = false; k += '=';
            for (i in c) { if (c[i].indexOf(k) > -1) { n = $.trim(c[i]).replace(k, ''); f = true; break; } }
            return (f) ? n : '';
        } else if (typeof(k)!='undefined' && k!='' && k!=null) {
            /*var d = new Date(), n = d.toString();*/
            document.cookie = k+"="+v+";path=/;"
        }
    }
}