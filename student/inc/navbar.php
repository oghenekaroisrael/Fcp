
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-toggle d-inline">
              <button type="button" class="navbar-toggler">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </button>
            </div>
            <a class="navbar-brand" href="javascript:void(0)">Student Dashboard</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
          </button>
          <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav ml-auto">
              <li class="dropdown nav-item">
                <a href="javascript:void(0)" class="dropdown-toggle nav-link" data-toggle="dropdown">
                <?php 
                  $cn = Database::getInstance()->count_notifications(0,$matNumber);
                  if ($cn > 0) {
                    ?>
                    <div class="notification d-none d-lg-block d-xl-block"></div>
                    <?php
                  }
                ?>
                  <i class="far fa-bell"></i>
                  <p class="d-lg-none">
                    Notifications
                  </p>
                </a>
                <ul class="dropdown-menu dropdown-menu-right dropdown-navbar">
                <?php 
                    $notifications = Database::getInstance()->show_notification_all($matNumber);
                    if (count($notifications) > 0) {
                      foreach ($notifications as $value) {
                        if ($value['status'] == 1) {
                          
                          ?>
                            <li class="nav-link"><a href="<?php echo $value['link']; ?>&n=<?php echo $value['notificationID']; ?>" class="nav-item dropdown-item"><?php echo $value['message']; ?></a></li>
                          <?php
                        }else if ($value['status'] == 0) {
                          ?>
                            <li class="nav-link"><a href="<?php echo $value['link']; ?>&n=<?php echo $value['notificationID']; ?>" class="nav-item dropdown-item"><b><?php echo $value['message']; ?></b></a></li>
                          <?php
                        }
                      }
                    }else{
                      ?>
                            <li class="nav-link" style="color: #000;">No Notifications</li>
                          <?php
                    }
                  ?>
                  </ul>
              </li>
              <li class="dropdown nav-item">
                <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                  <div class="photo">
                    <img src="../assets/img/pic.jpg" alt="Profile Photo">
                  </div>
                  <b class="caret d-none d-lg-block d-xl-block"></b>
                  <p class="d-lg-none">
                    Log out
                  </p>
                </a>
                <ul class="dropdown-menu dropdown-navbar">
                  <!-- <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Profile</a></li>
                  <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Settings</a></li>
                  <li class="dropdown-divider"></li> -->
                  <li class="nav-link"><a href="logout.php" class="nav-item dropdown-item">Log out</a></li>
                </ul>
              </li>
              <li class="separator d-lg-none"></li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->