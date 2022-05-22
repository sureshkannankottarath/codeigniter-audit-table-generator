<!DOCTYPE html>
<html lang="en">

<head>
    <title>Codeigniter - Audit Table Generator</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootoast.css">

</head>

<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">C Auditor</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="active"><a href="#nav_tables" data-toggle="tab">Home</a></li>
                <!-- <li><a href="#nav_tables" data-toggle="tab">Tables</a></li> -->
            </ul>
        </div>
    </nav>
    <div class="alert alert-success" id="success-alert" style="display:none"> Success Message</div>
    <div class="container tab-pane active" id="nav_tables" style="padding-top: 40px;">
        <h2><b>Database :</b> <?= $this->db->database ?></h2>
        <p><?= '<b>Platform :</b> ' . $this->db->platform() . ' ,<b>db version :</b> ' . $this->db->version() ?></p>

        <?php
        $procedures = $this->db->query('SHOW PROCEDURE STATUS');
        $procedures = $procedures->result_array();
        // print_r($procedures[0]['Name']);
        ?>
        <form action="<?= base_url('generateAuditTables') ?>" id="generateAuditTables" method="POST" enctype="multipart/form-data">
            <button type="submit" class="btn btn-info" style="float: right;margin-bottom:10px">Generate</button>
            <table class="table table-hover" id="dbtables" width="100%">
                <thead>
                    <tr>
                        <th>Table Name</th>
                        <th>Audit Table</th>
                    </tr>

                </thead>
                <tbody>

                </tbody>
            </table>
    </div>

    <!-- scripts start -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>assets/js/bootoast.js"></script>
    <!-- scripts end -->

</body>

</html>

<script>
    $(document).ready(function() {
        load_db_table();

        function load_db_table() {

            $('#dbtables').DataTable({
                "processing": true,
                "serverSide": true,
                "destroy": true,
                "order": [],
                "ajax": {
                    "url": "<?= base_url('list_db_tables'); ?>",
                    "type": "POST",
                    "data": {
                    }
                },
                //Set column definition initialisation properties
                "columnDefs": [{
                    className: "dt-head-center",
                    targets: "_all"
                }, {
                    className: 'text-center',
                    targets: [1]
                }, {
                    className: 'text-left',
                    targets: [0]
                }],
                "pageLength": 25
            });
        }
    });

    $("#generateAuditTables").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var form = $(this);
        var actionUrl = form.attr('action');

        $.ajax({
            type: "POST",
            url: actionUrl,
            data: form.serialize(),
            success: function(data) {
                load_db_table();
                bootoast.toast({
                    message: 'Audit Tables Generated Successfully',
                    type: 'success'
                });
                
            }
        });

    });

    function change_value(table_name, chk_id, type) {

        //slicing table name
        var table_name = table_name.slice(2);

        // check checkbox is checked or not
        if ($('#' + chk_id).is(':checked')) {
            $('#' + type + '_' + table_name).val(1);
        } else {
            $('#' + type + '_' + table_name).val(0);
        }

    }
</script>