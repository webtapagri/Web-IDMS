@extends('adminlte::page')
@section('title', 'FMDB - Role')
@section('content')
<section class="content">
    <div class="row">
        <div class="col-xs-4">
            <span style="font-size:24px">Modules</span>
        </div>
        <div class="col-xs-8" align="right">
            <span href="#" class="btn btn-sm btn-flat btn-danger btn-add">&nbsp;<i class="glyphicon glyphicon-plus" title="Add new data"></i>&nbsp; Add</span>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <table id="data-table" class="table table-bordered table-hover table-condensed" width="100%">
                        <thead>
                            <tr>
                                <th width="10%">Icon</th>
                                <th width="10%">Sort</th>
                                <th width="20%">Name</th>
                                <th>Desc</th>
                                <th width="10%">Active</th>
                                <th width="8%">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </div>
    </div>
</section>
<div id="add-data-modal" class="modal fade" role="dialog">
    <div class="modal-dialog" width="900px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <form id="data-form">
                <div class="modal-body">
                    <div class="box-body">
                        <div class="col-xs-3">
                            <label class="control-label" for="name">Sort</label>
                            <input class="form-control" name='sort' id="sort" maxlength="2" requried>
                        </div>
                        <div class="col-xs-12">
                            <label class="control-label" for="name">Nama</label>
                            <input class="form-control" name='name' id="name" maxlength="200" requried>
                            <input type="hidden" name='edit_id' id="edit_id">
                        </div>
                        <div class="col-xs-12">
                            <label class="control-label" for="name">Description</label>
                            <textarea class="form-control" name='description' id="description"></textarea>
                        </div>
                        <div class="col-xs-6">
                            <label class="control-label" for="name">Icon</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="" id="font-awesome-result"></i></span>
                                <input type="text" class="form-control" placeholder="use fontawesome" name="icon" id="icon">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-flat btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-flat btn-danger" style="margin-right: 5px;">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop
@section('js')
<script>
    var attribute = [];
    jQuery(document).ready(function() {
        jQuery('#data-table').DataTable({
            ajax: "{!! route('get.grid_modules') !!}",
            columns: [{
                    "render": function(data, type, row) {
                        return '<i  class="' + row.icon + '"></i>';
                    }
                },
                {
                    data: 'sort',
                    name: 'sort'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'description',
                    name: 'description'
                },
                {
                    "render": function(data, type, row) {
                        if (row.deleted == 0) {
                            var content = '<span class="badge bg-red">Y</span>';
                        } else {
                            var content = '<span class="badge bg-grey">N</span>';
                        }
                        return content;
                    }
                },
                {
                    "render": function(data, type, row) {
                        var content = '<button class="btn btn-flat btn-xs btn-danger btn-action btn-edit " title="edit data ' + row.id + '" onClick="edit(' + row.id + ')"><i class="fa fa-pencil"></i></button>';
                        content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-activated  ' + (row.deleted == 0 ? '' : 'hide') + '" style="margin-left:5px"  onClick="inactive(' + row.id + ')"><i class="fa fa-trash"></i></button>';
                        content += '<button class="btn btn-flat btn-xs btn-danger btn-action btn-inactivated ' + (row.deleted == 1 ? '' : 'hide') + '" style="margin-left:5px"  onClick="active(' + row.id + ')"><i class="fa fa-check"></i></button>';

                        return content;
                    }
                }
            ],
            columnDefs: [{
                    targets: [0, 5],
                    className: 'text-center',
                    orderable: false
                },
                {
                    targets: [4],
                    className: 'text-center'
                }
            ]
        });

        jQuery("#icon").on('keyup', function() {
            jQuery("#font-awesome-result").removeClass();
            jQuery("#font-awesome-result").addClass(jQuery(this).val());
        });

        jQuery('.btn-add').on('click', function() {
            document.getElementById("data-form").reset();
            jQuery('#role_id').prop('disabled', false);
            jQuery("#edit_id").val("");
            jQuery("#font-awesome-result").removeClass();
            jQuery("#add-data-modal").modal({
                backdrop: 'static',
                keyboard: false
            });
            jQuery("#add-data-modal .modal-title").html("<i class='fa fa-plus'></i> Create new data");
            jQuery("#add-data-modal").modal("show");
        });

        jQuery('.btn-edit').on('click', function() {
            jQuery("#add-data-modal").modal({
                backdrop: 'static',
                keyboard: false
            });
            jQuery("#add-data-modal .modal-title").html("<i class='fa fa-pencil'></i> Edit data");
            jQuery("#add-data-modal").modal("show");
        });

        jQuery('#data-form').on('submit', function(e) {
            e.preventDefault();
            var param = jQuery(this).serialize();
            jQuery.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            jQuery.ajax({
                url: "{{ url('modules/post') }}",
                method: "POST",
                data: param,
                beforeSend: function() {
                    jQuery('.loading-event').fadeIn();
                },
                success: function(result) {
                    if (result.status) {
                        jQuery("#add-data-modal").modal("hide");
                        jQuery("#data-table").DataTable().ajax.reload();
                        notify({
                            type: 'error',
                            message: result.message
                        });
                    } else {
                        notify({
                            type: 'warning',
                            message: result.message
                        });
                    }
                },
                complete: function() {
                    jQuery('.loading-event').fadeOut();
                }
            });


        })
    });

    function edit(id) {
        document.getElementById("data-form").reset();
        jQuery("#edit_id").val(id);
        var result = jQuery.parseJSON(JSON.stringify(dataJson("{{ url('modules/edit/?id=') }}" + id)));
        jQuery("#edit_id").val(result.id);
        jQuery("#name").val(result.name);
        jQuery("#sort").val(result.sort);
        jQuery("#icon").val(result.icon);
        jQuery("#font-awesome-result").removeClass();
        jQuery("#font-awesome-result").addClass(result.icon);
        jQuery("#description").val(result.description);
        jQuery("#add-data-modal .modal-title").html("<i class='fa fa-edit'></i> Update data " + result.name);
        jQuery("#add-data-modal").modal("show");
    }

    function inactive(id) {
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            url: "{{ url('modules/inactive') }}",
            method: "POST",
            data: {
                id: id
            },
            beforeSend: function() {
                jQuery('.loading-event').fadeIn();
            },
            success: function(result) {
                if (result.status) {
                    jQuery("#data-table").DataTable().ajax.reload();
                    notify({
                        type: 'error',
                        message: result.message
                    });
                } else {
                    notify({
                        type: 'warning',
                        message: result.message
                    });
                }
            },
            complete: function() {
                jQuery('.loading-event').fadeOut();
            }
        });
    }

    function active(id) {
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery.ajax({
            url: "{{ url('modules/active') }}",
            method: "POST",
            data: {
                id: id
            },
            beforeSend: function() {
                jQuery('.loading-event').fadeIn();
            },
            success: function(result) {
                if (result.status) {
                    jQuery("#data-table").DataTable().ajax.reload();
                    notify({
                        type: 'error',
                        message: result.message
                    });
                } else {
                    notify({
                        type: 'warning',
                        message: result.message
                    });
                }
            },
            complete: function() {
                jQuery('.loading-event').fadeOut();
            }
        });
    }
</script>
@stop