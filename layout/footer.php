<footer>
    <div class="footer clearfix mb-0 text-muted">
        <div class="float-start">
            <p>nhóm 9 &copy;</p>
        </div>
        <div class="float-end">
            
    </div>
</footer>
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
        ajax: 'model/fetch_domains.php',
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
            url: 'model/add_domain.php',
            method: 'POST',
            data: { domain_name: $('#domainName').val() },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.message
                    });
                    table.ajax.reload();
                    $('#domainName').val('');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: result.message
                    });
                }
            }
        });
    });

    $('#domainTable tbody').on('click', '.change-status', function() {
        var id = $(this).data('id');
        $.ajax({
            url: 'model/change_status.php',
            method: 'POST',
            data: { id: id },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Status changed successfully'
                });
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
        url: 'model/edit_domain.php',
        method: 'POST',
        data: {
            id: $('#editDomainId').val(),
            domain_name: $('#editDomainName').val()
        },
        success: function(response) {
            var result = JSON.parse(response);
            if (result.status === 'success') {
                $('#editDomainModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Domain updated successfully'
                });
                table.ajax.reload();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message
                });
            }
        }
    });
});


    $('#domainTable tbody').on('click', '.delete-domain', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'model/delete_domain.php',
                    method: 'POST',
                    data: { id: id },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Your domain has been deleted.'
                        });
                        table.ajax.reload();
                    }
                });
            }
        });
    });

    $('#exportButton').on('click', function() {
        $.ajax({
            url: 'model/export_excel.php',
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

    <script src="assets/static/js/components/dark.js"></script>
    <script src="assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    
    
    <script src="assets/compiled/js/app.js"></script>
    

    
<!-- Need: Apexcharts -->
<script src="assets/extensions/apexcharts/apexcharts.min.js"></script>
<script src="assets/static/js/pages/dashboard.js"></script>

</body>

</html>