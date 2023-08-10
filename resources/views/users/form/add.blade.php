<?php
$n = isset($record) ? true : false;
?>
<form class="forms-sample ajax-Form" method="post" action="{{ url('/users/store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="exampleInputUsername1">Name</label>
        <input type="text" name="name" class="form-control" id="exampleInputName" placeholder="Name" value="{{ $n ? $record->name : '' }}">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">Email</label>
        <input type="text" name="email" class="form-control" id="exampleInputTerm" placeholder="Email" value="{{ $n ? $record->email : '' }}">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" name="password" class="form-control" id="exampleInputPassword" placeholder="Password">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">Confirm Password</label>
        <input type="password" name="password_confirmation" class="form-control" id="password-confirm" placeholder="Confirm Password">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">User Type</label>
        <select name="user_type" class="form-control">
            <option {{ $n ? ($record->user_type == "" ? 'selected' : '') : '' }} value="">Select</option>
            <option {{ $n ? ($record->user_type == "admin" ? 'selected' : '') : '' }} value="admin">Admin</option>
            <option {{ $n ? ($record->user_type == "user" ? 'selected' : '') : '' }} value="user">User</option>
        </select>
    </div>

    <input type="hidden" name="rec_id" class="form-control" value="{{ $n ? $record->id : '0' }}">
    <button type="submit" class="btn btn-primary me-2">{{ $is_create ? 'Create' : 'Update' }}</button>
    <button type="button" data-bs-dismiss="modal" class="btn btn-light">Cancel</button>
</form>
