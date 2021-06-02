<div class="btn-group" role="group" aria-label="Basic mixed styles example">
<a href="javascript:void(0)" type="button" data-toggle="tooltip"  data-id="{{$sertif->id}}" data-original-title="Edit" class="btn btn-warning btn-sm editSertif">Edit</a>
  <a href="{{ route('destroy.siomay', $sertif->id) }}" class="btn {{ !request()->ajax() ? 'btn btn-danger btn-sm' : 'btn btn-danger btn-sm' }}" title="{{ __('Delete') }}"
       onclick="event.preventDefault(); if (confirm('{{ __('Hapus data ?') }}')) $('#delete_jurusan_{{ $sertif->id }}_form').submit();">Hapus
        <i class="far fa-trash-alt {{ !request()->ajax() ? 'fa-fw' : '' }}"></i>
    </a>

    <form method="post" action="{{ route('destroy.siomay', $sertif->id) }}" id="delete_jurusan_{{ $sertif->id }}_form" class="d-none">
        @csrf
        @method('delete')
    </form>
</div>