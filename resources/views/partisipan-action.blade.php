<div class="btn-group" role="group" aria-label="Basic mixed styles example">
  <a href="{{ route('destroy.siomay', $partisipan->id) }}" class="btn {{ !request()->ajax() ? 'btn btn-danger btn-sm' : 'btn btn-danger btn-sm' }}" title="{{ __('Delete') }}"
       onclick="event.preventDefault(); if (confirm('{{ __('Hapus data ?') }}')) $('#delete_jurusan_{{ $partisipan->id }}_form').submit();">Hapus
        <i class="far fa-trash-alt {{ !request()->ajax() ? 'fa-fw' : '' }}"></i>
    </a>

    <form method="post" action="{{ route('destroy.siomay', $partisipan->id) }}" id="delete_jurusan_{{ $partisipan->id }}_form" class="d-none">
        @csrf
        @method('delete')
    </form>
</div>