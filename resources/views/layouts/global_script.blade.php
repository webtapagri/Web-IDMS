<script>
var light = $('body');
function HoldOn(light=$('body')){
	$(light).block({
		message: 'Silahkan menunggu <i class="icon-spinner spinner"></i>',
		overlayCSS: {
			backgroundColor: '#1B2024',
			opacity: 0.85,
			cursor: 'wait'
		},
		css: {
			border: 0,
			padding: 0,
			backgroundColor: 'none',
			color: '#fff',
			width:'100%',
			paddingTop:'10px',
			fontSize:'15pt',
		}
	});
}
function HoldOff(light=$('body')){
	$(light).unblock();
}
function formRequiredMark(){
	$('form').find('label').each((k,v)=>{
		if(!$(v).hasClass('norequired')){
			$(v).append(' <span class="text-danger">*</span>')
		}
	})
}

//bootstrap 4 form validation
(function() {
  'use strict';
  window.addEventListener('load', function() {
    var forms = document.getElementsByClassName('needs-validation');
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();

//cache $.ajax respon
var localCache = {
    
    timeout: 30000,
    
    data: {},
    remove: function (url) {
        delete localCache.data[url];
    },
    exist: function (url) {
        return !!localCache.data[url] && ((new Date().getTime() - localCache.data[url]._) < localCache.timeout);
    },
    get: function (url) {
        // console.log('Getting in cache for url' + url);
        return localCache.data[url].data;
    },
    set: function (url, cachedData, callback) {
        localCache.remove(url);
        localCache.data[url] = {
            _: new Date().getTime(),
            data: cachedData
        };
        if ($.isFunction(callback)) callback(cachedData);
    }
};

$.ajaxPrefilter(function (options, originalOptions, jqXHR) {
    if (options.cache) {
        var success = originalOptions.success || $.noop,
            url = originalOptions.url;
		options.cache = false;
        options.beforeSend = function () {
			$(options.param.elTarget).html(options.param.elValue)
			HoldOn()
            if (localCache.exist(url)) {
                var rsp = localCache.get(url)
				success(rsp.responseJSON)
				HoldOff()
                return false;
            }
            return true;
        };
        options.complete = function (data, textStatus) {
            localCache.set(url, data, success);
			HoldOff()
        };
    }
});
</script>