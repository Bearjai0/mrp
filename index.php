<!DOCTYPE html>
<html lang="en">
    <?php
        require_once("application.php");
        require_once("js_css_header.php");

        if(isset($_COOKIE['mrp_user_code_mst'])){
            header('location: ' . $CFG->wwwroot . '/home');
        }
    ?>
    <body class="pace-top">
        <div id="loader" class="app-loader">
            <span class="spinner"></span>
        </div>

        <div id="app" class="app">
            <div class="login login-v2 fw-bold">
                <div class="login-cover">
                    <div class="login-cover-img" style="background-image: url(<?=$CFG->sub_images?>/login-bg/login-bg-17.jpg)" data-id="login-cover-image"></div>
                    <div class="login-cover-bg"></div>
                </div>
                <div class="login-container">
                    <div class="login-header">
                        <div class="brand">
                            <div class="d-flex align-items-center">
                                <span class="logo"><img src="favicon.ico" style="height: 40px;"></span> <b>MRP </b>&nbsp;Manufacturing
                            </div>
                            <small>A program for managing warehouse, material, production, planning, and manufacturing management.</small>
                        </div>
                        <div class="icon">
                            <i class="fa fa-lock"></i>
                        </div>
                    </div>
                    <div class="login-content">
                        <form id="frm_login" data-parsley-validate="true">
                            <div class="form-floating mb-20px">
                                <input type="text" class="form-control fs-13px h-45px border-0" placeholder="Admin Username" id="usercode" style="text-transform: uppercase;" />
                                <label for="usercode" class="d-flex align-items-center text-gray-600 fs-13px">Admin Username</label>
                            </div>
                            <div class="form-floating mb-20px">
                                <input type="password" class="form-control fs-13px h-45px border-0" placeholder="Password" id="password" />
                                <label for="password" class="d-flex align-items-center text-gray-600 fs-13px">Password</label>
                            </div>
                            <hr>
                            <div class="mb-20px">
                                <button type="submit" class="btn btn-theme d-block w-100 h-45px btn-lg">Sign me in</button>
                            </div>
                            <div class="text-gray-500">
                                Not a member yet? Click <a href="javascript:;" class="text-white">here</a> to request the permission.
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="login-bg-list clearfix">
                <div class="login-bg-list-item active"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="<?=$CFG->sub_images?>/login-bg/login-bg-17.jpg" style="background-image: url(<?=$CFG->sub_images?>/login-bg/login-bg-17-thumb.jpg)"></a></div>
                <div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="<?=$CFG->sub_images?>/login-bg/login-bg-16.jpg" style="background-image: url(<?=$CFG->sub_images?>/login-bg/login-bg-16-thumb.jpg)"></a></div>
                <div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="<?=$CFG->sub_images?>/login-bg/login-bg-11.jpg" style="background-image: url(<?=$CFG->sub_images?>/login-bg/login-bg-11-thumb.jpg)"></a></div>
                <div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="<?=$CFG->sub_images?>/login-bg/login-bg-14.jpg" style="background-image: url(<?=$CFG->sub_images?>/login-bg/login-bg-14-thumb.jpg)"></a></div>
                <div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="<?=$CFG->sub_images?>/login-bg/login-bg-13.jpg" style="background-image: url(<?=$CFG->sub_images?>/login-bg/login-bg-13-thumb.jpg)"></a></div>
                <div class="login-bg-list-item"><a href="javascript:;" class="login-bg-list-link" data-toggle="login-change-bg" data-img="<?=$CFG->sub_images?>/login-bg/login-bg-12.jpg" style="background-image: url(<?=$CFG->sub_images?>/login-bg/login-bg-12-thumb.jpg)"></a></div>
            </div>
        </div>
    </body>
    <?php require_once("js_css_footer.php"); ?>
</html>

<!-- #modal-message -->
<div id="load_view_detail" class="modal fade"></div>
<!-- #modal-message -->


<script type="text/javascript">
    var toggleAttr = '[data-toggle="login-change-bg"]';
    var toggleImageAttr = '[data-id="login-cover-image"]';
    var toggleImageSrcAttr = 'data-img';
    var toggleItemClass = '.login-bg-list-item';
    var toggleActiveClass = 'active';
    
    $(document).on('click', toggleAttr, function(e) {
        e.preventDefault();
        
        $(toggleImageAttr).css('background-image', 'url(' + $(this).attr(toggleImageSrcAttr) +')');
        $(toggleAttr).closest(toggleItemClass).removeClass(toggleActiveClass);
        $(this).closest(toggleItemClass).addClass(toggleActiveClass);	
    });


    $("#frm_login").submit(function(e){
        e.preventDefault()
        
        const usercode = $("#usercode").val()
        const password = $("#password").val()

        if(usercode == '' || password == ''){
            SwalOnlyText('warning', '', 'กรุณากรอกข้อมูล Usercode และ Password ให้ครบ')
            return false
        }

        $.post('<?=$CFG->mod_user?>/management', { protocol: 'CHeckAdminLogin', usercode: usercode, password: password }, function(data){
            try {
                const result = JSON.parse(data)
                if(result.code == 200){
                    if(result.type == 'Member'){
                        Swal.fire({
                            icon: 'success',
                            text: 'ยืนยันตัวตนสำเร็จ',
                            showConfirmButton: false,
                            showCancelButton: false,
                        })
                        setTimeout(function (){
                            window.location = result.route
                        }, 2000)
                    }else{
                        OpenViewDetail("#load_view_detail", '<?=$CFG->mod_user?>/load_chage_password', { 'usercode' : result.user_code, 'user_name_en' : result.user_name_en })
                    }
                }else{
                    SwalOnlyText('warning', '', result.message)
                }
            } catch(err) {
                SwalOnlyText('error', '', 'ไม่สามารถดำเนินการได้ ' + err.message)
            }
        })
    })
</script>