@if ($data->count() > 0)
    @foreach ($data as $key => $item)
        <tr>
            <td style="text-align: center">{{ $key + $data->firstItem() }}</td>
            <td style="text-align: center">{{ $item->kd_kardus }}</td>
            <td>
                <b>Judul Kardus : </b>{{ $item->judul_kardus ?? '-' }} 
                <br><b>Nomor Kardus : </b>{{ $item->no_kardus ?? '-' }}
                @if($item->shared_with)
                    <br><span class="badge bg-info">Berbagi ruang dengan: {{ $item->shared_with }}</span>
                @endif
            </td>
            <td>
                <b>Kode Kardus : </b>{{ $item->transaksi->kd_transaksi ?? '-' }} 
                <br><b>Judul Arsip : </b>{{ $item->transaksi->judul_transaksi ?? '-' }} 
                <br><b>Tanggal Diterima : </b>{{ $item->transaksi->tgl_diterima ?? '-' }}
            </td>
            <td style="text-align: center">
                @if($item->tgl_penempatan)
                    {{ \Carbon\Carbon::parse($item->tgl_penempatan)->translatedFormat('d F Y') }}
                @else
                    <span class="text-muted">Belum Ditempatkan</span>
                @endif
            </td>
            <td>
                <b>Kode Rak : </b>{{ $item->kd_rak }} 
                <br><b>Keterangan : </b>{{ $item->keterangan }} 
                <br><b>Kolom Ke-</b>{{ $item->kolom_ke }} 
                <br><b>Baris Ke-</b>{{ $item->baris_ke }}
            </td>
            <td style="text-align: center">
                @php
                    $kapasitasPerUkuran = [
                        'kecil' => 4,
                        'sedang' => 3,
                        'besar' => 2
                    ];
            
                    $currentKapasitas = $kapasitasPerUkuran[$item->ukuran_kardus];
                    $sisaSlot = $currentKapasitas - $item->sub_total_kardus;
                    
                    // Status badge
                    $badgeClass = $sisaSlot > 0 ? 'bg-success' : 'bg-danger';
                    $statusText = $sisaSlot > 0 ? "Tersedia $sisaSlot unit" : "Slot Penuh";
                @endphp
            
                <div class="d-flex flex-column">
                    <span>
                        <b>Ukuran: </b>{{ ucfirst($item->ukuran_kardus) }}
                        <br><b>Kapasitas: </b>{{ $currentKapasitas }} per baris
                    </span>
                </div>
            </td>

            <!-- Kode untuk menampilkan status slot dalam Blade template -->
<td style="text-align: center">            
    <div class="d-flex flex-column">
        <span><b>Total: </b>{{ $item->sub_total_kardus }} Kardus</span>
        <br>
        @if($item->status_slot)
            @php
                // Cek apakah perlu parsing untuk status dengan kata "atau"
                if(strpos($item->status_slot, '|atau|') !== false) {
                    $statusArray = explode('|atau|', $item->status_slot);
                    $hasAdditionalInfo = strpos($item->status_slot, '<br>dengan') !== false;
                    
                    if($hasAdditionalInfo) {
                        $parts = explode('<br>dengan', $item->status_slot);
                        $statusArray = explode('|atau|', $parts[0]);
                        $additionalInfo = 'dengan' . $parts[1];
                    } else {
                        $additionalInfo = '';
                    }
                    
                    $isArray = true;
                } else {
                    $isArray = false;
                }
            @endphp
            
            @if($isArray)
                @foreach($statusArray as $index => $status)
                    <span class="badge bg-success p-2 text-wrap d-block mb-1">
                        {{ $status }}
                    </span>
                    @if($index < count($statusArray) - 1)
                        <span class="d-block text-center my-1">atau</span>
                    @endif
                @endforeach
                
                @if(!empty($additionalInfo))
                    <span class="badge bg-success p-2 text-wrap d-block mt-2">
                        {!! $additionalInfo !!}
                    </span>
                @endif
            @else
                <span class="badge {{ strpos($item->status_slot, 'Penuh') !== false ? 'bg-danger' : 'bg-success' }} p-2 text-wrap">
                    {!! $item->status_slot !!}
                </span>
            @endif
        @else
            @php
                $status = $item->getStatusKetersediaan();
            @endphp
            @if(is_array($status['status']))
                @foreach($status['status'] as $index => $stat)
                    <span class="badge {{ $status['class'] }} d-block mb-1">
                        {{ $stat }}
                    </span>
                    @if($index < count($status['status']) - 1)
                        <span class="d-block text-center my-1">atau</span>
                    @endif
                @endforeach
                
                @if(!empty($status['additionalInfo']))
                    <span class="badge {{ $status['class'] }} d-block mt-2">
                        {{ $status['additionalInfo'] }}
                    </span>
                @endif
            @else
                <span class="badge {{ $status['class'] }}">
                    {!! $status['status'] !!}
                </span>
            @endif
        @endif
    </div>
</td>

<td style="text-align: center">{{ $item->ketersediaan }}</td>

            <td style="text-align: center">
                <!-- Tombol Cetak -->
                {{-- <a href="{{ route('kardus.cetak', $item->id_kardus) }}" 
                    class="btn btn-success btn-sm mb-1 w-100" style="background-color:#22915d"
                    target="_blank" type="submit" onclick="return confirm('Apakah Anda Yakin Ingin Mencetak Data Ini?')"
                    data-bs-toggle="tooltip"
                    title="Form peminjaman">
                    <i class="fas fa-print"></i> Form Peminjaman
                </a>  --}}

                <!-- Tombol Edit -->
                <a href="{{ route('admin.kardus.edit', $item->id_kardus) }}" 
                    class="btn btn-warning btn-sm mb-1 w-100"
                    onclick="sessionStorage.setItem('previous_url', window.location.href)"
                    data-bs-toggle="tooltip"
                    title="Edit data kardus">
                    <i class="fas fa-edit"></i> Edit
                </a>                             
                
                <!-- Tombol Hapus -->
                <form action="{{ route('admin.kardus.delete', $item->id_kardus) }}" 
                      method="POST" 
                      class="d-inline w-100">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="btn btn-danger btn-sm w-100"
                            onclick="return confirm('Apakah Anda Yakin Ingin Menghapus Data Ini?')"
                            data-bs-toggle="tooltip"
                            title="Hapus data kardus">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="9" class="text-center">Data Tidak Ditemukan</td>
    </tr>
@endif