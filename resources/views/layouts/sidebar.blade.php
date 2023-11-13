
<div class="m-grid m-grid--hor m-grid--root m-page">
   @include('layouts.header')
<div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body custom-nav">
   <!-- BEGIN: Left Aside -->
   <button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
      <i class="la la-close"></i>
   </button>
   <div id="m_aside_left" class="m-grid__item  m-aside-left  m-aside-left--skin-dark ">
      <!-- BEGIN: Aside Menu -->
      <div 
      id="m_ver_menu" 
      class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark custom-sidenar-nav" 
      data-menu-vertical="true"
      data-menu-scrollable="false" data-menu-dropdown-timeout="500"  
      >
      <ul class="m-menu__nav  m-menu__nav--dropdown-submenu-arrow side-bar-menu">
         @if(App\Helpers\Helper::acl_privilege('request_listing'))
            <li class="m-menu__item home-menu m-menu__item--submenu {{ (Request::url()==route('showScreen'))?'active':'' }}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
               <a  href="{{ Route('showScreen') }}" class="m-menu__link m-menu__toggle">
                  <i class="m-menu__link-icon">
                     <i class="far fa-list-alt" title="Request Listing" style="color: #fff;font-size: 21px;" aria-hidden="true"></i>
                  </i>
                  <span class="m-menu__link-text">
                     Request Listing
                  </span>
               </a>
            </li>
         @endif
         @if(App\Helpers\Helper::acl_privilege('user_management'))
            <li class="m-menu__item home-menu m-menu__item--submenu {{ (Request::url()==route('showUsers'))?'active':'' }}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
               <a  href="{{ Route('showUsers') }}" class="m-menu__link m-menu__toggle">
                  <i class="m-menu__link-icon">
                     <i class="fas fa-users-cog" title="User Management" style="color: #fff;font-size: inherit;" aria-hidden="true"></i>
                  </i>
                  <span class="m-menu__link-text">
                     User Management
                  </span>
               </a>
            </li>
         @endif
         @if(App\Helpers\Helper::acl_privilege('reports'))
            <li class="m-menu__item home-menu m-menu__item--submenu {{ (Request::url()==route('show_reports'))?'active':'' }}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
               <a  href="{{ Route('show_reports') }}" class="m-menu__link m-menu__toggle">
                  <i class="m-menu__link-icon">
                     <img title="Reports" src="{{ asset('public/default/images/excel.png') }}" >
                  </i>
                  <span class="m-menu__link-text">
                     Reports
                  </span>
               </a>
            </li>
         @endif
         @if(App\Helpers\Helper::acl_privilege('image_compression'))
            <li class="m-menu__item home-menu m-menu__item--submenu {{ (Request::url()==route('uploadForm'))?'active':'' }}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
               <a  href="{{ Route('uploadForm') }}" class="m-menu__link m-menu__toggle">
                  <i class="m-menu__link-icon">
                     <i class="fas fa-camera" title="Image Compression" style="color: #fff;font-size: inherit;" aria-hidden="true"></i>
                  </i>
                  <span class="m-menu__link-text">
                     Image Compression
                  </span>
               </a>
            </li>
         @endif
         @if(App\Helpers\Helper::acl_privilege('stocks_listing'))
            <li class="m-menu__item home-menu m-menu__item--submenu {{ (Request::url()==route('showStocks'))?'active':'' }}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
               <a  href="{{ Route('showStocks') }}" class="m-menu__link m-menu__toggle">
                  <i class="m-menu__link-icon">
                     <i class="fas fa-list-ol" title="Stock Listing" style="color: #fff;font-size: inherit;" aria-hidden="true"></i>
                  </i>
                  <span class="m-menu__link-text">
                     Stocks
                  </span>
               </a>
            </li>
         @endif
         @if(App\Helpers\Helper::acl_privilege('custom_configs'))
            <li class="m-menu__item home-menu m-menu__item--submenu {{ (Request::url()==route('showConfigScreen'))?'active':'' }}" aria-haspopup="true"  data-menu-submenu-toggle="hover">
               <a  href="{{ Route('showConfigScreen') }}" class="m-menu__link m-menu__toggle">
                  <i class="m-menu__link-icon">
                     <i class="fa fa-wrench" title="Custom Configurations" style="color: #fff;font-size: inherit;" aria-hidden="true"></i>
                  </i>
                  <span class="m-menu__link-text">
                     Custom Configurations
                  </span>
               </a>
            </li>
         @endif
      </ul>
   </div>
</div>