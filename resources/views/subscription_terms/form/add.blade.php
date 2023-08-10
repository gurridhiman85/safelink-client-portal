<?php
$n = isset($record) ? true : false;
?>
<form class="forms-sample ajax-Form" method="post" action="{{ url('/subscription_terms/store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="exampleInputUsername1">Name</label>
        <input type="text" name="Name" class="form-control" id="exampleInputName" placeholder="Name" value="{{ $n ? $record->Name : '' }}">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Term (In Month)</label>
        <input type="number" name="Term" class="form-control" id="exampleInputTerm" placeholder="Term" value="{{ $n ? $record->Term : '' }}">
    </div>

    <input type="hidden" name="rec_id" class="form-control" value="{{ $n ? $record->id : '0' }}">
    <button type="submit" class="btn btn-primary me-2">{{ $is_create ? 'Create' : 'Update' }}</button>
    <button type="button" data-bs-dismiss="modal" class="btn btn-light">Cancel</button>
</form>
