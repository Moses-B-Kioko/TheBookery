@extends('admin.layouts.app')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">					
	<div class="container-fluid my-2">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1>Create User</h1>
			</div>
			<div class="col-sm-6 text-right">
				<a href="{{ route('users.index')}}" class="btn btn-primary">Back</a>
			</div>
		</div>
	</div>
	<!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
	<!-- Default box -->
	<div class="container-fluid">
            <form action="" method="post" id="userForm" name="userForm" autocomplete="off"> <!-- Disable autofill for the entire form if needed -->
            @csrf
            @method('POST')
            <div class="card">
                <div class="card-body">								
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Name" autocomplete="name"> <!-- Added autocomplete -->
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Email</label>
                                <input type="text" name="email" id="email" class="form-control" placeholder="Email" autocomplete="slug"> <!-- Added autocomplete -->
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" autocomplete="slug"> <!-- Added autocomplete -->
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone" autocomplete="slug"> <!-- Added autocomplete -->
                                <p></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="slug">Status</label>
                                <select name="status" id="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Block</option>
                                </select>
                                <p></p>
                            </div>
                        </div>
                        							
                    </div>
                </div>							
            </div>
            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('users.index')}}" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
        </form>
	</div>
	<!-- /.card -->
</section>
<!-- /.content -->
@endsection

@section('customJs')
<script>


  $("#userForm").submit(function(event){
    event.preventDefault();
    var element = $(this);
    $("button[type=submit]").prop('disabled', true);
    $.ajax({
        url: '{{ route("users.store")}}',
        type: 'post',
        data: element.serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled', false);
            if(response["status"] == true){
                $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#phone").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                $("#password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                window.location.href="{{ route('users.index')}}";

            } else {
                var errors = response['errors'];
                if(errors['name']) {
                    $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name']);
                } else {
                    $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }
                if(errors['email']) {
                    $("#email").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['email']);
                } else {
                    $("#email").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }
                if(errors['phone']) {
                    $("#phone").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['phone']);
                } else {
                    $("#phone").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }
                if(errors['password']) {
                    $("#password").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['password']);
                } else {
                    $("#password").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html("");
                }
            }
        }, error: function(jqXHR, exception){
            console.log("Something went wrong");
        }
    })
  });

 
</script>    
@endsection
