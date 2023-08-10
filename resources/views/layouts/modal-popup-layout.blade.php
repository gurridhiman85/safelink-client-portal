<div id="modal-popup" class="modal " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true">
    <div class="modal-dialog {{isset($size) ? $size : ''}}">
        <div class="modal-content" {{ isset($style) ? $style : '' }}>
            <div class="modal-header">
                <h6 class="modal-title pl-2" id="myModalLabel">{{$title}}</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body <?= isset($pd) ? $pd : ''; ?>" style="margin: 7px;">
                {!! $content !!}
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<style>
    .modal-header .close {
        margin-right: -10px !important;
        padding-top: 0px;
    }
</style>
