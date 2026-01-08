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
                            <th>Slug</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Status</th>
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
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" class="form-control" id="price" name="price">
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="short_description" class="form-label">Short Description</label>
                            <textarea class="form-control" id="short_description" rows="2" name="short_description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" rows="4" name="description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-outline-primary mt-2" id="save-data-btn">Save Product</button>
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
                    <b>Category:</b>
                    <p id="category-info"></p>
                    <b>Slug:</b>
                    <p id="slug-info"></p>
                    <b>Price:</b>
                    <p id="price-info"></p>
                    <b>Quantity:</b>
                    <p id="quantity-info"></p>
                    <b>Status:</b>
                    <p id="status-info"></p>
                    <b>Short Description:</b>
                    <p id="short-description-info"></p>
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

        function showModal(id) {
            const modalEl = document.getElementById(id);
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (!modal) {
                modal = new bootstrap.Modal(modalEl);
            }
            modal.show();
        }

        function hideModal(id) {
            const modalEl = document.getElementById(id);
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
        }

        $(function() {
            var baseUrl = $('meta[name=app-url]').attr("content");
            let url = baseUrl + '/api/products';
            // create a datatable
            $('#datas_table').DataTable({
                processing: true,
                ajax: url,
                // "lengthMenu": [
                //     [10, 25, 50, -1],
                //     [10, 25, 50, "All"]
                // ],
                order: [[0, "desc"]],
                columns: [
                    { data: 'name' },
                    { data: 'slug' },
                    { data: 'price' },
                    { data: 'quantity' },
                    { data: 'status' },
                    { data: 'action' },
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
            $("#category_id").val("");
            $("#price").val("");
            $("#quantity").val("");
            $("#status").val("active");
            $("#short_description").val("");
            $("#description").val("");
            showModal('form-modal');
        }

        /*
            submit the form and will be stored to the database
        */
        function storeData() {
            $("#save-data-btn").prop('disabled', true);
            let url = $('meta[name=app-url]').attr("content") + "/api/products";
            let data = {
                name: $("#name").val(),
                category_id: $("#category_id").val(),
                price: $("#price").val(),
                quantity: $("#quantity").val(),
                status: $("#status").val(),
                short_description: $("#short_description").val(),
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
                    $("#category_id").val("");
                    $("#price").val("");
                    $("#quantity").val("");
                    $("#status").val("active");
                    $("#short_description").val("");
                    $("#description").val("");
                    reloadTable();
                    hideModal('form-modal');
                },
                error: function(response) {
                    $("#save-data-btn").prop('disabled', false);
                    let errors = response.responseJSON.errors || {};
                    let errorHtml = '<div class="alert alert-danger"><b>Validation Error!</b><ul>';
                    for (let field in errors) errorHtml += '<li>' + errors[field][0] + '</li>';
                    errorHtml += '</ul></div>';
                    $("#error-div").html(errorHtml);
                }
            });
        }

        /*
            edit record function
            it will get the existing value and show the data form
        */
        function editData(id) {
            let url = $('meta[name=app-url]').attr("content") + "/api/products/" + id;
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    let data = response.data;
                    $("#alert-div").html("");
                    $("#error-div").html("");
                    $("#update_id").val(data.id);
                    $("#name").val(data.name);
                    $("#category_id").val(data.category_id);
                    $("#price").val(data.price);
                    $("#quantity").val(data.quantity);
                    $("#status").val(data.status);
                    $("#short_description").val(data.short_description);
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
            let url = $('meta[name=app-url]').attr("content") + "/api/products/" + $("#update_id").val();
            let data = {
                id: $("#update_id").val(),
                name: $("#name").val(),
                category_id: $("#category_id").val(),
                price: $("#price").val(),
                quantity: $("#quantity").val(),
                status: $("#status").val(),
                short_description: $("#short_description").val(),
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
                    $("#category_id").val("");
                    $("#price").val("");
                    $("#quantity").val("");
                    $("#status").val("active");
                    $("#short_description").val("");
                    $("#description").val("");
                    reloadTable();
                    hideModal('form-modal');
                },
                error: function(response) {
                    $("#save-data-btn").prop('disabled', false);
                    let errors = response.responseJSON.errors || {};
                    let errorHtml = '<div class="alert alert-danger"><b>Validation Error!</b><ul>';
                    for (let field in errors) errorHtml += '<li>' + errors[field][0] + '</li>';
                    errorHtml += '</ul></div>';
                    $("#error-div").html(errorHtml);
                }
            });
        }

        /*
            get and display the record info on modal
        */
        function showData(id) {
            $("#name-info").html("");
            $("#slug-info").html("");
            $("#category-info").html("");
            $("#price-info").html("");
            $("#quantity-info").html("");
            $("#status-info").html("");
            $("#short-description-info").html("");
            $("#description-info").html("");
            let url = $('meta[name=app-url]').attr("content") + "/api/products/" + id + "";
            $.ajax({
                url: url,
                type: "GET",
                success: function(response) {
                    let data = response.data;
                    $("#name-info").html(data.name);
                    $("#slug-info").html(data.slug);
                    $("#category-info").html(data.category.name);
                    $("#price-info").html(data.price);
                    $("#quantity-info").html(data.quantity);
                    $("#status-info").html(data.status);
                    $("#short-description-info").html(data.short_description);
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
            if (!confirm("Are you sure you want to delete this product?")) return;
            let url = $('meta[name=app-url]').attr("content") + "/api/products/" + id;
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: url,
                type: "DELETE",
                success: function() {
                    $("#alert-div").html('<div class="alert alert-success"><b>Product Deleted Successfully</b></div>');
                    reloadTable();
                }
            });
        }

    </script>

@endsection
