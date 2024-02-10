        <style>

        	.logo {
			    margin: 0 0 30%!important;
			}
			nav {
			
			    letter-spacing: 0px!important;
			}


	        .morph {
	            -webkit-transition: all 0.5s ease;
	            -moz-transition: all 0.5s ease;
	            -o-transition: all 0.5s ease;
	            -ms-transition: all 0.5s ease;
	            transition: all 0.5s ease;
	        }

	        .morph:hover {
	            border-radius: 0%;
	            -webkit-transform: rotate(360deg);
	            -moz-transform: rotate(360deg);
	            -o-transform: rotate(360deg);
	            -ms-transform: rotate(360deg);
	            transform: rotate(360deg);
	        }

	        .social-media {
			    /*margin: 65px 0 0!important;*/
			    margin: 20%px 0 0!important;
			}


			.navigation li.about {
			    margin-top: 20%!important;
			    margin-bottom: 4%!important;
			}
			.navigation li.view_finder {
			    margin-top: 20%!important;
			
			}

			.navigation li.ashram {
			    margin-top: 20%!important;
			}


	    </style>

        <nav>
			<div class="logo"><a href="{{route('intro')}}"><img src="{{url('public/uploads/menu_logo/'.$data->menu_logo)}}" alt="logo" /></a></div>
			
			<ul class="navigation">
				<li @if($menu == 'demo') class="active" @endif> <a href="{{route('demo')}}" />Showreel</a></li>
				<li @if($menu == 'narratives')class="active" @endif><a href="{{route('narratives')}}" />Narrative</a></li>
				<li @if($menu == 'commercials')class="active" @endif> <a href="{{route('commercials')}}" />Commercial</a></li>
				<li @if($menu == 'documentaries')class="active" @endif><a href="{{route('documentaries')}}" />Documentary</a></li>
			

				<li @if($menu == 'viewFinder')class="view_finder active" @endif class="view_finder"><a href="{{route('viewFinder')}}" />Frames</a></li>
			


				<li @if($menu == 'photography')class="active" @endif><a href="{{route('photography')}}" />Photography</a></li>
				<li @if($menu == 'visualpoetry')class="active" @endif> <a href="{{route('visualpoetry')}}" />Visual Poetry</a></li>

				{{-- <li @if($menu == 'sample')class="active" @endif> <a href="{{route('sample')}}" />Sample</a></li> --}}

				<li @if($menu == 'ashram' || $menu == 'ashramPhotography')class="ashram active" @endif class="ashram"><a href="{{route('ashram')}}" />Ashram</a></li>
				<li @if($menu == 'blog')class="active" @endif><a href="{{route('blog')}}" />Blog</a></li>
				
				<li @if($menu == 'about')class="about active" @endif class="about"><a href="{{route('about')}}" />About</a></li>
				<li @if($menu == 'contact')class="active" @endif><a href="{{route('contact')}}" />Contact</a></li>
				
			</ul>
			<div class="social-media text-right" style="letter-spacing:1px!important">
                @foreach($socialLink as $sLink)   
					<a href="{{$sLink->social_link}}" target="_blank" style="padding:0 -1px; font-size: 20px"><i class="{{$sLink->fa_icon}}"></i></a> 
				@endforeach 
				

          </div>
          <!-- <a href="{{url('public/uploads/updateCv/'.$cv->attachment)}}" target="_blank" style="padding:0 3px; font-size: 12px" >Curriculum Vitae<i class="fa fa-download"></i></a> -->

         
		</nav>