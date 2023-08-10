<?php
$n = isset($record) ? true : false;
?>
<form class="forms-sample ajax-Form" method="post" action="{{ url('/master_products/store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label for="exampleInputUsername1">Name</label>
        <input type="text" name="Name" class="form-control" id="exampleInputName" placeholder="Name" value="{{ $n ? $record->Name : '' }}">
    </div>
    <div class="form-group">
        <label for="exampleInputEmail1">Description</label>
        <textarea cols="5" rows="10" name="Descriptions" class="form-control" id="exampleInputDescription">{{ $n ? $record->Descriptions : '' }}</textarea>
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Price</label>
        <input type="text" name="Price" class="form-control" id="exampleInputPrice" placeholder="Price" value="{{ $n ? $record->Price : '' }}">
    </div>

    <div class="form-group">
        <label for="exampleInputPassword1">Photo</label>
        <input type="file" name="Photo" class="form-control" id="exampleInputPhoto" >
    </div>

    <input type="hidden" name="rec_id" class="form-control" value="{{ $n ? $record->id : '0' }}">
    <button type="submit" class="btn btn-primary me-2">{{ $is_create ? 'Create' : 'Update' }}</button>
    <button type="button" data-bs-dismiss="modal" class="btn btn-light">Cancel</button>
</form>
