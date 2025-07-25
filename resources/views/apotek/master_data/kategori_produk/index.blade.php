<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel </title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
        <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>  
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    </head>
    <body>

        <div class="container-fluid">

            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><h3 class="panel-title">Add Items</h3></div>
                        <div class="panel-body">

                            <form name="add_item" id="add_item" class="form-inline">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label for="pr_form_number">PR Form Number: </label>
                                    <input type="text" class="form-control" name="pr_number" value="" readonly required><br><br>
                                </div>

                                <div class="table-responsive">
                                    <table class='table table-bordered table-hover' id="tab_logic">
                                        <thead>
                                            <tr class='info'>
                                                <th style='width:10%;'>ITEM NO.</th>
                                                <th style='width:10%;'>QTY</th>
                                                <th style='width:10%;'>UNIT</th>
                                                <th style='width:30%;'>DESCRIPTION</th>
                                                <th style='width:10%;'>COST PER UNIT</th>
                                                <th style='width:10%;'>COST PER ITEM</th>
                                                <th style='width:10%;'>ACTION</th>
                                            </tr>
                                        </thead>
                                        <thead>
                                            <tr id="addr0">
                                                <td class="custom-tbl"><input class='form-control input-sm'style='width:100%;' type="text" value="1" id="pr_item0" name="pr_item[]" readonly required></td>
                                                <td class="custom-tbl"><input class='form-control input-sm' style='width:100%;' type="text" id="pr_qty0" oninput='multiply(0);' name="pr_qty[]"></td>
                                                <td class="custom-tbl"><input class='form-control input-sm' style='width:100%;' type="text" id="pr_unit0" name="pr_unit[]"></td>
                                                <td class="custom-tbl"><input class='form-control input-sm' style='width:100%;' type="text" id="pr_desc0" name="pr_desc[]"></td>
                                                <td><input class='form-control input-sm' style='width:100%;' type="text" id="pr_cpu0" oninput='multiply(0);' name="pr_cpu[]"></td>
                                                <td class="custom-tbl"><input class='estimated_cost form-control input-sm' id="pr_cpi0" style='width:100%;' type="text" name="pr_cpi[]" readonly></td>
                                                <td class="custom-tbl"><button type="button" id="add" class="btn btn-info btn-sm"><span class="glyphicon glyphicon-plus"></span></button></td>
                                            </tr>
                                        </thead>
                                        <tbody id="dynamic_field">

                                        <tbody>
                                        <tfoot>
                                            <tr class='info'>
                                                <td style='width:65%;text-align:right;padding:4px;' colspan='5'>GRAND TOTAL:</td>
                                                <td style='padding:0px;'>

                                                    <input style='width:100%;' type='text' class='form-control input-sm' id='grand_total' name='grand_total' value='0' readonly required>

                                                </td>

                                        </tfoot>

                                    </table>
                                </div>


                                <button type="button" id="submit" name="submit" class="btn btn-default">Submit</button>
                            </form>

                        </div>

                    </div>
                </div>

            </div>


        </div>

        <script type="text/javascript">



$(document).ready(function () {
    var i = 1;


    $('#add').click(function () {
        i++;
        $('#dynamic_field').append('<tr id="row' + i 
                + '" class="dynamic-added"><td class="custom-tbl"><input id="pr_item' + i 
                + '" class="form-control input-sm"style="width:100%;" type="text" value="' + i 
                + '" name="pr_item[]" readonly required></td><td class="custom-tbl"><input id="pr_qty' + i 
                + '"class="form-control input-sm" style="width:100%;" type="text" oninput="multiply(' + i 
                + ');" name="pr_qty[]"></td><td class="custom-tbl"><input id="pr_unit' + i 
                + '"class="form-control input-sm" style="width:100%;" type="text" name="pr_unit[]"></td><td class="custom-tbl"><input id="pr_desc' + i 
                + '" class="form-control input-sm" style="width:100%;" type="text" name="pr_desc[]"></td><td class="custom-tbl"><input id="pr_cpu' + i 
                + '" class="form-control input-sm" style="width:100%;" type="text" oninput="multiply(' + i 
                + ');" name="pr_cpu[]"></td><td class="custom-tbl"><input id="pr_cpi' + i 
                + '" class="estimated_cost form-control input-sm" style="width:100%;" type="text" name="pr_cpi[]" readonly></td><td class="custom-tbl"><button type="button" name="remove" id="' + i 
                + '" class="btn btn-danger btn-sm btn_remove"><span class="glyphicon glyphicon-remove"></span></button></td></tr>');
    });


    $(document).on('click', '.btn_remove', function () {
        var button_id = $(this).attr("id");
        $('#row' + button_id + '').remove();
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $('#submit').click(function () {
        $.ajax({
            url: "",
            method: "POST",
            data: $('#add_item').serialize(),
            type: 'json',

        });
    });

});




        </script>

        <script type="text/javascript">
            function multiply(id)
            {
                var total1 = parseFloat($('#pr_qty' + id).val()) * parseFloat($('#pr_cpu' + id).val());
                $("input[id=pr_cpi" + id + "]").val(total1);
                grandTotal();
            }
            function grandTotal()
            {
                var items = document.getElementsByClassName("estimated_cost");
                var itemCount = items.length;
                var total = 0;
                for (var i = 0; i < itemCount; i++)
                {
                    total = total + parseFloat(items[i].value);
                }
                document.getElementById('grand_total').value = total;
            }
        </script>

    </body>
</html>