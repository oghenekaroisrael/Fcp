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
              <p>Bulletin Archive</p>
            </a>
          </li>
          <li class="<?php if ($active_page == 'courses')echo 'active'; ?>">
            <a href="courses.php">
              <i class="fas fa-list"></i>
              <p>Courses</p>
            </a>
          </li>
          <li class="<?php if ($active_page == 'transcript' || $active_page == 'transcripts')echo 'active'; ?>">
            <a href="transcripts.php">
              <i class="fas fa-file"></i>
              <p>Student's Transcript</p>
            </a>
          </li>
          <li class="<?php if ($active_page == 'outstandings' || $active_page == 'outstanding')echo 'active'; ?>">
            <a href="outstandings.php">
              <i class="fas fa-times"></i>
              <p>Student's Outstanding</p>
            </a>
          </li>
          <li class="<?php if ($active_page == 'report')echo 'active'; ?>">
            <a href="report.php">
              <i class="fas fa-file-alt"></i>
              <p>General Report</p>
            </a>
          </li>
        </ul>
      </div>
    </div>