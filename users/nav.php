
<nav class="navbar">
    <div class="navbar-title" style="display: flex;">
        <div class="logo" style="margin-right: 10px;">
            <img src="../image/logo2.png" alt="" style="width: 100%;max-width:60px">
        </div>
        <div class="title">
            <h1>DIGITAL MANAGEMENT INFORMATION SYSTEM</h1>
            <h2>BARANGAY MARINIG CABUYAO, LAGUNA</h2>
        </div>
    </div>

    <ul class="menu" id="menu">
        <li><a href="index.php"><i class="fas fa-user"></i> Profile</a></li>
        <li style="position: relative;">
            <a href="request.php"><i class="fa-solid fa-hand-point-up"></i> Request</a>
            <?php if($count_request > 0): ?>
            <span class="badge" id="count_request"><?php echo $count_request ?></span>
            <?php endif;?>
        </li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</nav>
