       <!-- Toolbar -->
       <div class="pb-5">
        <!-- Container -->
        <div class="container-fixed flex items-center justify-between flex-wrap gap-3">
         <div class="flex items-center flex-wrap gap-1 lg:gap-5">
          <h1 class="font-medium text-base text-gray-900" style="font-size:26px;">
           <?php echo ucwords($title_page); ?>
          </h1>
          <div class="flex items-center flex-wrap gap-1 text-sm" style="vertical-align: bottom;margin-top:10px;">
           <a class="text-gray-700 hover:text-primary" href="<?php echo $config['base_url']; ?>">Home</a>
           <span class="text-gray-400 text-sm">/</span>
           <a class="text-gray-700 hover:text-primary" href="<?php echo $config['base_url']; ?>history/all_history">History</a>
           <span class="text-gray-400 text-sm">/</span>
           <a class="text-gray-900 hover:text-primary" href="<?php echo $config['base_url']; ?>history/all_history">All History</a>
          </div>
         </div>

         <div class="flex items-center flex-wrap gap-1 lg:gap-5">
          <div class="menu menu-default flex flex-wrap justify-center gap-1.5 rounded-lg py-2">
           <div class="menu-item">
            <a class="menu-link" href="<?php echo $config['base_url']; ?>history/all_history">
             <span class="menu-icon">
              <i class="ki-filled ki-chart-simple"></i>
             </span>
             <span class="menu-title">All History</span>
            </a>
           </div>
           <div class="menu-item">
            <a class="menu-link" href="<?php echo $config['base_url']; ?>">
             <span class="menu-icon">
              <i class="ki-filled ki-shield-search"></i>
             </span>
             <span class="menu-title">ECCT</span>
            </a>
           </div>
           <div class="menu-item">
            <a class="menu-link" href="<?php echo $config['base_url']; ?>">
             <span class="menu-icon">
              <i class="ki-filled ki-rocket"></i>
             </span>
             <span class="menu-title">ECBS</span>
            </a>
           </div>
          </div>
         </div>

        </div>
        <!-- End of Container -->
       </div>
       <!-- End of Toolbar -->

       <style>
        .hero-bg {
          background-image: url('<?php echo $config['base_url']; ?>assets/media/images/2600x1200/bg-1.png');
        }
        .dark .hero-bg {
          background-image: url('<?php echo $config['base_url']; ?>assets/media/images/2600x1200/bg-1-dark.png');
        }
       </style>

       <div class="bg-center bg-cover bg-no-repeat hero-bg" style="margin-top:-80px;">
         <div class="container-fixed">
           <div class="flex flex-col items-center gap-2 lg:gap-3.5 py-4 lg:pt-5 lg:pb-10" style="padding:0px 10px 10px 10px;">
            <img src="<?php echo $config['base_url']; ?>images/banner/device-2.png" width="300px" />
           </div>
          </div>
       </div>