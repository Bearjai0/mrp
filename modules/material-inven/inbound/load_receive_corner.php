<?php
    require_once("../../../session.php");
    
    $po_no = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $pu_con->query("SELECT vendor_name FROM tbl_purchase_order WHERE po_no = '$po_no'");
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);
?>
<form id="_recieve_material" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 60%;">
            <div class="modal-header">
                <h4 class="modal-title">Receive Materials - Corner Material</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-6">
                        <label for="po_no">PO Number.</label>
                        <input type="text" id="po_no" name="po_no" class="form-control fw-700" value="<?=$po_no?>" data-parsley-required="true" readonly>
                    </div>
                    <div class="col-6">
                        <label for="inv_no">Invoice Number. <span class="text-red">**</span></label>
                        <input type="text" id="inv_no" name="inv_no" class="form-control fw-700" data-parsley-required="true">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12">
                        <label for="sup_name">Supplier Name</label>
                        <input type="text" id="sup_name" name="sup_name" class="form-control fw-700" value="<?=$fstResult['vendor_name']?>" data-parsley-required="true" readonly>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-10">
                        <label for="item_code">Choose Material <span class="text-red">**</span></label>
                        <select id="item_code" name="item_code" data-parsley-required="true" data-style="btn-white"></select>
                    </div>
                    <div class="col-2">
                        <label for="choose_color">Color <span class="text-red">**</span></label>
                        <select id="choose_color" name="choose_color" class="form-control" data-parsley-required="true" data-style="btn-white">
                            <option value="">เลือกสี</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                            <option value="None">None</option>
                        </select>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-3">
                        <label for="choose_color">Quantity(Pcs.) <span class="text-red">**</span></label>
                        <input type="number" id="quantity" name="quantity" class="form-control" data-parsley-required="true">
                    </div>
                    <div class="col-3">
                        <label for="choose_color">Comp Item(Pcs.) <span class="text-red">**</span></label>
                        <input type="number" id="comp_item" name="comp_item" class="form-control" data-parsley-required="true">
                    </div>
                    <div class="col-3">
                        <label for="choose_color">Unit Price(THB)</label>
                        <input type="text" id="unit_price" name="unit_price" class="form-control" data-parsley-required="true" readonly>
                    </div>
                    <div class="col-3">
                        <label for="choose_color">Summary (THB)</label>
                        <input type="text" id="summary" name="summary" class="form-control" data-parsley-required="true" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn bg-gradient-blue text-white">Confirm Receive</button>
                <a href="javascript:;" class="btn btn-white" data-bs-dismiss="modal">Close</a>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        $("#choose_color").picker({ search: true })
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
                        $.ajax({
                            method: "POST",
                            url: "<?=$CFG->fol_material_inbound?>/management",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(data){
                                console.log(data)
                                try {
                                    const result = JSON.parse(data)
                                    if(result.code == 200){
                                        Swal.fire({
                                            icon: 'success',
                                            title: result.message,
                                            text: result.message,
                                        }).then(() => {
                                            location.reload()
                                        })
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