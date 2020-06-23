@extends('layouts.app')

@section('content')

    <div class="card mt-5">
        <div class="card-body">
            <h5>Talks</h5>
            <p>Here are a list of talks</p>
            <div class="table-responsive mt-3 pb-3">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Topic</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Date Added</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($talks as $key => $talk)
                        <tr>
                            <td>{{ ($key + 1) }}</td>
                            <td>{{ $talk->topic }}</td>
                            <td>{{ $talk->description ?? 'No description' }}</td>
                            <td>{{ DateTime::createFromFormat("Y-m-d", $talk->date)->format("F jS, Y") }}</td>
                            <td>{{ DateTime::createFromFormat("H:i:s", $talk->time)->format("h:i a") }}</td>
                            <td>{{ DateTime::createFromFormat("Y-m-d H:i:s", $talk->created_at)->format("F jS, Y") }}</td>
                            <td><button class="btn btn-outline-danger remove-talk-btn" data-uuid="{{ $talk->uuid }}">Remove</button></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if($talks->count() <= 0)
                    <div class="text-center mt-5">
                        <h1>No talk yet</h1>
                        <a href="{{ route('talk-add') }}" class="btn btn-outline-primary mt-2">Add Talk</a>
                    </div>
                @endif
            </div>
            <div class="table-responsive mt-3">
                {{ $talks->links() }}
            </div>
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

        $('.remove-talk-btn').click(function (e) {

            let self = $(this), timeout;

            successMsg('Remove Talk', "This talk will be removed, do you want proceed?",
                'Yes, proceed', 'No, cancel', function ({value}) {

                    if (!value) return;

                    timeout = setTimeout(() => {

                        mInstance.addToRequestQueue({
                            url: "{{ route('talk-remove-api') }}",
                            method: 'post',
                            timeout: 10000,
                            dataType: 'json',
                            data: {
                                uuid: self.data('uuid'),
                                '_token': "{{ csrf_token() }}"
                            },
                            beforeSend: () => {
                                swal.showLoading();
                            },
                            success: (data, status, xhr) => {

                                swal.hideLoading();

                                if (data.status !== true) {
                                    errorMsg('Talk Remove Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                                    return false;
                                }

                                successMsg('Talk Remove Successful', data.message);

                                self.closest('tr').fadeOut(600, function () {
                                    $(this).detact();
                                });

                            },
                            ontimeout: () => {
                                swal.hideLoading();
                                errorMsg('Talk Remove Failed', 'Request failed, as it timed out', 'Ok');
                            },
                            error: (data, xhr, status, statusText) => {

                                swal.hideLoading();

                                errorMsg('Talk Remove Failed', typeof data.message !== 'string' ? serializeMessage(data.message) : data.message, 'Ok');
                            }
                        });

                        clearTimeout(timeout);
                    }, 500);
                })
        });
    </script>
@endsection()
