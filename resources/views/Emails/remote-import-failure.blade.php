<html>
    <head> 
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table border="1" id="show_request_table" style="text-align: center;" class="table table-bordered">
                        <thead>
                            <th>Import Table</th>
                            <th>Import Description</th>
                            <th>Import Status</th>
                            <th>Import Date</th>
                        </thead>
                        <tbody>
                        <tr>
                            <td>{{ $importTable }}</td>
                            <td>{{ $importDescription }}</td>
                            <td>{{ $status }}</td>
                            <td>{{ $importDate }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>


