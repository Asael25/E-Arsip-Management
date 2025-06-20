@if(!empty($rakData))
<div class="row">
    @foreach($rakData as $rak => $data)
    <div class="col-md-6 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header {{ $data->count() > 0 ? 'bg-primary' : 'bg-secondary' }} text-white">
                <h5 class="card-title mb-0">Rak {{ $rak }}</h5>
            </div>
            <div class="card-body p-2">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm mb-0">
                        <thead>
                            <tr>
                                <th style="width: 60px"></th>
                                @for($i = 1; $i <= 8; $i++)
                                    <th style="width: 60px">K{{ $i }}</th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @for($baris = 1; $baris <= 8; $baris++)
                            <tr>
                                <td class="text-center">B{{ $baris }}</td>
                                @for($kolom = 1; $kolom <= 8; $kolom++)
                                    @php
                                        $kardus = $data->where('baris_ke', $baris)
                                                      ->where('kolom_ke', $kolom)
                                                      ->first();
                                    @endphp
                                    <td class="{{ $kardus ? 'table-primary position-relative' : 'table-secondary' }}" 
                                        style="height: 40px">
                                        @if($kardus)
                                        <div class="position-relative" 
                                             data-bs-toggle="tooltip" 
                                             data-bs-placement="top"
                                             data-html="true"
                                             title="Kode: {{ $kardus->kd_kardus }}<br>Nomor: {{ $kardus->no_kardus }}<br>Ketersediaan: {{ $kardus->ketersediaan }}">
                                            <div class="text-center">
                                                <small>{{ $kardus->kd_kardus }} <br>
                                                        {{ $kardus->ukuran_kardus }} <br>
                                                        {{ $kardus->status_slot }}
                                                </small>
                                            </div>
                                        </div>
                                        @else
                                        <div class="text-center text-muted">
                                            <small><b>-</b></small>
                                        </div>
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <small>
                        Terisi: {{ $data->count() }} kardus
                    </small>
                    <small>
                        Kosong: {{ 64 - $data->count() }} slot
                    </small>
                </div>
                <div class="progress mt-2" style="height: 5px;">
                    @php
                        $persentase = ($data->count() / 64) * 100;
                    @endphp
                    <div class="progress-bar" role="progressbar" 
                         style="width: {{ $persentase }}%;" 
                         aria-valuenow="{{ $persentase }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="alert text-center">
    Data Tidak Ditemukan
</div>
@endif