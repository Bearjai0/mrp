<head>
	<meta charset="utf-8" />
    <meta content="IE=edge,chrome=1,X-UA-Compatible">
    <title>MRP Manufacturing</title>
	<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
	
	<link href="<?=$CFG->dir_css?>/vendor.min.css" rel="stylesheet" />
	<link href="<?=$CFG->dir_css?>/app.min.css" rel="stylesheet" />
	<link href="<?=$CFG->dir_css?>/app-input-style.css" rel="stylesheet" />

	<link href="<?=$CFG->wwwlib?>/datatable-1.13.6/dataTables.net-bs5/dataTables.bootstrap5.min.css" rel="stylesheet" />
	<link href="<?=$CFG->wwwlib?>/datatable-1.13.6/dataTables.net-responsive-bs5/responsive.bootstrap5.min.css" rel="stylesheet" />
	<link href="<?=$CFG->wwwlib?>/datatable-1.13.6/dataTables.net-buttons-bs5/buttons.bootstrap5.min.css" rel="stylesheet" />

    <link href="<?=$CFG->wwwlib?>/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet" />

	<link href="<?=$CFG->wwwlib?>/fontawesome-6.4.2/all.min.js" rel="stylesheet" />
	<link href="<?=$CFG->wwwlib?>/gritter/jquery.gritter.css" rel="stylesheet" />
	<link href="<?=$CFG->wwwlib?>/select-picker/dist/picker.min.css" rel="stylesheet" />
	<link href="<?=$CFG->wwwlib?>/select2/select2.min.css" rel="stylesheet" />

	<link href="<?=$CFG->wwwlib?>/switchery/dist/switchery.min.css" rel="stylesheet" />
    
    <?php if(basename($_SERVER['SCRIPT_FILENAME'], '.php')): ?>
        <link href="<?=$CFG->wwwlib?>/nvd3/build/nv.d3.min.css" rel="stylesheet" />
    <?php endif; ?>
    
    <style>
        @font-face {
            font-family: ProgrammeRegular;
            src: url('/webfonts/Programme-Regular.woff2');
        }

        @font-face {
            font-family: Wakanda;
            src: url('/webfonts/Wakanda.ttf');
        }

         *,a,abbr,acronym,address,applet,article,aside,audio,b,big,blockquote,body,canvas,caption,center,cite,code,dd,del,details,dfn,div,dl,dt,em,embed,fieldset,figcaption,figure,footer,form,h1,h2,h3,h4,h5,h6,header,hgroup,html,i,iframe,img,ins,kbd,label,legend,li,mark,menu,nav,object,ol,output,p,pre,q,ruby,s,samp,section,small,span,strike,strong,sub,summary,sup,table,tbody,td,tfoot,th,thead,time,tr,tt,u,ul,var,video{
            font-family: ProgrammeRegular;
        }
        .file, .input, .select, .spinner, .tag-input, .textarea, input[type=datetime-local], input[type=email], input[type=file], input[type=month], input[type=number], input[type=password], input[type=search], input[type=tel], input[type=text], input[type=time], input[type=url], input[type=week], select, textarea{
            font-family: ProgrammeRegular;
        }
    </style>
</head>