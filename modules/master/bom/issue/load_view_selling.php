<?php
    require_once("../../../../session.php");
    
    $rev_uniq = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';

    $fst = $db_con->prepare("SELECT * FROM tbl_rev_selling WHERE rev_uniq = :rev_uniq");
    $fst->bindParam(':rev_uniq', $rev_uniq);
    $fst->execute();
    $fstResult = $fst->fetch(PDO::FETCH_ASSOC);
?>
<form id="_approve_selling" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 70%;">
            <div class="modal-header">
                <h4 class="modal-title">BOM - Approve Selling</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body"> 
                <h6>Mail Content</h6>
                <textarea id="mail_content" name="mail_content" class="form-control" style="height: 11em;" data-parsley-required="true" readonly><?=$fstResult['rev_content']?></textarea>
                <h6 class="mt-3">FG List</h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th nowrap class="bg-gradient-black text-white text-center" width="10%">#</th>
                            <th nowrap class="bg-gradient-black text-white text-center" width="15%">BOM Uniq</th>
                            <th nowrap class="bg-gradient-black text-white text-center" width="20%">FG Code</th>
                            <th nowrap class="bg-gradient-black text-white text-center" width="25%">Part Customer</th>
                            <th nowrap class="bg-gradient-black text-white text-center" width="15%">Old Price</th>
                            <th nowrap class="bg-gradient-yellow text-black text-center" width="15%">New Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $list = $db_con->prepare("SELECT ROW_NUMBER() OVER(ORDER BY bom_uniq) AS list, A.*, A.det_bom_uniq, B.fg_code, B.part_customer FROM tbl_rev_selling_detail AS A LEFT JOIN tbl_bom_mst AS B ON A.det_bom_uniq = B.bom_uniq WHERE det_rev_uniq = :rev_uniq");
                            $list->bindParam(':rev_uniq', $rev_uniq);
                            $list->execute();
                            while($listResult = $list->fetch(PDO::FETCH_ASSOC)):
                        ?>
                            <td class="pt-1 pb-1 text-center"><?=$listResult['list']?></td>
                            <td class="pt-1 pb-1"><input type="text" id="bom_uniq" name="bom_uniq[]" class="form__field text-center p-0" value="<?=$listResult['det_bom_uniq']?>" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1"><input type="text" id="fg_code" name="fg_code[]" class="form__field text-center p-0" value="<?=$listResult['fg_code']?>" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1"><input type="text" id="part_customer" name="part_customer[]" class="form__field text-center p-0" value="<?=$listResult['part_customer']?>" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1"><input type="text" id="old_selling_price" name="old_selling_price[]" class="form__field text-center p-0" value="<?=number_format($listResult['det_old_price'], 2)?>" data-parsley-required="true" readonly></td>
                            <td class="pt-1 pb-1"><input type="text" id="selling_price" name="selling_price[]" class="form__field text-blue text-center p-0" style="border-bottom: dashed 1px #0088cc;" value="<?=number_format($listResult['det_new_price'], 2)?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <hr>
                <center>
                    <button type="submit" disabled class="btn bg-gradient-blue-indigo fw-600 text-white ps-5 pe-5">Approve</button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-white fw-600 ms-5 ps-5 pe-5">Close</button>
                </center>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        
    })
</script>