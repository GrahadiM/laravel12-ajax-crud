@extends('layouts.base')

@section('content')

    <div class="container">
        <h2 class="text-center mt-5 mb-3">{{ env('APP_NAME') }}</h2>
        <div class="card">
            <div class="card-header">
                <button class="btn btn-outline-primary" onclick="createData()">
                    Create New Data
                </button>
            </div>
            <div class="card-body">
                <div id="alert-div">

                </div>
                <table class="table table-bordered" id="datas_table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th width="240px">Action</th>
                        </tr>
                    </thead>
                    <tbody id="datas-table-body">

                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <!-- data form modal -->
    <div class="modal fade" tabindex="-1" id="form-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Data Form</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="error-div"></div>
                    <form>
                        <input type="hidden" name="update_id" id="update_id">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" rows="3" name="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-primary mt-3" id="save-data-btn">Save Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- view data modal -->
    <div class="modal fade" tabindex="-1" id="view-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Data Information</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <b>Name:</b>
                    <p id="name-info"></p>
                    <b>Description:</b>
                    <p id="description-info"></p>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')

    <!-- DataTables Bootstrap 5 CSS -->
    <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">

@endsection

@section('scripts')

    <!-- DataTables Core -->
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <!-- DataTables Bootstrap 5 -->
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript">
        $(function() {
            var baseUrl = $('meta[name=app-url]').attr("content");
            let url = baseUrl + '/api/categories';
            // create a datatable
            $('#datas_table').DataTable({
                processing: true,
                ajax: url,
                // "lengthMenu": [
                //     [10, 25, 50, -1],
                //     [10, 25, 50, "All"]
                // ],
                "order": [
                    [0, "desc"]
                ],
                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'action'
                    },
                ],

            });
        });

        /*
            reload the data on the datatable
        */
        function reloadTable() {
            $('#datas_table').DataTable().ajax.reload();
        }

        /*
            check if form submitted is for creating or updating
        */
        $("#save-data-btn").click(function(event) {
            event.preventDefault();
            if ($("#update_id").val() == null || $("#update_id").val() == "") {
                storeData();
            } else {
                updateData();
            }
        })

        /*
            show modal for creating a record and
            empty the values of form and remove existing alerts
        */
        function createData() {
            $("#alert-div").html("");
            $("#error-div").html("");
            $("#update_id").val("");
            $("#name").val("");
            $("#description").val("");
            showModal('form-modal');
        }

        /*
            submit the form and will be stored to the database
        */
        function storeData() {
            $("#save-data-btn").prop('disabled', true);
            let url = $('meta[name=app-url]').attr("content") + "/api/categories";
            let data = {
                name: $("#name").val(),
                description: $("#description").val(),
            };
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "POST",
                data: data,
                success: function(response) {
                    $("#save-data-btn").prop('disabled', false);
                    let successHtml =
                        '<div class="alert alert-success" role="alert"><b>Data Created Successfully</b></div>';
                    $("#alert-div").html(successHtml);
                    $("#name").val("");
                    $("#description").val("");
                    reloadTable();
                    hideModal('form-modal');
                },
                error: function(response) {
                    $("#save-data-btn").prop('disabled', false);
                    if (typeof response.responseJSON.errors !== 'undefined') {
                        let errors = response.responseJSON.errors;
                        let descriptionValidation = "";
                        if (typeof errors.description !== 'undefined') {
                            descriptionValidation = '<li>' + errors.description[0] + '</li>';
                        }
                        let nameValidation = "";
                        if (typeof errors.name !== 'undefined') {
                            nameValidation = '<li>' + errors.name[0] + '</li>';
                        }

                        let errorHtml = '<div class="alert alert-danger" role="alert">' +
                            '<b>Validation Error!</b>' +
                            '<ul>' + nameValidation + descriptionValidation + '</ul>' +
                            '</div>';
                        $("#error-div").html(errorHtml);
                    }
                }
            });
        }

        /*
            edit record function
            it will get the existing value and show the data form
        */
        function editData(id) {
            let url = $('meta[name=app-url]').attr("content") + "/api/categories/" + id;
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    let data = response.data;
                    $("#alert-div").html("");
                    $("#error-div").html("");
                    $("#update_id").val(data.id);
                    $("#name").val(data.name);
                    $("#description").val(data.description);
                    showModal('form-modal');
                },
                error: function(response) {
                    console.log(response.responseJSON)
                }
            });
        }

        /*
            sumbit the form and will update a record
        */
        function updateData() {
            $("#save-data-btn").prop('disabled', true);
            let url = $('meta[name=app-url]').attr("content") + "/api/categories/" + $("#update_id").val();
            let data = {
                id: $("#update_id").val(),
                name: $("#name").val(),
                description: $("#description").val(),
            };
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "PUT",
                data: data,
                success: function(response) {
                    $("#save-data-btn").prop('disabled', false);
                    let successHtml =
                        '<div class="alert alert-success" role="alert"><b>Data Updated Successfully</b></div>';
                    $("#alert-div").html(successHtml);
                    $("#name").val("");
                    $("#description").val("");
                    reloadTable();
                    hideModal('form-modal');
                },
                error: function(response) {
                    $("#save-data-btn").prop('disabled', false);
                    if (typeof response.responseJSON.errors !== 'undefined') {
                        let errors = response.responseJSON.errors;
                        let descriptionValidation = "";
                        if (typeof errors.description !== 'undefined') {
                            descriptionValidation = '<li>' + errors.description[0] + '</li>';
                        }
                        let nameValidation = "";
                        if (typeof errors.name !== 'undefined') {
                            nameValidation = '<li>' + errors.name[0] + '</li>';
                        }

                        let errorHtml = '<div class="alert alert-danger" role="alert">' +
                            '<b>Validation Error!</b>' +
                            '<ul>' + nameValidation + descriptionValidation + '</ul>' +
                            '</div>';
                        $("#error-div").html(errorHtml);
                    }
                }
            });
        }

        /*
            get and display the record info on modal
        */
        function showData(id) {
            $("#name-info").html("");
            $("#description-info").html("");
            let url = $('meta[name=app-url]').attr("content") + "/api/categories/" + id + "";
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    let data = response.data;
                    $("#name-info").html(data.name);
                    $("#description-info").html(data.description);
                    showModal('view-modal');

                },
                error: function(response) {
                    console.log(response.responseJSON)
                }
            });
        }

        /*
            delete record function
        */
        function destroyData(id) {
            let url = $('meta[name=app-url]').attr("content") + "/api/categories/" + id;
            let data = {
                name: $("#name").val(),
                description: $("#description").val(),
            };
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: "DELETE",
                data: data,
                success: function(response) {
                    let successHtml =
                        '<div class="alert alert-success" role="alert"><b>Data Deleted Successfully</b></div>';
                    $("#alert-div").html(successHtml);
                    reloadTable();
                },
                error: function(response) {
                    console.log(response.responseJSON)
                }
            });
        }

        function showModal(id) {
            const modalEl = document.getElementById(id);
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        }

        function hideModal(id) {
            const modalEl = document.getElementById(id);
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        }

    </script>

@endsection
