@if ($data->count() > 0)
    @foreach ($data as $key => $item)
        <tr>
            <td style="text-align: center">{{ $key + $data->firstItem() }}</td>
            <td style="text-align: center">{{ $item->kd_transaksi }}</td>
            <td style="text-align: center">{{ $item->judul_transaksi }}</td>
            <td style="text-align: center">{{ $item->tgl_diterima }}</td>
            <td style="text-align: center">
                @if($item->tgl_cetak)
                    {{ \Carbon\Carbon::parse($item->tgl_cetak)->translatedFormat('d F Y') }}
                @else
                    <span class="text-muted">Belum Dicetak</span>
                @endif
            </td>
            <td><b>NIP : </b>{{ $item->nip_penerima ?? '-' }} <br><b>Nama : </b>{{ $item->penerima ?? '-' }} <br><b>Jabatan : </b>{{ $item->jabatan_penerima ?? '-' }}</td>
            <td><b>NIP : </b>{{ $item->pengirim->nip ?? '-' }} <br><b>Nama : </b>{{ $item->pengirim->nama_pegawai ?? '-' }} <br><b>Jabatan : </b>{{ $item->pengirim->jabatan ?? '-' }}</td>
            <td style="text-align: center">{{ $item->jumlah_kardus }}</td>
            <td style="text-align:center">
                <!-- Tombol Cetak -->
                <a href="{{ route('arsip.cetak', $item->id_transaksi) }}" 
                    class="btn btn-success btn-sm mb-1 w-100" style="background-color:#22915d"
                    target="_blank" type="submit" onclick="return confirm('Apakah Anda Yakin Ingin Mencetak Data Ini?')"
                    data-bs-toggle="tooltip"
                    title="Cetak berita acara">
                    <i class="fas fa-print"></i> Cetak Berita Acara
                </a> 

                <!-- Tombol Edit -->
                <a href="{{ route('admin.arsip.edit', $item->id_transaksi) }}" 
                    class="btn btn-warning btn-sm mb-1 w-100"
                    onclick="sessionStorage.setItem('previous_url', window.location.href)"
                    data-bs-toggle="tooltip"
                    title="Edit data arsip">
                    <i class="fas fa-edit"></i> Edit
                </a>                             
                
                <!-- Tombol Hapus -->
                <form action="{{ route('admin.arsip.delete', $item->id_transaksi) }}" 
                      method="POST" 
                      class="d-inline w-100">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="btn btn-danger btn-sm w-100"
                            onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')"
                            data-bs-toggle="tooltip"
                            title="Hapus data arsip">
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