@extends('layouts.app')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Contacts</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 position-relative">
                <h6 class="m-0 font-weight-bold text-primary">Contacts</h6>
                <button type="button" class="btn btn-primary add-btn" data-toggle="modal" data-target="#addContact">Add
                    Contact</button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="contact-dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addContact" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Contact</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" method="post" id="contact-form">
                            <input type="hidden" name="id" id="contact_id">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" name="name" id="name">
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="text" class="form-control" name="email" id="email">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone:</label>
                                <input type="text" class="form-control" name="phone" id="phone">
                            </div>
                            <div class="form-group">
                                <label for="address">Address:</label>
                                <textarea class="form-control" name="address" id="address"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary close-contact" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary submit-contact" type="button">Submit</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
@endsection
@push('scripts')
    <script>
        $('#contact-dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('contacts.index') }}",
            columns: [{
                    name: 'name',
                    data: 'name'
                },
                {
                    name: 'email',
                    data: 'email'
                },
                {
                    name: 'phone',
                    data: 'phone'
                },
                {
                    name: 'address',
                    data: 'address'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        $('.submit-contact').click(function() {
            $('#contact-form').submit();
        });

        $('#contact-dataTable').on('click', '.edit', function() {
            $('#contact_id').val($(this).attr('data-id'));
            $.ajax({
                method: "GET",
                url: "{{ route('contacts.edit', ':id') }}".replace(':id', $(this).attr('data-id')),
                success: function(response) {
                    $('.modal-title').text('Edit Contact');
                    $('#name').val(response.contact.name);
                    $('#email').val(response.contact.email);
                    $('#phone').val(response.contact.phone);
                    $('#address').val(response.contact.address);
                    $('#addContact').show();
                }
            });
        });

        $('#contact-dataTable').on('click', '.delete', function() {
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this Contact!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{ route('contacts.destroy', ':id') }}".replace(':id', $(this).data(
                                "id")),
                            type: "DELETE",
                            success: function(response) {
                                swal("Success", "Contact Deleted Successfully!", "success");
                                $('#contact-dataTable').DataTable().draw();
                            },
                            error: function(xhr, status, error) {
                                swal("Error", "Something Went Wrong!", "error")
                                $('#contact-dataTable').DataTable().draw();
                            }
                        });
                    }
                });
        });

        $('.add-btn').click(function() {
            $('.modal-title').text('Add Contact');
            $('#contact_id').val('');
            $('#contact-form')[0].reset();
        })
        $('#contact-form').submit(function(event) {
            event.preventDefault();
            let form = $(this)[0];
            let formData = new FormData(form);
            let swalLoading = swal({
                title: "Loading...",
                text: "Please wait while the Contact is processed",
                icon: "info",
                buttons: false,
                closeOnClickOutside: false,
                closeOnEsc: false,
            });
            $('.swal-overlay').show();
            $('.swal-modal').show();
            let url = "{{ route('contacts.store') }}";
            let method = "POST";
            if (formData.get('id')) {
                url = "{{ route('contacts.update', ':id') }}".replace(':id', formData.get('id'));
            }
            $.ajax({
                url: url,
                type: method,
                data: formData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function(response) {
                    swal("Success", "Contact " + (formData.get('id') ? "Updated" : "Created") +
                        " Successfully!", "success");
                    form.reset();
                    $('#contact-dataTable').DataTable().draw();
                    $('#addContact').hide();
                    $('.close-contact').trigger("click");
                },
                error: function(xhr, status, error) {
                    let errorMessage = "";
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage += "\n";
                        for (let key in xhr.responseJSON.errors) {
                            errorMessage += xhr.responseJSON.errors[key].join("\n") + "\n";
                        }
                    } else if (xhr.statusText) {
                        errorMessage += "\n" + xhr.statusText;
                    } else {
                        errorMessage += "\nFailed to Process Contact!";
                    }
                    swal("Error", errorMessage, "error");
                }
            });
        });
    </script>
@endpush
