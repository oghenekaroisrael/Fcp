<div class="sidebar">
      <div class="sidebar-wrapper">
        <div class="logo">
          <a href="javascript:void(0)" class="simple-text logo-mini">
            Logo
          </a>
          <a href="javascript:void(0)" class="simple-text logo-normal">
            FCP
          </a>
        </div>
        <ul class="nav">
          <li class="<?php if ($active_page == 'dashboard')echo 'active'; ?>">
            <a href="dashboard.php">
              <i class="fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          <li class="<?php if ($active_page == 'bulletin')echo 'active'; ?>">
            <a href="bulletin.php">
              <i class="fas fa-book"></i>
              <p>My Bulletin</p>
            </a>
          </li>
          <li class="<?php if ($active_page == 'courses')echo 'active'; ?>">
            <a href="courses.php">
              <i class="fas fa-list"></i>
              <p>My Courses</p>
            </a>
          </li>
          <li class="<?php if ($active_page == 'transcript')echo 'active'; ?>">
            <a href="transcript.php">
              <i class="fas fa-list"></i>
              <p>My Transcript</p>
            </a>
          </li>
          <li class="<?php if ($active_page == 'outstandings')echo 'active'; ?>">
            <a href="outstandings.php">
              <i class="fas fa-times"></i>
              <p>My Outstandings</p>
            </a>
          </li>
        </ul>
      </div>
    </div>