    <div class="fixed top-0 bottom-0 z-20 hidden lg:flex flex-col items-stretch shrink-0 bg-[--tw-page-bg] dark:bg-[--tw-page-bg-dark]" data-drawer="true" data-drawer-class="drawer drawer-start flex" data-drawer-enable="true|lg:false" id="sidebar">
     <div class="hidden lg:flex items-center justify-center shrink-0 pt-8 pb-3.5" id="sidebar_header">
      
      <a href="<?php echo $config['base_url']; ?>dashboard">
        <center>
         <img class="dark:hidden min-h-[42px]" src="<?php echo $config['base_url']; ?>t.php?d=/images/favicon.png" width="50px"/>
         <img class="hidden dark:block min-h-[42px]" src="<?php echo $config['base_url']; ?>t.php?d=/images/favicon.png" width="50px"/>
        </center>
      </a>
     </div>
     <div class="scrollable-y-hover grow gap-2.5 shrink-0 flex items-center pt-5 lg:pt-0 ps-3 pe-3 lg:pe-0 flex-col" data-scrollable="true" data-scrollable-dependencies="#sidebar_header,#sidebar_footer" data-scrollable-height="auto" data-scrollable-offset="80px" data-scrollable-wrappers="#sidebar_menu_wrapper" id="sidebar_menu_wrapper">
      <!-- Sidebar Menu -->
      <div class="menu flex flex-col gap-2.5 grow" data-menu="true" id="sidebar_menu">

<?php
  
  $menu1_name = array("Inventory", "Report");
  $menu1_link = array("inventory", "report");
  $menu1_icon = array("ki-cube-2", "ki-book");

  $menu2_name[0] = array("ECCT", "ECBS", "Report");
  $menu2_link[0] = array("inventory/inv_ecct", "inventory/inv_ecbs", "inventory/inv_report_needs");
  $menu2_icon[0] = array("ki-technology-1", "ki-technology-3", "ki-duotone ki-book-open");

  $menu2_name[1] = array("Rekap", "Statistik");
  $menu2_link[1] = array("laporan/rekap", "laporan/statistik");
  $menu2_icon[1] = array("ki-chart-bar", "ki-chart-pie");
  // $menu1_name = array("Inventory", "Report");
  // $menu1_link = array("inventory", "report");
  // $menu1_icon = array("ki-cube-2", "ki-book");

  // $menu2_name[0] = array("All Item", "ECCT", "ECBS", "Massive Input");
  // $menu2_link[0] = array("inventory/all_item", "inventory/inv_ecct", "inventory/inv_ecbs", "inventory/massive_input");
  // $menu2_icon[0] = array("ki-technology-2", "ki-technology-1", "ki-technology-3", "ki-technology-4");

  // $menu2_name[1] = array("Rekap", "Statistik");
  // $menu2_link[1] = array("laporan/rekap", "laporan/statistik");
  // $menu2_icon[1] = array("ki-chart-bar", "ki-chart-pie"); 
  
?>


  <?php for($m1=0;$m1<sizeof($menu1_name);$m1++){ ?>
    <?php if($config['access'][$menu1_link[$m1]]==true){ ?>
       <div class="menu-item" data-menu-item-offset="-10px, 14px" data-menu-item-overflow="true" data-menu-item-placement="right-start" data-menu-item-toggle="dropdown" data-menu-item-trigger="click|lg:hover">
        <div class="menu-link rounded-[9px] border border-transparent menu-item-here:border-gray-200 menu-item-here:bg-light menu-link-hover:bg-light menu-link-hover:border-gray-200 w-[62px] h-[60px] flex flex-col justify-center items-center gap-1 p-2 grow">
         <span class="menu-icon menu-item-here:text-primary menu-item-active:text-primary menu-link-hover:text-primary text-gray-600">
          <i class="ki-filled <?php echo $menu1_icon[$m1]; ?> text-1.5xl"></i>
         </span>
         <span class="menu-title menu-item-here:text-primary menu-item-active:text-primary menu-link-hover:text-primary font-medium text-xs text-gray-600">
          <?php echo $menu1_name[$m1]; ?>
         </span>
        </div>
        <div class="menu-default menu-dropdown gap-0.5 w-[220px] scrollable-y-auto lg:overflow-visible max-h-[50vh]">
         
         <?php for($m2=0;$m2<sizeof($menu2_name[$m1]);$m2++){ ?>
         <div class="menu-item">
          <a class="menu-link" href="<?php echo $config['base_url'].$menu2_link[$m1][$m2]; ?>">
            <span class="menu-icon">
             <i class="ki-filled <?php echo $menu2_icon[$m1][$m2]; ?>"></i>
            </span>
           <span class="menu-title"><?php echo $menu2_name[$m1][$m2]; ?></span>
          </a>
         </div>
         <?php } ?>

        </div>
       </div>
    <?php } ?>
  <?php } ?>




      </div>
       
      <!-- End of Sidebar Menu -->





     </div>
     <div class="flex flex-col gap-5 items-center shrink-0 pb-4" id="sidebar_footer">

      <?php $photo_link=$config['base_url']."images/admin/".$config['adm_id'].".jpg"; ?>

      <?php

        if(!$this->format_model->urlExist($photo_link)){
          $photo_link=$config['base_url']."images/admin/blank.jpg";
        }

      ?>

      <div class="menu" data-menu="true">
       <div class="menu-item" data-menu-item-offset="-20px, 28px" data-menu-item-overflow="true" data-menu-item-placement="right-end" data-menu-item-toggle="dropdown" data-menu-item-trigger="click|lg:click">
        <div class="menu-toggle btn btn-icon">
         <img alt="" class="size-8 justify-center rounded-lg border border-gray-500 shrink-0" src="<?php echo $photo_link; ?>">
         </img>
        </div>
        <div class="menu-dropdown menu-default light:border-gray-300 w-screen max-w-[250px]">
         <div class="flex items-center justify-between px-5 py-1.5 gap-1.5">
          <div class="flex items-center gap-2">
           <img alt="" class="size-9 rounded-full border-2 border-primary" src="<?php echo $photo_link; ?>">
            <div class="flex flex-col gap-1.5">
             <span class="text-sm text-gray-800 font-semibold leading-none">
              <?php echo $config['adm_fullname']; ?>
             </span>
             <?php if(false){ ?>
             <a class="text-xs text-gray-600 hover:text-primary font-medium leading-none" href="html/demo8/account/home/get-started.html">
              c.fisher@gmail.com
             </a>
             <?php } ?>
            </div>
           </img>
          </div>
          <span class="badge badge-xs badge-primary badge-outline">
           <?php echo $config['adm_access']; ?>
          </span>
         </div>
         <div class="menu-separator">
         </div>
         <div class="flex flex-col">
          <?php if(false){ ?>
          <div class="menu-item">
           <a class="menu-link" href="html/demo8/account/home/user-profile.html">
            <span class="menu-icon">
             <i class="ki-filled ki-profile-circle">
             </i>
            </span>
            <span class="menu-title">
             My Profile
            </span>
           </a>
          </div>
          <div class="menu-item" data-menu-item-offset="-50px, 0" data-menu-item-placement="left-start" data-menu-item-placement-rtl="right-start" data-menu-item-toggle="dropdown" data-menu-item-trigger="click|lg:hover">
           <div class="menu-link">
            <span class="menu-icon">
             <i class="ki-filled ki-setting-2">
             </i>
            </span>
            <span class="menu-title">
             My Account
            </span>
            <span class="menu-arrow">
             <i class="ki-filled ki-right text-3xs rtl:transform rtl:rotate-180">
             </i>
            </span>
           </div>
           <div class="menu-dropdown menu-default light:border-gray-300 w-full max-w-[220px]">
            <div class="menu-item">
             <a class="menu-link" href="html/demo8/account/home/user-profile.html">
              <span class="menu-icon">
               <i class="ki-filled ki-some-files">
               </i>
              </span>
              <span class="menu-title">
               My Profile
              </span>
             </a>
            </div>
            
            <div class="menu-separator">
            </div>
            <div class="menu-item">
             <a class="menu-link" href="html/demo8/account/security/overview.html">
              <span class="menu-icon">
               <i class="ki-filled ki-shield-tick">
               </i>
              </span>
              <span class="menu-title">
               Notifications
              </span>
              <label class="switch switch-sm">
               <input checked="" name="check" type="checkbox" value="1">
               </input>
              </label>
             </a>
            </div>
           </div>
          </div>
          <?php } ?>
          
          <div class="menu-item" data-menu-item-offset="-10px, 0" data-menu-item-placement="left-start" data-menu-item-toggle="dropdown" data-menu-item-trigger="click|lg:hover">
           <div class="menu-link">
            <span class="menu-icon">
             <i class="ki-filled ki-icon">
             </i>
            </span>
            <span class="menu-title">
             Language
            </span>
            <div class="flex items-center gap-1.5 rounded-md border border-gray-300 text-gray-600 p-1.5 text-2xs font-medium shrink-0">
             Indonesia
             <img alt="" class="inline-block size-3.5 rounded-full" src="<?php echo $config['base_url']; ?>assets/media/flags/indonesia.svg"/>
            </div>
           </div>
           <div class="menu-dropdown menu-default light:border-gray-300 w-full max-w-[170px]">
            <div class="menu-item active">
             <a class="menu-link h-10">
              <span class="menu-icon">
               <img alt="" class="inline-block size-4 rounded-full" src="<?php echo $config['base_url']; ?>assets/media/flags/indonesia.svg"/>
              </span>
              <span class="menu-title">
               Indonesia
              </span>
              <span class="menu-badge">
               <i class="ki-solid ki-check-circle text-success text-base">
               </i>
              </span>
             </a>
            </div>
            
            <div class="menu-item">
             <a class="menu-link h-10">
              <span class="menu-icon">
               <img alt="" class="inline-block size-4 rounded-full" src="<?php echo $config['base_url']; ?>assets/media/flags/united-states.svg"/>
              </span>
              <span class="menu-title">
               English
              </span>
             </a>
            </div>
            
            <div class="menu-item">
             <a class="menu-link h-10">
              <span class="menu-icon">
               <img alt="" class="inline-block size-4 rounded-full" src="<?php echo $config['base_url']; ?>assets/media/flags/japan.svg"/>
              </span>
              <span class="menu-title">
               Japanese
              </span>
             </a>
            </div>

           </div>
          </div>
         </div>
         <div class="menu-separator">
         </div>
         <div class="flex flex-col">
          <div class="menu-item mb-0.5">
           <div class="menu-link">
            <span class="menu-icon">
             <i class="ki-filled ki-moon">
             </i>
            </span>
            <span class="menu-title">
             Dark Mode
            </span>
            <label class="switch switch-sm">
             <input data-theme-state="dark" data-theme-toggle="true" name="check" type="checkbox" value="1">
             </input>
            </label>
           </div>
          </div>
          <div class="menu-item px-4 py-1.5">
           <a class="btn btn-sm btn-light justify-center" href="<?php echo $config['base_url']."login"; ?>">
            Log out
           </a>
          </div>
         </div>
        </div>
       </div>
      </div>
     </div>
    </div>
    <!-- End of Sidebar -->


    <!-- Main -->
    <div class="flex flex-col grow rounded-xl bg-[--tw-content-bg] dark:bg-[--tw-content-bg-dark] border border-gray-300 dark:border-gray-200 lg:ms-[--tw-sidebar-width] mt-0 lg:mt-5 m-5">
     <div class="flex flex-col grow lg:scrollable-y-auto lg:[scrollbar-width:auto] lg:light:[--tw-scrollbar-thumb-color:var(--tw-content-scrollbar-color)] pt-5" id="scrollable_content">
      <main class="grow" role="content">

        