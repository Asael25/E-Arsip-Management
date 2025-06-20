@if ($data->count() > 0)
    @foreach ($data as $key => $item)
        <tr>
            <td style="text-align: center">{{ $key + $data->firstItem() }}</td>
            <td style="text-align: center">{{ $item->kd_uk }}</td>
            <td>{{ $item->eselon_2 }}</td>
            <td>{{ $item->eselon_3 }}</td>
            <td>{{ $item->eselon_4 }}</td>
            <td style="text-align: center">
                <!-- Tombol Edit -->
                <a href="{{ route('admin.unit_kerja.edit', $item->id_uk) }}" 
                    class="btn btn-warning btn-sm mb-1 w-100"
                    onclick="sessionStorage.setItem('previous_url', window.location.href)"
                    data-bs-toggle="tooltip"
                    title="Edit data unit kerja">
                    <i class="fas fa-edit"></i> Edit
                </a>                             
                
                <!-- Tombol Hapus -->
                <form action="{{ route('admin.unit_kerja.delete', $item->id_uk) }}" 
                      method="POST" 
                      class="d-inline w-100">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="btn btn-danger btn-sm w-100"
                            onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')"
                            data-bs-toggle="tooltip"
                            title="Hapus data unit kerja">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="6" class="text-center">Data Tidak Ditemukan</td>
    </tr>
@endif