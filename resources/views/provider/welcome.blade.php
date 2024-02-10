<div class="row">
	<div class="container error-container col-sm-12">
	 <div class="error-panel panel panel-default plain animated bounceIn">
	    <div class=panel-body>
	       <div class=page-header>
	          <h1 class="text-center mb25">Hi, <small>{{Auth::guard('provider')->user()->name}}</small></h1>
	       </div>
	       <h2 class="text-center mt30 mb30">Welcome to Admin Panel</h2>
	     </div>
	 </div>
	</div>
</div>
