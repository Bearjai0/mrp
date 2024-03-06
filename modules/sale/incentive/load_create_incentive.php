<?php
    require_once("../../../session.php");

    $inc_month = isset($_POST['sendingTask']) ? $_POST['sendingTask'] : '';
?>
<form id="_incenlist" data-parsley-validate="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content mb-5" style="width: 95%;">
            <div class="modal-header">
                <h4 class="modal-title">Incentive of <?=date('F Y', strtotime($inc_month))?></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-hidden="true"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th nowrap class="bg-gradient-black text-white text-center" width="20%">Field Name</th>
                            <th nowrap class="bg-gradient-black text-white text-center" width="80%">Field Value</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end text-end">Incentive Month :</td>
                            <td class="pt-1 pb-1"><input type="text" id="inc_month" name="inc_month" class="form__field p-0" value="<?=date('F Y', strtotime($inc_month))?>" data-parsley-required="true" readonly></td>
                        </tr>
                        <tr>
                            <td class="pt-1 pb-1 bg-gray-200 text-end">Choose income file :</td>
                            <td class="pt-1 pb-1">
                                <input type="file" id="upfile" name="upfile" class="form__field p-0" data-parsley-required="true">
                                <button type="button" id="confirmUpfile" class="btn badge bg-gradient-blue text-white">Confirm</button>
                            </td>
                    </tbody>
                </table>
                <hr>
                <center>
                    <button type="submit" disabled class="btn bg-gradient-blue-indigo fw-600 text-white ps-5 pe-5">Confirm</button>
                    <button type="button" data-bs-dismiss="modal" class="btn btn-white fw-600 ms-5 ps-5 pe-5">Close</button>
                </center>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(data){
    
    })

    $("#confirmUpfile").click(function(){
        var upfile = $("#upfile")[0].files[0]
        var formData = new FormData()
        formData.append('upfile', upfile)
        formData.append('protocol', 'MatchFile')

        $.ajax({
            url: 'management',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(resp) {
                console.log('resp is ' + resp)
            }, error: function(xhr, status, error) {
                console.log('failed on upload')
            }
        })
    })
</script>