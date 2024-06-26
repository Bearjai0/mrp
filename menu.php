<?php
    $filename = basename($_SERVER['SCRIPT_FILENAME'], '.php');

    $arr_filt_master = [
        'route_name' => [
            'ini_bom_set',
            'ini_bom_list',
            'ini_revise_selling',
            'ini_raw_material',
            'ini_sub_material',
            'ini_supplier'
        ],
        'route_data' => [
            [
                'root' => $CFG->func_bom_master,
                'route' => 'ini_bom_set',
                'name' => 'Master Set',
                'group' => 0
            ],[
                'root' => $CFG->func_bom_master,
                'route' => 'ini_bom_list',
                'name' => 'Master BOM',
                'group' => 0
            ],[
                'root' => $CFG->func_bom_issue,
                'route' => 'ini_revise_selling',
                'name' => 'Revise Selling',
                'group' => 1
            ],[
                'root' => $CFG->func_material_rm,
                'route' => 'ini_raw_material',
                'name' => 'Raw Materials',
                'group' => 2
            ],[
                'root' => $CFG->func_material_sm,
                'route' => 'ini_raw_material',
                'name' => 'Sub Materials',
                'group' => 2
            ],[
                'root' => $CFG->fol_master_supplier,
                'route' => 'ini_supplier',
                'name' => 'Master Supplier',
                'group' => 3
            ]
        ]
    ];

    $arr_filt_material_inbound = [
        'route_name' => [
            'ini_receive_materials',
        ],
        'route_data' => [
            [
                'root' => $CFG->fol_material_inbound,
                'route' => 'ini_receive_materials',
                'name' => 'Receive Materials',
                'group' => 0
            ]
        ]
    ];

    $arr_filt_tooling = ['ini_tooling_list','ini_handling_mst'];
    $arr_filt_planning = ['ini_planning_order','ini_planning_inplan'];
    $arr_filt_fulfillment = ['ini_ffmc_upload','ini_ffmc_create','ini_ffmc_order','ini_ffmc_report'];
    // $arr_filt_material_inbound = ['ini_rm_receive','ini_sm_receive','ini_corner_receipt','ini_putaway_material'];
    $arr_filt_material_outbound = ['ini_rm_new_picksheet','ini_sm_new_picksheet','ini_rm_job_picksheet','ini_material_shipping'];
    $arr_filt_production_report = ['ini_simulate','ini_inform','ini_inform_transactions','ini_station_transactions','ini_pd_shipping_lots','ini_pd_shipping_details', 'ini_planning_simulate'];
    $arr_filt_wip_inven = ['ini_wip_inven','ini_wip_inven_transactions'];
    
    $mrp_user_code_mst = isset($_COOKIE['mrp_user_code_mst']) ? $_COOKIE['mrp_user_code_mst'] : '';
    $mrp_user_type_code_mst = isset($_COOKIE['mrp_user_type_code_mst']) ? $_COOKIE['mrp_user_type_code_mst'] : '';
    $mrp_user_dep_id_mst = isset($_COOKIE['mrp_user_dep_id_mst']) ? $_COOKIE['mrp_user_dep_id_mst'] : '';
?>
<div id="sidebar" class="app-sidebar" data-bs-theme="dark">
    <div class="app-sidebar content" data-scrollbar="true" data-height="100%">
        <div class="menu">
            <div class="menu-profile">
                <a href="#" class="menu-profile-link" data-toggoe="app-sidebar-profile" data-target="#appSidebarProfileMenu">
                    <div class="menu-profile-cover with-shadow"></div>
                    <div class="menu-profile-image">
                        <img src="<?=$CFG->sub_images?>/user.png" alt="thumbnail background">
                    </div>
                    <div class="menu-profile-info">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <?=$_COOKIE['mrp_user_name_mst']?>
                            </div>
                            <i class="fa-solid fa-fingerprint"></i>
                        </div>
                        <small><?=$_COOKIE['mrp_user_position_mst']?></small>
                    </div>
                </a>
            </div>
            <div class="menu-header fw-700">Navigation</div>
            <div class="menu-item <?=$filename == 'home' ? 'active' : '' ?>">
                <a href="<?=$CFG->wwwroot?>/home" class="menu-link">
                    <div class="menu-icon rounded bg-gradient-dark md hydrated" style="width: 26px; height: 26px;">
                        <i class="fa-brands fa-delicious"></i>
                    </div>
                    <div class="menu-text">Dashboard</div>
                </a>
            </div>
            <div class="menu-item has-sub <?php if(in_array($filename, $arr_filt_master['route_name'])){ echo 'active'; } ?>">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon rounded bg-gradient-blue-indigo md hydrated" style="width: 26px; height: 26px;">
                        <i class="fa-solid fa-database"></i>
                    </div>
                    <div class="menu-text">Master Data</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item <?php if(in_array($filename, $arr_filt_master['route_name'])){ echo 'active'; } ?>">
                        <?php
                            foreach($arr_filt_master['route_data'] as $id => $item){
                                $tck_id = $id == 0 ? $item['group'] : $tck_id;
                                if($tck_id != $item['group']){
                                    $tck_id = $item['group'];
                                    echo '<div class="menu-divider m-0"></div>';
                                }

                                $mem_item_active = $filename == $item['route'] ? 'active' : '';
                                echo '<div class="menu-item '.$mem_item_active.'"><a href="'. $item['root'] . '/' . $item['route'].'" class="menu-link">'.$item['name'].'</a></div>';
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="menu-item has-sub <?php if($filename == "ini_account_report"){ echo 'active'; } ?>">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon rounded bg-gradient-indigo md hydrated" style="width: 26px; height: 26px;">
                        <i class="fa-solid fa-receipt"></i>
                    </div>
                    <div class="menu-text">Accounting</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item <?php if($filename == "ini_account_report"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->mod_accounting?>/ini_account_report" class="menu-link"><div class="menu-text">Warehouse Report</div></a>
                    </div>
                </div>
            </div>
            <?php if($mrp_user_type_code_mst == "T005" || $mrp_user_type_code_mst == "T002"): ?>
            <div class="menu-item has-sub <?php if($filename == "ini_incenlist" || $filename == "ini_incenrate" || $filename == "ini_sale_order" || $filename == "ini_selling_list" || $filename == "ini_quotations_list" || $filename == "ini_quotations_history"){ echo 'active'; } ?>">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon rounded bg-gradient-teal md hydrated text-black" style="width: 26px; height: 26px;">
                        <i class="fa-solid fa-user-tag"></i>
                    </div>
                    <div class="menu-text">Sale</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item <?php if($filename == "ini_incenlist"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_sale_incentive?>/ini_incenlist" class="menu-link"><div class="menu-text">Incentive</div></a>
                    </div>
                    <div class="menu-item <?php if($filename == "ini_incenrate"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_sale_incenrate?>/ini_incenrate" class="menu-link"><div class="menu-text">Incentive Rate</div></a>
                    </div>
                    <div class="menu-divider m-0"></div>
                    <div class="menu-item <?php if($filename == "ini_sale_order"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_sale_saleorder?>/ini_sale_order" class="menu-link"><div class="menu-text">Management Order</div></a>
                    </div>
                    <div class="menu-item <?php if($filename == "ini_tax_doc"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_sale_saleorder?>/ini_tax_doc" class="menu-link"><div class="menu-text">New Tax Document</div></a>
                    </div>
                    <div class="menu-item <?php if($filename == "ini_tax_doc"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_sale_saleorder?>/ini_tax_doc" class="menu-link"><div class="menu-text">List Tax Document</div></a>
                    </div>
                    <div class="menu-divider m-0"></div>
                    <div class="menu-item <?php if($filename == "ini_selling_list"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_sale_quotations?>/ini_selling_list" class="menu-link"><div class="menu-text">Create Quotations</div></a>
                    </div>
                    <div class="menu-item <?php if($filename == "ini_quotations_list"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_sale_quotations?>/ini_quotations_list" class="menu-link"><div class="menu-text">Request Quotations</div></a>
                    </div>
                    <div class="menu-item <?php if($filename == "ini_quotations_history"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_sale_quotations?>/ini_quotations_history" class="menu-link"><div class="menu-text">All Quotations</div></a>
                    </div>
                </div>
            </div>
            <? endif; ?>
            <div class="menu-item has-sub <?php if(in_array($filename, $arr_filt_fulfillment)){ echo 'active'; } ?>">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon rounded bg-gradient-purple-indigo md hydrated" style="width: 26px; height: 26px;">
                        <i class="fa-solid fa-arrow-up-from-water-pump"></i>
                    </div>
                    <div class="menu-text">Fulfillment</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item <?php if($filename == $arr_filt_fulfillment[0]){ echo 'active'; } ?>"><a href="<?=$CFG->fol_ffmc_create . '/' . $arr_filt_fulfillment[0]?>" class="menu-link"><div class="menu-text">Create Order</div></a></div>
                    <div class="menu-item <?php if($filename == $arr_filt_fulfillment[1]){ echo 'active'; } ?>"><a href="<?=$CFG->fol_ffmc_upload . '/' . $arr_filt_fulfillment[1]?>" class="menu-link"><div class="menu-text">Upload Order</div></a></div>
                    <div class="menu-divider m-0"></div>
                    <div class="menu-item <?php if($filename == $arr_filt_fulfillment[2]){ echo 'active'; } ?>"><a href="<?=$CFG->fol_ffmc_manage . '/' . $arr_filt_fulfillment[2]?>" class="menu-link"><div class="menu-text">Management Order</div></a></div>
                    <div class="menu-divider m-0"></div>
                    <div class="menu-item <?php if($filename == $arr_filt_fulfillment[3]){ echo 'active'; } ?>"><a href="<?=$CFG->fol_ffmc_report . '/' . $arr_filt_fulfillment[3]?>" class="menu-link"><div class="menu-text">Report Order Ontime</div></a></div>
                </div>
            </div>
            <div class="menu-item has-sub <?php if(in_array($filename, $arr_filt_planning)){ echo 'active'; } ?>">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon rounded bg-gradient-orange text-black md hydrated" style="width: 26px; height: 26px;">
                        <i class="fa-solid fa-paste"></i>
                    </div>
                    <div class="menu-text">Planning</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item <?php if($filename == $arr_filt_planning[0]){ echo 'active'; } ?>"><a href="<?=$CFG->fol_planning_manage . '/' . $arr_filt_planning[0]?>" class="menu-link"><div class="menu-text">Manage Plan</div></a></div>
                    <div class="menu-item <?php if($filename == $arr_filt_planning[1]){ echo 'active'; } ?>"><a href="<?=$CFG->fol_planning_manage . '/' . $arr_filt_planning[1]?>" class="menu-link"><div class="menu-text">Manage Job</div></a></div>
                </div>
            </div>
            <?php if($mrp_user_dep_id_mst == "D020" || $mrp_user_type_code_mst == "T005" || $mrp_user_code_mst == "GDJ00184"): ?>
            <div class="menu-item has-sub <?php if($filename == "ini_pd_work" || $filename == "ini_pd_work_list" || $filename == "ini_pd_station" || $filename == "ini_combine_set" || $filename == "ini_tigthing_cv" || $filename == "ini_outside_plan"){ echo 'active'; } ?>">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon rounded bg-gradient-red-pink md hydrated" style="width: 26px; height: 26px;">
                        <i class="fa-solid fa-screwdriver-wrench"></i>
                    </div>
                    <div class="menu-text">Production</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item <?php if($filename == "ini_pd_work"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_pd_work?>/ini_pd_work" class="menu-link"><div class="menu-text">Work order</div></a>
                    </div>
                    <div class="menu-item <?php if($filename == "ini_pd_work_list"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_pd_work?>/ini_pd_work_list" class="menu-link"><div class="menu-text">Work on process</div></a>
                    </div>
                    <div class="menu-divider m-0"></div>
                    <div class="menu-item <?php if($filename == "ini_pd_station"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_pd_station?>/ini_pd_station" class="menu-link"><div class="menu-text">Station Confirmation</div></a>
                    </div>
                    <?php if($mrp_user_type_code_mst == "T005"){ ?>
                    <div class="menu-item <?php if($filename == "ini_pd_station"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_pd_station?>/ini_pd_station" class="menu-link"><div class="menu-text">Station Confirmation 2</div></a>
                    </div>
                    <?php } ?>
                    <div class="menu-item <?php if($filename == "ini_combine_set"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_pd_combine?>/ini_combine_set" class="menu-link"><div class="menu-text">Combine Set</div></a>
                    </div>
                    <div class="menu-item <?php if($filename == "ini_tigthing_cv"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_pd_tigthing?>/ini_tigthing_cv" class="menu-link"><div class="menu-text">Tightening Cover</div></a>
                    </div>
                    <div class="menu-divider m-0"></div>
                    <div class="menu-item <?php if($filename == "ini_outside_plan"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_pd_outside?>/ini_outside_plan" class="menu-link"><div class="menu-text">Outside plan</div></a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <div class="menu-item has-sub <?php if(in_array($filename, $arr_filt_production_report)){ echo 'active'; } ?>">
                <a href="javascript:;" class="menu-link"> 
                    <div class="menu-icon rounded bg-gradient-green md hydrated text-black" style="width: 26px; height: 26px;">
                        <i class="fa-regular fa-folder-open"></i>
                    </div>
                    <div class="menu-text">Production Report</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item <?php if($filename == $arr_filt_production_report[0]){ echo 'active'; } ?>"><a href="<?=$CFG->fol_rep_simulate . '/' . $arr_filt_production_report[0]?>" class="menu-link"><div class="menu-text">Job Simulator</div></a></div>
                    <div class="menu-divider m-0"></div>
                    <div class="menu-item <?php if($filename == $arr_filt_production_report[1]){ echo 'active'; } ?>"><a href="<?=$CFG->fol_rep_inform . '/' . $arr_filt_production_report[1]?>" class="menu-link"><div class="menu-text">Job Summary</div></a></div>
                    <div class="menu-item <?php if($filename == $arr_filt_production_report[2]){ echo 'active'; } ?>"><a href="<?=$CFG->fol_rep_inform . '/' . $arr_filt_production_report[2]?>" class="menu-link"><div class="menu-text">Job Transactions</div></a></div>
                    <div class="menu-divider m-0"></div>
                    <div class="menu-item <?php if($filename == $arr_filt_production_report[3]){ echo 'active'; } ?>"><a href="<?=$CFG->fol_rep_shipping . '/' . $arr_filt_production_report[3]?>" class="menu-link"><div class="menu-text">Station Transactions</div></a></div>
                    <div class="menu-item <?php if($filename == $arr_filt_production_report[4]){ echo 'active'; } ?>"><a href="<?=$CFG->fol_rep_shipping . '/' . $arr_filt_production_report[4]?>" class="menu-link"><div class="menu-text">Confirm Shipping Lots</div></a></div>
                    <div class="menu-item <?php if($filename == $arr_filt_production_report[5]){ echo 'active'; } ?>"><a href="<?=$CFG->fol_rep_shipping . '/' . $arr_filt_production_report[5]?>" class="menu-link"><div class="menu-text">Confirm Shipping Details</div></a></div>
                    <div class="menu-divider m-0"></div>
                    <div class="menu-item <?php if($filename == $arr_filt_production_report[6]){ echo 'active'; } ?>"><a href="<?=$CFG->fol_rep_simulate . '/' . $arr_filt_production_report[6]?>" class="menu-link"><div class="menu-text">Planning Simulator</div></a></div>
                </div>
            </div>
            <div class="menu-item has-sub <?php if(in_array($filename, $arr_filt_tooling)){ echo 'active'; } ?>">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon rounded bg-gradient-yellow-red md hydrated" style="width: 26px; height: 26px;">
                        <i class="fa-solid fa-circle-nodes"></i>
                    </div>
                    <div class="menu-text">Control Tooling</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item">
                        <a href="<?=$CFG->fol_toollist?>/ini_tooling_list?ts_type=Plate Die Cut" class="menu-link"><div class="menu-text">Plate Die Cut</div></a>
                    </div>
                    <div class="menu-item">
                        <a href="<?=$CFG->fol_toollist?>/ini_tooling_list?ts_type=Flexo Block Screen" class="menu-link"><div class="menu-text">Flexo Block Screen</div></a>
                    </div>
                    <div class="menu-item <?php if($filename == "ini_handling_mst"){ echo 'active'; } ?>">
                        <a href="<?=$CFG->fol_handling?>/ini_handling_mst" class="menu-link"><div class="menu-text">Handling</div></a>
                    </div>
                </div>
            </div>
            <div class="menu-header fw-700">Inventory Management</div>
            <div class="menu-item has-sub <?php if(in_array($filename, $arr_filt_wip_inven)){ echo 'active'; } ?>">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon rounded bg-gradient-purple-indigo md hydrated" style="width: 26px; height: 26px;">
                        <i class="fa-solid fa-arrow-up-from-water-pump"></i>
                    </div>
                    <div class="menu-text">WIP Inventory</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item <?php if($filename == $arr_filt_wip_inven[0]){ echo 'active'; } ?>"><a href="<?=$CFG->mod_wip_inven . '/' . $arr_filt_wip_inven[0]?>" class="menu-link"><div class="menu-text">WIP Inventory</div></a></div>
                    <div class="menu-item <?php if($filename == $arr_filt_wip_inven[1]){ echo 'active'; } ?>"><a href="<?=$CFG->mod_wip_inven . '/' . $arr_filt_wip_inven[1]?>" class="menu-link"><div class="menu-text">WIP Transactions</div></a></div>
                </div>
            </div>

            <div class="menu-item has-sub <?php if(in_array($filename, $arr_filt_material_inbound['route_name'])){ echo 'active'; } ?>">
                <a href="javascript:;" class="menu-link">
                    <div class="menu-icon rounded bg-gradient-purple-indigo md hydrated" style="width: 26px; height: 26px;">
                        <i class="fa-solid fa-industry"></i>
                    </div>
                    <div class="menu-text">Material Inventory</div>
                    <div class="menu-caret"></div>
                </a>
                <div class="menu-submenu">
                    <div class="menu-item has-sub <?php if(in_array($filename, $arr_filt_material_inbound['route_name'])){ echo 'active'; } ?>">
                    <a href="javascript:;" class="menu-link">
                        <div class="menu-text">Inbound</div>
                        <div class="menu-caret"></div>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item has-sub <?php if(in_array($filename, $arr_filt_material_inbound['route_name'])){ echo 'active'; } ?>">
                            <?php
                                foreach($arr_filt_material_inbound['route_data'] as $id => $item){
                                    $tck_id = $id == 0 ? $item['group'] : $tck_id;
                                    if($tck_id != $item['group']){
                                        $tck_id = $item['group'];
                                        echo '<div class="menu-divider m-0"></div>';
                                    }

                                    $mem_item_active = $filename == $item['route'] ? 'active' : '';
                                    echo '<div class="menu-item '.$mem_item_active.'"><a href="'. $item['root'] . '/' . $item['route'].'" class="menu-link">'.$item['name'].'</a></div>';
                                }
                            ?>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <div class="menu-header fw-700">User Information</div>
            <div class="menu-item <?php if($filename == "ini_user"){ echo 'active'; } ?>">
                <a href="<?=$CFG->mod_user?>/ini_user" class="menu-link">
                    <div class="menu-icon rounded bg-gradient-orange md hydrated" style="width: 26px; height: 26px;">
                        <i class="fa-solid fa-signature"></i>
                    </div>
                    <div class="menu-text">User management</div>
                </a>
            </div>

            <div class="menu-item d-flex">
				<a href="javascript:;" class="app-sidebar-minify-btn ms-auto d-flex align-items-center text-decoration-none" data-toggle="app-sidebar-minify"><div class="menu-text">Collapse</div></a>
			</div>
        </div>
    </div>
</div>