<?php
    if(!isset($_COOKIE['mrp_user_code_mst'])){
        header('location: ' . $CFG->wwwroot . '/index');
    }
?>

<div id="header" class="app-header">
    <div class="navbar-header">
        <a href="<?=$CFG->wwwroot?>/home" class="navbar-brand"><span class="navbar-logo"><img src="<?=$CFG->wwwroot?>/favicon.ico" alt=""></span> <b class="me-1">MRP</b> Manufacturing</a>
        <button type="button" class="navbar-mobile-toggler" data-toggle="app-sidebar-mobile">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
    </div>
    <div class="navbar-nav">
        <div class="d-flex justify-content-between">
            <div class="navbar-item navbar-user dropdown">
                <a href="#" class="navbar-link dropdown-toggle d-flex align-items-center" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-circle-user"></i> &nbsp;&nbsp;
                    <span>
                        <span class="d-none d-md-inline"><?=$_COOKIE['mrp_user_name_mst']?></span>
                        <b class="caret"></b>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end me-1">
                    <a href="extra_profile.html" class="dropdown-item">Edit Profile</a>
                    <a href="email_inbox.html" class="dropdown-item d-flex align-items-center">
                        Inbox Mail
                        <span class="badge bg-danger rounded-pill ms-auto pb-4px">2</span> 
                    </a>
                    <a href="calendar.html" class="dropdown-item">Calendar</a>
                    <a href="extra_settings_page.html" class="dropdown-item">Settings</a>
                    <div class="dropdown-divider"></div>
                    <button onclick="Logout()" class="dropdown-item">Log Out</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function Logout(){
        Swal.fire({
            icon: 'warning',
            title: 'ออกจากระบบ',
            text: 'ต้องการออกจากระบบหรือไม่?',
            showCancelButton: true,
            confirmButtonText: 'ออกจากระบบ',
            cancelButtonText: 'ยกเลิก',
        }).then((thens) => {
            if(thens.isConfirmed){
                $.post('<?=$CFG->mod_user?>/management', { protocol: 'UserLogout', usercode: '<?=$_COOKIE['mrp_user_code_mst']?>' }, function(data){
                    try {
                        const result = JSON.parse(data)
                        if(result.code == 200){
                            Swal.fire({
                                icon: 'success',
                                text: result.message,
                                showConfirmButton: false,
                                showCancelButton: false,
                            })
                            setTimeout(function (){
                                window.location = result.route
                            }, 2000)
                        }else{
                            SwalOnlyText('error', '', result.message)
                        }
                    } catch(err) {
                        SwalOnlyText('error', '', 'ไม่สามารถดำเนินการได้ ' + err.message)
                    }
                })
            }
        })
    }
</script>