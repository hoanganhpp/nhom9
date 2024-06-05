<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Domain Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
</head>
<body>
<div class="container">
    <h1 class="mt-5">Domain Management</h1>
    <form id="domainForm" class="mb-3">
        <div class="form-group">
            <label for="domainName">Domain Name</label>
            <input type="text" class="form-control" id="domainName" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Domain</button>
    </form>
    <table id="domainTable" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>Domain Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <!-- Data will be inserted here by DataTables -->
        </tbody>
    </table>
    <button id="exportButton" class="btn btn-success mt-3">Export to Excel</button>
</div>

<!-- Modal for editing domain -->
<div class="modal fade" id="editDomainModal" tabindex="-1" aria-labelledby="editDomainModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDomainModalLabel">Edit Domain</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editDomainForm">
                    <input type="hidden" id="editDomainId">
                    <div class="form-group">
                        <label for="editDomainName">Domain Name</label>
                        <input type="text" class="form-control" id="editDomainName" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#domainTable').DataTable({
        ajax: 'fetch_domains.php',
        columns: [
            { data: 'id' },
            { data: 'domain_name' },
            { data: 'status' },
            {
                data: 'id',
                render: function(data, type, row) {
                    return '<button class="btn btn-warning change-status" data-id="' + data + '">Change Status</button> ' +
                           '<button class="btn btn-primary edit-domain" data-id="' + data + '" data-domain="' + row.domain_name + '">Edit</button> ' +
                           '<button class="btn btn-danger delete-domain" data-id="' + data + '">Delete</button>';
                }
            }
        ]
    });

    $('#domainForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'add_domain.php',
            method: 'POST',
            data: { domain_name: $('#domainName').val() },
            success: function(response) {
                table.ajax.reload();
                $('#domainName').val('');
            }
        });
    });

    $('#domainTable tbody').on('click', '.change-status', function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'change_status.php',
            method: 'POST',
            data: { id: id },
            success: function(response) {
                table.ajax.reload();
            }
        });
    });

    $('#domainTable tbody').on('click', '.edit-domain', function() {
        var id = $(this).data('id');
        var domain = $(this).data('domain');
        $('#editDomainId').val(id);
        $('#editDomainName').val(domain);
        $('#editDomainModal').modal('show');
    });

    $('#editDomainForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: 'edit_domain.php',
            method: 'POST',
            data: {
                id: $('#editDomainId').val(),
                domain_name: $('#editDomainName').val()
            },
            success: function(response) {
                $('#editDomainModal').modal('hide');
                table.ajax.reload();
            }
        });
    });

    $('#domainTable tbody').on('click', '.delete-domain', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this domain?')) {
            $.ajax({
                url: 'delete_domain.php',
                method: 'POST',
                data: { id: id },
                success: function(response) {
                    table.ajax.reload();
                }
            });
        }
    });

    $('#exportButton').on('click', function() {
        $.ajax({
            url: 'export_excel.php',
            method: 'GET',
            xhrFields: {
                responseType: 'blob'
            },
            success: function(data) {
                var a = document.createElement('a');
                var url = window.URL.createObjectURL(data);
                a.href = url;
                a.download = 'domains.xlsx';
                document.body.append(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            }
        });
    });
});
</script>
</body>
</html>
