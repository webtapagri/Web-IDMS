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
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
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
</script>