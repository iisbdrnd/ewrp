(function(){
	var appUrl = {
		currentUrl: function(){
			return '<?php echo Request::url('/'); ?>';
		},
		siteUrl: function(){
			return '<?php echo (empty($prefix)) ? url('/') : url($prefix); ?>';
		},
		baseUrl: function(action){
			return '<?php echo url('/'); ?>' + action;
		},
		getSiteAction: function(action){
			return '<?php echo (empty($prefix)) ? url('/') : url($prefix); ?>' + action;
		},
		nodeUrl: function(action){
			return '<?php echo (empty($nodeUrl)) ? url('/') : $nodeUrl; ?>';
		},
		defaultPage: function(){
			return '<?php echo $defaultPage; ?>';
		},
		loginPage: function(){
			return '<?php echo (!empty($loginRoute)) ? route($loginRoute) : route("login"); ?>';
		}
	};
	window.appUrl = appUrl;
}());