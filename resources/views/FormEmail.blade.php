<div class="container p-5">
    <div class="row d-flex justify-content-center">
        <div class="col-md-9 col-xs-9 col-lg-9">
                <h6 id="title" class="mb-3">Queue with database connection</h6>

                <div class="alert alert-secondary" style="font-size: 11px;">
                    set QUEUE_CONNECTION=<span id="connection">database</span> in .env file 
                </div>

                <form id="form-email" class="row mb-3">
                    <div class="col-md-7 mb-3">
                        <input type="hidden" name="connection" class="form-control" value="database" readonly />
                        <input name="email" class="form-control" placeholder="email" />
                    </div>
                    <div class="col-md-7">
                        <button name="btn-submit" class="btn btn-success px-3">Send</button>
                    </div>
                </form>

                <div id="loading">
                </div>

                <div id="message" style="font-size: 12px">
                </div>
        </div>
    </div>
</div>

@push('scripts')

<script>
    $(document).ready(function() {
        $('#myTab a[href="#database"]').tab('show');
    })

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var href = $(this).attr('href').replace("#","");
        $("#title").html(`Queue with ${href} connection`);
        $("#connection").html(href);
        $("#form-email input[name='connection']").val(href);
        clear();
    })

    $("#form-email").submit((e)=> {
        e.preventDefault();
        var email = $("#form-email input[name='email']").val();
        var connection = $("#form-email input[name='connection']").val();
        var url   = window.location.href;
        
        $.ajax({
            type: 'POST',
            url,
            dataType: "json",
            // contentType: 'application/json; charset=utf-8',
            beforeSend: () => loading(true),
            complete: () => loading(false),

            data: { 
                email, connection,
                _token: "{{ csrf_token() }}",
            },
            success: (response) => {
                // console.log(response);                
                $("#message").html(`<span class="text-success">${response.message}</span>`);
            },
            error: (err) => {
                // console.log(err)
                $("#message").html(`<span class="text-danger font-weight-bold">${err.responseJSON.message}</span>`);
            },
        })
    })

    function loading(status) {
        if (status) {
            $("#form-email input[name='email']").attr('disabled', 'disabled');
            $("#form-email button[name='btn-submit']").attr('disabled', 'disabled');
            $("#loading").html('Sending Email...');
        } else {            
            $("#form-email input[name='email']").removeAttr('disabled');
            $("#form-email button[name='btn-submit']").removeAttr('disabled');
            $("#loading").html('');
        }
    }

    function clear() {
        $("#message").html('');
        $("#loading").html('');
    }
</script>
@endpush