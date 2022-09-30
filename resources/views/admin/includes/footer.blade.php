<!-- Scripts -->
<script src="{{asset('admins/js/jquery-2.1.1.js') }}"></script>

<!-- jQuery UI -->
<script src="{{asset('plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<script src="{{asset('admins/js/bootstrap.min.js')}}"></script>

<!--metisMenu-->
<script src="{{asset('plugins/metisMenu/jquery.metisMenu.js')}}"></script>

<!--slimscroll-->
<script src="{{asset('plugins/slimscroll/jquery.slimscroll.min.js')}}"></script>

<!-- valiation-->
<script src="{{asset('plugins/validate/jquery.validate.min.js')}}"></script>
<script src="{{ asset('plugins/validate/additional-methods.min.js') }} "></script> 

<!-- Toastr -->
<script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

<!-- Custom and plugin javascript -->
<script src="{{asset('admins/js/inspinia.js')}}"></script>
<script src="{{asset('plugins/pace/pace.min.js')}}"></script>

<script src="{{asset('plugins/chosen/chosen.jquery.js')}}"></script>

{{-- Data table --}}
<script src="{{ asset('plugins/dataTables/jquery.dataTables.js') }}"></script>
<script src="{{ asset('plugins/dataTables/dataTables.bootstrap.js') }}"></script>
<script src="{{ asset('plugins/dataTables/dataTables.responsive.js') }}"></script>
<script src="{{ asset('plugins/dataTables/dataTables.tableTools.min.js') }}"></script>

{{-- Sweetalert2 --}}
<script src="{{ asset('plugins/sweetalert2/es6-promise.auto.min.js') }} "></script> 
<!-- for IE support -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }} "></script> 

<!-- moment -->
{{--  <script type="text/javascript" src="{{ asset('plugins/moment/moment.min.js') }}"></script>  --}}
<script type="text/javascript" src="{{ asset('plugins/combodate/moment.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/plugins/moment/moment-timezone-with-data.min.js') }}"></script>

<!-- bootstrap-datetimepicker -->
<script type="text/javascript" src="{{ asset('plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

<!-- bootstrap-datetimepicker -->
<script type="text/javascript" src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<!-- combodate -->
<script type="text/javascript" src="{{ asset('plugins/combodate/combodate.js') }}"></script>

<!-- time-to jQuery -->
<script type="text/javascript" src="{{asset('plugins/time-to/jquery.time-to.min.js')}}"></script>

<!-- select2 -->
<script type="text/javascript" src="{{ asset('js/plugins/select2/select2.min.js') }}"></script>

<script type="text/javascript">
// Prevent alert message from being displayed
$.fn.dataTable.ext.errMode = 'none';
$(document).ajaxError(function (event, jqxhr, settings, thrownError) {
    if (jqxhr.status == 401 && jqxhr.statusText == 'Unauthorized' && thrownError == 'Unauthorized') {
        window.location.href = "{{url('admin/login')}}";
    }
});

var TIMEZONE_STR = "{{ config('ethiopay.TIMEZONE_STR') }}";
$(document).ready(function () {
    $('.add-button,.center-button').click(function () {
        $(this).button('loading');
    });

    toastr.options = {
        closeButton: true,
        progressBar: true,
        showMethod: 'slideDown',
        timeOut: 5000
    };

    var errorMessage = "{{\Session::has('error')}}" ? "{{\Session::get('error')}}" : '';
    if (errorMessage != "")
        fnToastError(errorMessage);

    var successMessage = "{{\Session::has('success')}}" ? "{{\Session::get('success')}}" : '';
    if (successMessage != "")
        fnToastSuccess(successMessage);

    $(".inquiry_counter").click(function () {
        resetNotification($(this));
    });
});

function deleteRecordByAjax(deleteUrl, moduleName, dataTablesName, deleteType) {
    var deleteAlertStr = "";
    var deleteAlertStr = "";
    if (deleteType == 1)
        deleteAlertStr = "You want to Delete Parmanent " + moduleName + "?";
    else
        deleteAlertStr = "You want to delete " + moduleName + "?";

    swal({
        title: "Are you sure?",
        text: deleteAlertStr,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Remove it!",
        cancelButtonText: "No, cancel!"
    }).then(function () {
        jQuery.ajax({
            url: deleteUrl,
            type: 'DELETE',
            data: {
                "_token": window.Laravel.csrfToken,
                deleteType: deleteType
            },
            success: function (result) {
                dataTablesName.draw(true);
                swal("success!", moduleName + " Deleted successfully.", "success");
            },
            error: function (xhr, status, error) {
                if (xhr.responseJSON && xhr.responseJSON.message != "") {
                    swal("ohh snap!", xhr.responseJSON.message, "error");
                } else {
                    swal("ohh snap!", "Something went wrong", "error");
                }
                ajaxError(xhr, status, error);
            }
        });
    }, function (dismiss) {
        if (dismiss === 'cancel') {
            swal("Cancelled", moduleName + " action is cancelled ", "error");
        }
    });
}

function fnToastSuccess(message) {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        showMethod: 'slideDown',
        timeOut: 4000
    };
    toastr.success(message);
}

function fnToastError(message) {
    toastr.options = {
        closeButton: true,
        progressBar: true,
        showMethod: 'slideDown',
        timeOut: 4000
    };
    toastr.error(message);
}
function ajaxError(xhr, status, error) {
    if (xhr.status == 401) {
        fnToastError("You are not logged in. please login and try again");
    } else if (xhr.status == 403) {
        fnToastError("You have not permission for perform this operations");
    } else if (xhr.responseJSON && xhr.responseJSON.message != "") {
        fnToastError(xhr.responseJSON.message);
    } else {
        fnToastError("Something went wrong , Please try again later.");
    }
}

function checkUserLogin() {
    $.ajax({
        type: 'get',
        url: '{{url("admin/user/checkLogin")}}',
        dataType: 'json',
        async: false,
        success: function (data)
        {
            if (data.code == 200)
            {
                // location.reload();
            }
        },
        error: function (data)
        {
            console.log('Error' + data)
        }
    });
}

function recordDetailByAjax(viewUrl, moduleName) {
    var clone = $("#viewModal").find('.modal-body').find('table>tbody>tr:first-child').clone();
    $("#viewModal").find('.modal-title').html(moduleName);
    jQuery.ajax({
        url: viewUrl,
        type: 'get',
        beforeSend: function (xhr) {
            $("#viewModal").find('.modal-body').find('sk-spinner').removeClass('hide');
            $("#viewModal").modal('show');
        },
        dataType: "json",
        success: function (data) {
            var $tbody, $row, additionalRows;
            if (data.code == 200) {
                $tbody = $("#viewModal").find('table tbody');
                $row = $tbody.find('tr:last');
                $tbody = $("#viewModal").find('table tbody').empty();
                for (var key in data.result) {
                    if (typeof data.result[key]['image'] !== 'undefined')
                        additionalRows = '<tr><th>' + key + '</th><td><img src=' + data.result[key]['image'] + ' width="200"/></td></tr>';
                    else
                        additionalRows = '<tr><th>' + key + '</th><td>' + data.result[key] + '</td></tr>';
                    $tbody.append(additionalRows);
                }
                $("#viewModal").modal('show');
            } else {
                $("#viewModal").modal('hide');
                fnToastError(data.message);
            }
            $("#viewModal").find('.modal-body').find('.sk-spinner').addClass('hide');
        },
        error: function (xhr, status, error) {
            if (xhr.responseJSON && xhr.responseJSON.message != "") {
                fnToastError(xhr.responseJSON.message);
            } else {
                fnToastError("Something went wrong");
            }
            ajaxError(xhr, status, error);
            $("#viewModal").modal('hide');
        }
    });
}

function restorRecordByAjax(restorUrl, moduleName, dataTablesName) {
    var restoreAlertStr = "You want to Restore " + moduleName + "?";
    swal({
        title: "Are you sure?",
        text: restoreAlertStr,
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, Restore it!",
        cancelButtonText: "No, cancel!",
        showLoaderOnConfirm: true,
        allowOutsideClick: false,
        allowEscapeKey: false,
    }).then(function () {
        jQuery.ajax({
            url: restorUrl,
            type: 'get',
            data: {
                "_token": window.Laravel.csrfToken
            },
            success: function (result) {
                fnToastSuccess(result.message);
                dataTablesName.draw();
                swal("success!", moduleName + " Restored successfully.", "success");
            },
            error: function (xhr, status, error) {
                if (xhr.responseJSON && xhr.responseJSON.message != "") {
                    swal("ohh snap!", xhr.responseJSON.message, "error");
                } else {
                    swal("ohh snap!", "Something went wrong", "error");
                }
                ajaxError(xhr, status, error);
                return false;
            }
        });
    }, function (dismiss) {
        if (dismiss === 'cancel') {
            swal("Cancelled", moduleName + " action is cancelled ", "error");
        }
    });
}
</script>