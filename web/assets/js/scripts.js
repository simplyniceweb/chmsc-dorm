jQuery(function($){

	var func = {
	    activeLink: function() {
	    	$('.sidebar li.active').removeClass('active');
	        var url = window.location;
	        var element = $('.sidebar ul.nav a').filter(function() {
	            if (this.href.indexOf('#') > -1) {
	                return false;
	            }

	            return this.href == url || url.href.indexOf(this.href) == 0;
	        }).parent();

	        if (element.is('li')) {
	            element.addClass('active');
	        }

	        if ($('.sidebar li.active').length > 1) {
	        	$('.sidebar li.active:first-child').removeClass('active');
	        }
	    }
	};

	func.activeLink();
});