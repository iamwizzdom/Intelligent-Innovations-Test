@extends('layouts.app')

@section('content')

    <div class="card mt-5">
        <div class="card-body">
            <form method="post" action="{{ route('talk-add') }}" id="add-talk-form">

                <h5 class="mb-4 text-uppercase">Talk Info</h5>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="topic">Topic <small>*</small></label>
                            <input type="text" class="form-control" id="topic" name="topic" placeholder="Enter topic">
                        </div>
                    </div>
                </div> <!-- end row -->

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="description">Description <small>(optional)</small></label>
                            <textarea class="form-control" id="description" name="description"
                                      placeholder="Enter description"></textarea>
                        </div>
                    </div>
                </div> <!-- end row -->

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="date">Date <small>*</small></label>
                            <input type="date" class="form-control" id="date" name="date" placeholder="Enter date">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="time">Time <small>*</small></label>
                            <input type="time" class="form-control" id="time" name="time" placeholder="Enter time">
                        </div>
                    </div>
                </div> <!-- end row -->

                @csrf

                <div class="col-md-2 offset-md-5 text-center mt-2">
                    <button type="submit" class="btn btn-success btn-block btn-rounded"><i
                            class="mdi mdi-content-save"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection()

@section('js')
    <script src="{{ asset('js/net-bridge/net-bridge.js') }}" type="application/javascript"></script>
    <script src="{{ asset('js/bootbox/bootbox.js') }}" type="application/javascript"></script>
    <script src="{{ asset('js/sweetalert/sweetalert.js') }}" type="application/javascript"></script>
    <script>
        function successMsg(title, message, confirmBtnTxt, cancelBtnTxt, btnCallback, isHtml = false) {
            // swal.fire(title, message, 'success')
            swal.fire({
                title: title,
                [isHtml ? 'html': 'text']: message,
                type: "success",
                confirmButtonClass: "btn-success no-border-radius",
                confirmButtonText: confirmBtnTxt ? confirmBtnTxt : 'Ok',
                showCancelButton: (typeof cancelBtnTxt === "string"),
                cancelButtonText: cancelBtnTxt ? cancelBtnTxt : 'Cancel',
                closeOnConfirm: true
            }).then((btnCallback instanceof Function ? function (isConfirm) {
                btnCallback(isConfirm);
            } : null));
        }


        function isEmpty(variable) {
            let string = variable.toString();
            return (
                variable === false
                || variable === null
                || string === "0"
                || string === ""
                || string === " "
            );
        }

        function serializeMessage(object) {
            let message = "", x, count = 0;
            for (x in object) {
                if (object.hasOwnProperty(x)) {
                    if (isEmpty(object[x])) continue;
                    // language=HTML
                    message += (!isEmpty(message) ? "<br>" : "") + (++count + ". " +
                        (typeof object[x] !== "string" ? serializeMessage(object[x]) : object[x]));
                }
            }
            return message;
        }

        function processErrors(xhr) {
            if (xhr.responseJSON) {
                const errors = xhr.responseJSON.errors;
                if (errors) {
                    let msg = '';
                    Object.keys(errors).forEach(key => {
                        errors[key].forEach(err => {
                            msg += `${err} <br/>`
                        })
                    });
                    return msg
                }
            }
        }

        function bootBox(title, message, confirmBtnTxt,
                         confirmBtnCallback, cancelBtnTxt,
                         cancelBtnCallback) {

            let button = (
                typeof cancelBtnTxt === "string" ?
                    {
                        ok: {
                            label: confirmBtnTxt,
                            className: "btn-primary no-border-radius",
                            callback: (confirmBtnCallback instanceof Function ? confirmBtnCallback : null)
                        },
                        cancel: {
                            label: cancelBtnTxt,
                            className: "btn-warning",
                            callback: (cancelBtnCallback instanceof Function ? cancelBtnCallback : null)
                        }
                    } :
                    {
                        ok: {
                            label: confirmBtnTxt,
                            className: "btn-primary no-border-radius",
                            callback: (confirmBtnCallback instanceof Function ? confirmBtnCallback : null)
                        }
                    }
            );

            bootbox.dialog({
                title: title,
                message: message,
                buttons: button
            });
        }

        function errorMsg(title, msg, html = false) {
            if (html) {
                swal.fire({
                    title: title,
                    html: msg,
                    icon: 'error'
                })
            } else {
                swal.fire(
                    title,
                    msg,
                    'error'
                )
            }
        }

        const getSearchParameters = () => {
            let params = window.location.search.substr(1);
            return params != null && params !== "" ? transformToAssocArray(params) : {};
        };

        const transformToAssocArray = (paramStr) => {
            let params = {};
            let paramsArr = paramStr.split("&");
            for (let i = 0; i < paramsArr.length; i++) {
                let tmpArr = paramsArr[i].split("=");
                params[tmpArr[0]] = tmpArr[1];
            }
            return params;
        };

        const serialize = (object) => {
            let list = [], x;
            for (x in object) {
                if (object.hasOwnProperty(x)) {
                    list[list.length] = encodeURIComponent(x) + "=" + encodeURIComponent(
                        null == object[x] ? "" : object[x]);
                }
            }
            return list.join('&');
        };
    </script>
    <script type="application/javascript">

        const mInstance = NetBridge.getInstance();

        $('#add-talk-form').submit(function (e) {

            e.preventDefault();

            let self = $(this);

            mInstance.addToRequestQueue({
                url: self.attr('action'),
                method: 'post',
                timeout: 10000,
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: new FormData(self[0]),
                beforeSend: () => {
                    swal.showLoading();
                },
                success: (data, status, xhr) => {

                    swal.hideLoading();

                    if (data.status !== true) {
                        errorMsg('Talk Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                        return false;
                    }

                    successMsg('Talk Successful', data.message);
                    self[0].reset();

                    setTimeout(() => {
                        window.location.href = "{{ route('home') }}";
                    }, 2000);

                },
                ontimeout: () => {
                    swal.hideLoading();
                    errorMsg('Talk Failed', 'Request failed, as it timed out', 'Ok');
                },
                error: (data, xhr, status, statusText) => {

                    swal.hideLoading();

                    errorMsg('Talk Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                }
            });
        });
    </script>
@endsection()
