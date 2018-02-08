<!DOCTYPE html>
<html>

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="https://bootswatch.com/4/sketchy/bootstrap.min.css">
    <title>Tasks</title>
</head>

<body>
    {{-- Upper Part --}}
    <div class="container py-3">
        {{-- Errors --}} 
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert"></button>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif 
        {{-- Add success --}} 
        @if (Session::has('status'))
        <div class="alert alert-info fade show alert-message">{{ Session::get('status') }}
            <button type="button" class="close alert-close"></button>
        </div>
        @endif 
        {{-- Add form --}}
        <div class="card border-primary mb-3" style="max-width: 20rem;">
            <div class="card-header">New Task</div>
            <div class="card-body text-primary">
                <form action="" method="POST">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label>Task</label>
                        <input type="text" class="form-control" name="task">
                        <input class="mt-1 btn btn-primary" type="submit" name="submit" value="Add Task">
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Lower Part --}}
    <div class="container">
        {{-- Error update --}}
        <div class="alert alert-danger print-error-msg fade show alert-message" style="display:none">
            <button type="button" class="close alert-close"></button>
            <ul></ul>
        </div>
        {{-- Success update --}}
        <div class="alert alert-success fade show alert-message" id="update-success-alert" style="display:none">
            <button type="button" class="close alert-close"></button>
            <p id="update-success-message"></p>
        </div>
        {{-- List Tasks --}}
        <div class="card border-primary mb-3" style="max-width: 20rem;">
            <div class="card-header">Current Task</div>
            <div class="card-body text-primary">
                <label>Current Task</label>
                @foreach($tasks as $task)
                <div class="row mb-1">
                    <input type="text" id="{{$task->id}}" name="" disabled value="{{$task->title}}">
                    <input type="button" data-index="{{$task->id}}" class="btn edit-button ml-2" name="" value="Edit">
                    <a class="btn btn-primary ml-2" href='{{url("/$task->id")}}'>
                        Delete
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    <script type="text/javascript" src="http://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
    var oldTask;
    $('.edit-button').click(function(event) {
        var editButton = this;
        var inputBox = $('#' + $(this).data('index'));
        if ($(editButton).val() == "Edit") {
            $(inputBox).prop('disabled', false);
            $(editButton).val('Save');
            oldTask = $(inputBox).val();
        } else {
            var updatedTask = $(inputBox).val();
            $.ajax({
                url: 'http://todo.test/' + $(editButton).data('index'),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'POST',
                data: { 'updatedTask': updatedTask },
                success: function(data) {
                    if ($.isEmptyObject(data.error)) {
                        $('#update-success-alert').css('display', 'block');
                        $('#update-success-message').html(data.success);
                        $(inputBox).prop('disabled', true);
                        $(editButton).val('Edit');
                    } else {
                        printErrorMsg(data.error);
                        $(inputBox).val(oldTask);
                    }

                    // console.log(data);
                }
            });
        }
    });

    function printErrorMsg(msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display', 'block');
        $.each(msg, function(key, value) {
            $(".print-error-msg").find("ul").append('<li>' + value + '</li>');
        });
    }
    // Hide alert message instead of removing in DOM
    $('.alert-close').click(function() {
        $('.alert-message').fadeOut('fast');
    });
    </script>
</body>

</html>