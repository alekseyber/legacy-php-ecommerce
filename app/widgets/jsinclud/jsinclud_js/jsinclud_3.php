            (function () {
                window.vkAsyncInit = function () {
                    $(document).trigger('vk-loaded');
                };
                setTimeout(function () {
                    var el = document.createElement("script");
                    el.type = "text/javascript";
                    el.src = "https://vk.com/js/api/openapi.js";
                    el.async = true;
                    document.getElementById("vk_api_transport").appendChild(el);
                }, 0);
                window.fbAsyncInit = function () {
                    $(document).trigger('fb-loaded');
                };
                (function (d, s, id) {
                    var js, fjs = d.getElementsByTagName(s)[0];
                    if (d.getElementById(id)) {
                        return;
                    }
                    js = d.createElement(s);
                    js.id = id;
                    js.src = "https://connect.facebook.net/en_US/sdk.js";
                    fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));
            }());