@props([
    'id',
    'title',
    'isForm' => false,
    'action' => '',
    'method' => 'POST',
    'formMethod' => null, // Untuk PUT, PATCH, DELETE
    'hasFile' => false, // Set true jika ada upload file
])

<div class="modal fade" id="{{ $id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            <div class="modal-header">
                <h5 class="modal-title fw-bold">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            @if($isForm)
                <form action="{{ $action }}" method="{{ $method }}" {!! $hasFile ? 'enctype="multipart/form-data"' : '' !!}>
                    @csrf
                    @if($formMethod)
                        @method($formMethod)
                    @endif

                    <div class="modal-body">
                        {{ $slot }}
                    </div>

                    @if(isset($footer))
                        <div class="modal-footer">
                            {{ $footer }}
                        </div>
                    @endif
                </form>
            @else
                <div class="modal-body">
                    {{ $slot }}
                </div>

                @if(isset($footer))
                    <div class="modal-footer">
                        {{ $footer }}
                    </div>
                @endif
            @endif

        </div>
    </div>
</div>