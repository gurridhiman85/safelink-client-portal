<?php
$n = isset($record) ? true : false;
?>
<form class="forms-sample ajax-Form" method="post" action="{{ url('/billings/store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="exampleInputUsername1">Company Name</label>
        <input type="text" name="company_name" class="form-control" id="exampleInputName" placeholder="Company Name" value="{{ $n ? $record->company_name : '' }}">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">Billing Email</label>
        <input type="text" name="billing_email" class="form-control" id="exampleInputPrice" placeholder="Billing Email" value="{{ $n ? $record->billing_email : '' }}">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">First Name</label>
        <input type="text" name="first_name" class="form-control" id="exampleInputPrice" placeholder="First Name" value="{{ $n ? $record->first_name : '' }}">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">Last Name</label>
        <input type="text" name="last_name" class="form-control" id="exampleInputPrice" placeholder="Last Name" value="{{ $n ? $record->last_name : '' }}">
    </div>

    <input type="hidden" name="rec_id" class="form-control" value="{{ $n ? $record->id : '0' }}">
    <button type="submit" class="btn btn-primary me-2">{{ $is_create ? 'Create' : 'Update' }}</button>
    <button type="button" data-bs-dismiss="modal" class="btn btn-light">Cancel</button>
</form>
