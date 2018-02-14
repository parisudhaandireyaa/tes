<?php
/*echo "<pre>";
print_r($owner_list);
exit;*/
?>

<!-- begin row -->
<div class="row">
    <!-- begin col-12 -->
    <div class="col-md-12">
        <!-- begin panel -->
        <div class="panel panel-inverse">
            <div class="panel-heading">
                <div class="panel-heading-btn">
                    <?php
                    echo $this->Html->link(
                        $this->Html->tag('i', '', array('class' => 'fa fa-list')) . " List",
                        array('action' => "index"),
                        array('class' => 'btn btn-white btn-xs', 'escape' => false)
                    );
                    ?>
                </div>
                <h4 class="panel-title">CC Collection</h4>
            </div>
            <div class="panel-body">
			
			<?php echo $this->Form->create(null, ['url' => ['controller' => 'payments', 'action' => 'add'], "class" => "form-horizontal", "id" => "payment_form", "name" => "payment_form"]); ?>
                <div class="form-group col-md-4">
                    <label class="control-label col-md-4">Drivers</label>
                    <div class="col-md-8">
                        <select class="form-control input-sm select_2 custom_required" id="driver_list" name="driver[id]">
                            <option value="" style="display:none;"> Select</option>
                            <?php
                            foreach ($drivers_list as $driv) {
                                $selected = ($driver_id == $driv['dri_id']) ? "selected" : "";
                                echo "<option cc_id ='{$driv['cc_id']}' value='{$driv['dri_id']}' {$selected}>{$driv['vehicle_no']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <table class="table table-bordered col-md-6" id="cc_mail_table" width100>
                    <thead>
					    <tr>
                            <th>#:</th>
						    <th>Value</th>
                        </tr>
					</thead>
					<tbody>
                    <tr>
                        <th>TAXI NO:</th>
						<th></th>
                    </tr>
                    <tr>
                        <th>Previous Balance</th>                        
						<th></th>
                    </tr>
					<tr>
                        <th>Total Received</th>                        
						<th></th>
                    </tr>
					<tr>
                        <th>Total Collection</th>                        
						<th></th>
                    </tr>
					<tr>
                        <th>Total CC(12%)</th>                        
						<th></th>
                    </tr>
					<tr>
                        <th>Amount To Be Paid</th>                        
						<th></th>
                    </tr>
					<tr>
                        <th>Balance</th>                        
						<th></th>
                    </tr>
					<tr>
                        <th>Mode</th>                        
						<th></th>
                    </tr>
                    </thead>
                </table>			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
                <?php echo $this->Form->create(null, ['url' => ['controller' => 'payments', 'action' => 'add'], "class" => "form-horizontal", "id" => "payment_form", "name" => "payment_form"]); ?>

                <input type="hidden" name="other[pre_paid_id]" value="<?php echo $preId; ?>" />
                <div class="form-group col-md-4">
                    <label class="control-label col-md-4">Drivers</label>
                    <div class="col-md-8">
                        <select class="form-control input-sm select_2 custom_required" id="driver_list" name="driver[id]">
                            <option value="" style="display:none;"> Select</option>
                            <?php
                            foreach ($drivers_list as $driv) {
                                $selected = ($driver_id == $driv['dri_id']) ? "selected" : "";
                                echo "<option cc_id ='{$driv['cc_id']}' value='{$driv['dri_id']}' {$selected}>{$driv['vehicle_no']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <table class="table table-striped table-bordered" id="cc_mail_table" width100>
                    <thead>
                    <tr>
                        <th>Sno</th>
                        <th>Taxi No</th>
                        <th>Previous Balance</th>
                        <th>Current Due</th>
                        <!--<th>Discount</th>-->
                        <th>Total</th>
                        <th>Received</th>
                        <th>Balance</th>
                        <th>Mode</th>
                        <!--<th>
                            <div class="checkbox">
                                <div class="checkbox checkbox-danger checkbox-inline p-l-0">
                                    <input type="checkbox" class="styled remove_cbox action_all" id="remove_cbox"/>
                                    <label for="remove_cbox"> Remove All </label>
                                </div>
                            </div>
                        </th>-->
                    </tr>
                    </thead>


                    <tbody>
                    <?php
                    if (isset($driver_id) && $driver_id > 0) {
                        $i = 0;
                        $prev_bal_total = 0;
                        $current_due_total = 0;
                        $to_be_paid_total = 0;
                        $discount_total = 0;

                        //if (empty($vehicle_data['vehicles'])) {
                        //    echo "<tr><td colspan='9' class='text-center'> Currently no payment is pending..
                        // </td></tr>";
                       // } else {
                            foreach ($vehicle_data['vehicles'] as $veh) {
                                $current_due_total += $veh['current_due'];
                                $remainig_payment = $veh['remainig_payment'];
                                $to_be_paid_total += $veh['current_tobe_paid'];
                                $discount_total += $veh['discount'];
                                $balance = ($remainig_payment==0)?$veh['current_tobe_paid']:0;
                                $i++;
                                echo "<tr>
                                <td>{$i}</td>
                                <td taxi_no >{$veh['vehicle_no']}</td>
                                <td pre_blance > <input type='hidden' name='payment[{$veh['id']}][previous_balance]' value='{$veh['previous_balance']}' /> {$veh['previous_balance']} </td>
                                <td current_due >{$veh['current_due']}</td>
                                <td current_due class='hide'><input type='hidden' 
                                name='payment[{$veh['id']}][discount_amount]' value='{$veh['discount']}' /> <input type='hidden' name='payment[{$veh['id']}][discount_reason]' value='{$veh['reason']}' /> {$veh['discount']}</td>
                                <td total_receivable > <input type='hidden' name='payment[{$veh['id']}][current_tobe_paid]' value='{$veh['current_tobe_paid']}' /><input type='hidden' name='payment[{$veh['id']}][current_due]' value='{$veh['current_due']}' /> {$veh['current_tobe_paid']}</td>
                                <td now_paid ><input class='form-control input-sm' style='background: #f0f3f5;border: 1px solid #f0f3f5;' type='text' name='payment[{$veh['id']}][paid]' readonly value='{$remainig_payment}' style='border: 0px;' /></td>
                                <td balance ><input class='form-control input-sm' style='background: #f0f3f5;border: 
                                1px solid #f0f3f5;' type='text' name='payment[{$veh['id']}][balance]' readonly value='{$balance}' style='border: 0px;' /></td>
                                <td>
                                    <div class='radio radio-primary radio-inline' style='width:50px'>
                                        <input type='radio' class='paymode'
                                        name='payment[{$veh['id']}][payment_mode]' value='cash'/>
                                        <label> Cash</label>
                                    </div>
                                    <div class='radio radio-primary radio-inline' style='width:50px'>
                                        <input type='radio' class='paymode'  
                                       name='payment[{$veh['id']}][payment_mode]' value='credit'/>
                                        <label> Credit</label>
                                    </div>
                                
                                </td>
                                <!--<td remove ><div class='checkbox p-t-0'>
                                <div class='checkbox checkbox-danger checkbox-inline p-l-0'>
                                    <input type='checkbox' class='styled remove_cbox' id='remove{$i}'/>
                                    <label for='remove{$i}'> Remove </label>
                                </div>
                            </div></td>-->
                              </tr>";
                            //}
                        }
                    } else {
                        echo "<tr><td colspan='9' class='text-center'> Select a Driver </td></tr>";
                    }
                    ?>
                    </tbody>

                    <?php
                    if (isset($driver_id) && $driver_id > 0 && !empty($vehicle_data['vehicles'])) {
                        ?>
                        <tfoot>
                        <tr total_row>
                            <th></th>
                            <th taxi_no></th>
                            <th pre_blance><?php echo $prev_bal_total; ?></th>
                            <th current_due><?php echo $current_due_total; ?></th>
                            <!--<th current_due><?php /*echo $discount_total; */?></th>-->
                            <th total_receivable id='total_tobe_rec'><?php echo $to_be_paid_total; ?></th>
                            <th now_paid><input type="text" id='total_now_paid' class="form-control input-sm"
                                                value="<?php echo $remainig_payment; ?>"></th>
                            <th balance id='total_balance'>0</th>
                            <th></th>
                            <!--<th remove></th>-->
                        </tr>
                        </tfoot>
                        <?php
                    }
                    ?>

                </table>

                <div class="form-group col-md-12">
                    <label class="col-md-0 control-label"></label>
                    <div class="col-md-12">
                        <button type="button"
                                class="btn btn-sm btn-primary pull-right m-r-10 pay"><i
                                class="fa fa-money"></i> &nbsp; Pay
                        </button>
                    </div>
                </div>
                <?php
                echo $this->Form->end();
                ?>
            </div>
        </div>
        <!-- end panel -->
    </div>
    <!-- end col-12 -->
</div>
<!-- end row -->


<script>
    $(function () {
        checkHaveCC($("#driver_list").find(":selected").attr("cc_id"));
        function checkHaveCC(id){
            if(id=='') {
                $()._toast("Error", "Map CC Collection Type...", "error");
                $(".pay").attr("disabled","true");
                $("#cc_mail_table").addClass("hide");
                return false;
            }
            else{
                $(".pay").removeAttr("disabled");'' +
                $("#cc_mail_table").removeClass("hide");
                return true;
            }
        }
        $("#owner_list,#driver_list").on("change", function () {
            ret = checkHaveCC($(this).find(":selected").attr("cc_id"));
            if(ret) {
                var owner_id = $(this).val();
                $.redirect(GLOBAL.BASE_URL + "/payments/add/" + owner_id);
            }
        });

        $(".pay").on("click", function () {
            //var owner = $("#owner_list").val();
            var owner = $("#driver_list").val();

            var total_amount = $("#total_now_paid").val();

            if(!($(".paymode").is(":checked")))
            {
                $()._toast("Error", "Select Payment Mode!", "error");
                return false;
            }

            if (owner == '' || owner == null) {
                $()._toast("Error", "Select Owner!", "error");
                return false;
            } else if (total_amount == undefined) {
                $()._toast("Warning", "Currently no payment is pending!", "warning");
                return false;
            } else if (total_amount == "" || total_amount <= 0) {
                $()._toast("Error", "Paid amount should be greater than Zero!", "error");
                return false;
            } else {
                $("#payment_form").submit();
            }
        });
    });

    var value_onEmpty = 0;

    /** >>>> Allow only 2 decimal number only **/
    $(document).ready(function () {
        $("#total_now_paid").keypress(function (evt) {
            var el = this;
            var charCode = (evt.which) ? evt.which : event.keyCode;
            var number = el.value.split('.');
            if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            //just one dot
            if (number.length > 1 && charCode == 46) {
                return false;
            }
            //get the carat position
            var caratPos = getSelectionStart(el);
            var dotPos = el.value.indexOf(".");
            if (caratPos > dotPos && dotPos > -1 && (number[1].length > 1) && charCode != 8) {
                return false;
            }
            return true;
        });
    });

    function getSelectionStart(o) {
        if (o.createTextRange) {
            var r = document.selection.createRange().duplicate();
            r.moveEnd('character', o.value.length);

            if (r.text == '') return o.value.length;
            return o.value.lastIndexOf(r.text)
        } else return o.selectionStart
    }
    /** >>>> Allow only 2 decimal number only **/

    $("body").on("input", "#total_now_paid", function (e) {
        //if(e.keyCode)
        var flAmt = $(this).val(),
            toPy = 0;

        $("#cc_mail_table tbody tr").each(function () {
            toPy = $(this).find("[total_receivable]").text();

            if ($(this).hasClass("not_payable")) {
//                $(this).find("[now_paid]").text(0);
//                $(this).find("[balance]").text(toPy);
                $(this).find("[now_paid]").find('input').val(0);
                $(this).find("[balance]").find('input').val(toPy);
                return;
            }

            if (parseFloat(flAmt) >= toPy) {
//                $(this).find("[now_paid]").text(toPy);
//                $(this).find("[balance]").text(0);
                $(this).find("[now_paid]").find('input').val(toPy);
                $(this).find("[balance]").find('input').val(0);
                flAmt = flAmt - toPy;
            }
            else {
//                $(this).find("[now_paid]").text(flAmt);
//                $(this).find("[balance]").text(toPy - flAmt);
                $(this).find("[now_paid]").find('input').val(flAmt);
                $(this).find("[balance]").find('input').val((toPy - flAmt).toFixed(2));
                flAmt = 0;
            }
        });

        if (parseFloat(flAmt) > 0) {
            var eachA = flAmt / $("#cc_mail_table tbody tr").not(".not_payable").length;
            var np = 0,
                bl = 0;

            $("#cc_mail_table tbody tr").each(function () {
                if ($(this).hasClass("not_payable")) return;
                var np = parseFloat($(this).find("[now_paid]").find('input').val()) + parseFloat(eachA);

//                $(this).find("[now_paid]").text(np);
//                $(this).find("[balance]").text(0 - eachA);
                $(this).find("[now_paid]").find('input').val(np.toFixed(2));
                $(this).find("[balance]").find('input').val(0 - (eachA.toFixed(2)));

            });
        }

        if(parseFloat($("[pre_blance]:last").text())>0)
        {
            var totBalance = $("#total_tobe_rec").text() - $("#total_now_paid").val();
        }
        else{
            var totBalance = $("#total_tobe_rec").val();
        }

        $("#total_balance").text(totBalance);
    });

    $("body").on("click", ".remove_cbox", function () {
        var getParent = $(this).closest("tr"),
            isChecked = $(this).is(":checked");

        if (isChecked == false)
            $(".action_all.remove_cbox").prop("checked", false);

        var isAll = $(this).hasClass("action_all") ? true : false;

        if (isAll) {
            getParent = $(this).closest("table").find("tbody");
        }

        getParent.find(".remove_cbox").each(function () {
            if (isChecked)
                $(this).closest("tr").addClass("not_payable");
            else
                $(this).closest("tr").removeClass("not_payable");

            $(this).prop("checked", isChecked);

            //$(this).closest("tr").find("[now_paid]").text(0);
        });

        if ($('#total_now_paid').is('[readonly]')) {
            $("#total_now_paid").val(value_onEmpty).removeAttr("readonly");
        }

        if ($("#cc_mail_table tbody .remove_cbox:checked").length == $("#cc_mail_table tbody tr").length) {
            $(".action_all.remove_cbox").prop("checked", true);

            value_onEmpty = $("#total_now_paid").val();
            $("#total_now_paid").attr("readonly", "readonly").val(0);
        }

        $("#total_now_paid").trigger("input");
    });

</script>
