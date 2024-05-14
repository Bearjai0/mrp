<?php
    require_once("../../../session.php");
    
    $po_no = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $pu_con->query("SELECT * FROM tbl_purchase_order WHERE po_no = '$po_no'");
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);
?>
<form id="_recieve_material" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 60%;">
            <div class="modal-header">
                <h4 class="modal-title">Receive Materials - Raw Material</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <h5>Details</h5>
                <table class="table table-bordered table-striped" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>PO Number</th>
                            <th>Descriptions</th>
                            <th>Qty</th>
                            <th>Received Qty</th>
                            <th>Unit price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $desList = $pu_con->prepare(
                                "SELECT ROW_NUMBER() OVER(ORDER BY CAST(A.item_code AS INT)) AS list, po_no, A.item_code, A.request_for, A.material_qty, A.material_qty_mrp_path, A.material_unitprice, A.material_summary_mrp_path, B.alt_material_name, B.alt_project
                                 FROM tbl_po_detail AS A 
                                 INNER JOIN tbl_pr_detail_path AS B ON A.po_no = B.po_ref AND A.item_code = B.po_ref_item_code
                                 WHERE A.po_no = :po_no
                                 ORDER BY CAST(A.item_code AS INT)"
                            );
                            $desList->bindParam(':po_no', $fstResult['po_no']);
                            $desList->execute();
                            while($desResult = $desList->fetch(PDO::FETCH_ASSOC)):
                        ?>
                        <tr>
                            <td nowrap class="p-2"><?=$desResult['list']?></td>
                            <td nowrap class="p-2"><?=$desResult['po_no']?></td>
                            <td nowrap class="p-2"><?=$desResult['request_for']?></td>
                            <td nowrap class="p-2 text-end"><?=number_format($desResult['material_qty'], 0, '.', ',')?></td>
                            <td nowrap class="p-2 text-end"><?=number_format($desResult['material_qty'] - $desResult['material_qty_mrp_path'], 0, '.', ',')?></td>
                            <td nowrap class="p-2 text-end"><?=number_format($desResult['material_unitprice'], 2, '.', ',')?></td>
                            <td nowrap class="p-2 text-center">
                                <?= $desResult['material_qty_mrp_path'] == 0 ? '<span class="badge bg-gradient-black fw-600">Received</span>' : '<span class="badge bg-gradient-blue fw-600">Pending</span>' ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <hr>
                <h5>Receive</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th nowrap class="bg-gradient-black text-white text-center" width="20%">Field Name</th>
                            <th nowrap class="bg-gradient-black text-white text-center" width="80%">Field Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">PO Number :</td>
                            <td class="pt-1 pb-1"><input type="text" id="po_no" name="po_no" class="form__field p-0" value="<?=$po_no?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">INV Number <span class="text-red">**</span> :</td>
                            <td class="pt-1 pb-1"><input type="text" id="inv_no" name="inv_no" class="form__field p-0" style="text-transform: uppercase;" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Supplier Name :</td>
                            <td class="pt-1 pb-1"><input type="text" id="sup_name" name="sup_name" class="form__field p-0" value="<?=$fstResult['vendor_name']?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Choose Material :</td>
                            <td class="p-0">
                                <select id="item_code" name="item_code" data-parsley-required="true" data-style="btn-white"></select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">RM Type :</td>
                            <td class="pt1 pb-1"><input type="text" id="rm_type" name="rm_type" class="form__field p-0" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Color :</td>
                            <td class="pt-1 pb-1">
                                <select id="choose_color" name="choose_color" class="form__field p-0 pt-1 pb-1" data-parsley-required="true" data-style="btn-white">
                                    <option value="">เลือกสี</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
                                    <option value="None">None</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Quantity(Pcs.) :</td>
                            <td class="pt-1 pb-1"><input type="number" id="quantity" name="quantity" oninput="CalculateSummary()" class="form__field p-0" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Comp Item(Pcs.) <span class="text-red">** งานแถม</span></td>
                            <td class="pt-1 pb-1"><input type="number" id="comp_item" name="comp_item" oninput="CalculateSummary()" class="form__field p-0" data-parsley-required="true"></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Unit Price(THB)</td>
                            <td class="pt-1 pb-1"><input type="text" id="unit_price" name="unit_price" class="form__field p-0" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Summary(THB)</td>
                            <td class="pt-1 pb-1"><input type="text" id="summary" name="summary" class="form__field p-0" data-parsley-required="true" readonly></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn bg-gradient-blue fw-600 text-white">Confirm Receive</button>
                <a href="javascript:;" class="btn btn-white fw-600" data-bs-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        GetItemList()
    })

    function GetItemList(){
        $.post('<?=$CFG->fol_material_inbound?>/management', { protocol: 'POsDetails', type: 'POsListItem', po_no: '<?=$po_no?>' }, function(data){
            try {
                const result = JSON.parse(data)
                var item_code = $("#item_code")
                item_code.append('<option option="">เลือกรายการ</option>')
                $.each(result.datas, function(id, item){
                    $(item_code).append(
                        $('<option class="fw-700"></option>').val(item.item_code).html(item.request_for)
                    )
                })

                item_code.picker({ search: true })
            } catch(e) {
                SwalOnlyText('error', 'ไม่สามารถแสดงผลข้อมูลรายการรอรับเข้าได้ ' + e.message())
            }
        })
    }

    $('#item_code').on('sp-change', function(e) {
        $.post('<?=$CFG->fol_material_inbound?>/management', { protocol: 'POsDetails', type: 'POsItemDetails', po_no: '<?=$po_no?>', item_code: e.currentTarget.value }, function(data){
            console.log(data)
            try {
                const result = JSON.parse(data)
                
                if(result.code == 200){
                    $("#rm_type").val(result.datas[0].alt_material_type)
                    $("#quantity").val(result.datas[0].material_qty_mrp_path)
                    $("#comp_item").val(0)
                    $("#unit_price").val(currency(result.datas[0].material_unitprice, { seperator: ',', symbol: '', precision: 2 }).format())
                    $("#summary").val(currency(result.datas[0].material_summary_mrp_path, { seperator: ',', symbol: '', precision: 2 }).format())
                }else{
                    SwalOnlyText('warning', result.message)
                }
            } catch(e) {
                SwalOnlyText('warning', 'ไม่สามารถแสดงข้อมูลของรายการที่เลือกได้ ' + e.message)
            }
        })
    });


    function CalculateSummary(){
        var quantity = parseInt($("#quantity").val().replaceAll(',','')) + parseInt($("#comp_item").val().replaceAll(',',''))
        console.log(quantity)
        var unit_price = $("#unit_price").val().replaceAll(',','')
        var summary = quantity * unit_price
        $("#summary").val(currency(summary, { seperator: ',', symbol: '', precision: 2 }).format())
    }
    
    function CalculateFetchUnitPrice(){
        var summary = $("#summary").val().replaceAll(',','')
        var quantity = parseInt($("#quantity").val().replaceAll(',','')) + parseInt($("#comp_item").val().replaceAll(',',''))

        $("#unit_price").val(currency(summary / quantity, { seperator: ',', symbol: '', precision: 4 }).format())
    }

    $("#_recieve_material").submit(function(e){
        e.preventDefault()
        var form = $(this)
        var formData = new FormData($(this)[0])

        form.parsley().validate()
        if(!form.parsley().isValid()){
            SwalOnlyText('error', 'กรุณากรอกข้อมูลช่องสีแดงให้ครบ')
            return false
        }

        Swal.fire({
            icon: 'info',
            text: 'ยืนยันการบันทึกข้อมูลรับ Raw Material หรือไม่?',
            showConfirmButton: true,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-thumbs-up"></i> ยืนยัน',
            cancelButtonText: 'ยกเลิก'
        }).then((thens) => {
            if(thens.isConfirmed){
                Swal.fire({
                    title: 'กำลังดำเนินการบันทึกข้อมูล...',
                    text: 'กรุณารอสักครู่',
                    imageUrl: '<?=$CFG->sub_gif?>/ajax-loader.gif',
                    showConfirmButton: false,
                    showCancelButton: false,
                    didOpen: () => {
                        formData.append('protocol', 'ReceiveRawMaterial')
                        formData.append('work_type', 'RAW MAT')
                        $.ajax({
                            method: "POST",
                            url: "management",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(data){
                                console.log(data)
                                try {
                                    const result = JSON.parse(data)
                                    if(result.code == 200){
                                        SwalReload('success', 'ดำเนินการสำเร็จ', result.message, result.route, target = '_blank')
                                    }else{
                                        SwalOnlyText('warning', result.message)
                                    }
                                } catch (err) {
                                    SwalOnlyText('error', 'ไม่สามารถประมวลได้ ' + err)
                                }
                            }, error: function(err){
                                SwalOnlyText('error', 'ไม่สามารถประมวลได้ ' + err)
                            }
                        })
                    }
                })
            }
        })
    })
</script>