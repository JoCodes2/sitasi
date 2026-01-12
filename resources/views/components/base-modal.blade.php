@props([
    'id',
    'title' => 'Modal Title',
    'size' => 'modal-md',
    'btnId' => 'btnSimpan',
    'btnText' => 'Simpan'
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog {{ $size }} modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="{{ $btnId }}">
                    <i class="fa-solid fa-save mr-1"></i> {{ $btnText }}
                </button>
            </div>
        </div>
    </div>
</div>
