	  <a href="#" class="logo">
        <img src="/public/img/logo.png" alt="Logo"/>
      </a>
      <div class="pull-right">
        <ul id="mini-nav" class="clearfix">
          <li class="list-box hidden-xs">
            <a href="#">
              <span class="text-white"><b>Welcome {{session('userEmail')}}</b></span> <br>
              <span class="text-white"><b>Role Type: {{session('typeUser')}}</b></span>
              </i>
            </a>
          </li>
          <li class="list-box user-profile">
            <a id="drop7" href="#" role="button" class="dropdown-toggle user-avtar" data-toggle="dropdown">
              <img src="{{URL::to('public/img/user5.png')}}" alt="Bluemoon User">
            </a>
            <ul class="dropdown-menu server-activity">
              <li>
                <p><i class="fa fa-cog text-info"></i> Account Settings</p>
              </li>
              <li>
                <a href="{{URL::route('bank.user.resetPassword')}}" style="color:#333; padding: 0px " ><p><i class="fa fa-cog text-info" ></i>Password Change</p></a>
              </li>
              <li>
                <p><i class="fa fa-fire text-warning"></i> Payment Details</p>
              </li>
              <li>
                <div class="demo-btn-group clearfix">
                  <a href="#" data-original-title="" title="">
                    <i class="fa fa-facebook fa-lg icon-rounded info-bg"></i>
                  </a>
                  <a href="#" data-original-title="" title="">
                    <i class="fa fa-twitter fa-lg icon-rounded twitter-bg"></i>
                  </a>
                  <a href="#" data-original-title="" title="">
                    <i class="fa fa-linkedin fa-lg icon-rounded linkedin-bg"></i>
                  </a>
                  <a href="#" data-original-title="" title="">
                    <i class="fa fa-pinterest fa-lg icon-rounded danger-bg"></i>
                  </a>
                  <a href="#" data-original-title="" title="">
                    <i class="fa fa-google-plus fa-lg icon-rounded success-bg"></i>
                  </a>
                </div>
              </li>
              <li>
                <div class="demo-btn-group clearfix">
                  <a href="/auth/logout" class="btn btn-danger">
                    Logout
                  </a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>