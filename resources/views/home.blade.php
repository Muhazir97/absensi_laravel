@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            @if(session('status'))
                <div class="alert alert-success">
                   {!! session('status') !!}
                </div>
            @endif
            @if(session('sudah'))
                <div class="alert alert-danger">
                   {!! session('sudah') !!}
                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Panel Absen</div>

                <div class="panel-body">
                    
                        <form action=" {{ url('/absen') }}" method="post">
                            <table class="table table-responsive">
                            {{ csrf_field() }}
                            <tr>
                                <td>
                                    <select class="form-control mb-2" name="nama" required="">
                                        <option value="">-- Pilih Nama --</option>
                                        <option value="IIP IHWANUDIN">IIP IHWANUDIN</option>
                                        <option value="KRISNU KUNCORO HADI">KRISNU KUNCORO HADI</option>
                                        <option value="M RUKI SAEFULLOH">M RUKI SAEFULLOH</option>
                                        <option value="MUHAMAD ALFI YASIN">MUHAMAD ALFI YASIN</option>
                                        <option value="MUHAMMAD ADITIYA">MUHAMMAD ADITIYA</option>
                                        <option value="MUHAMMAD AMIN MURODI">MUHAMMAD AMIN MURODI</option>
                                        <option value="MUHAMMAD DZIKRI RAMADHAN">MUHAMMAD DZIKRI RAMADHAN</option>
                                        <option value=" MUHAZIR"> MUHAZIR</option>
                                        <option value="MULUS RAHAYU">MULUS RAHAYU</option>
                                        <option value="OKY ROSYADI">OKY ROSYADI</option>
                                        <option value="RAKA FIKRI FADHILLAH">RAKA FIKRI FADHILLAH</option>
                                        <option value="RISQI NURALIF">RISQI NURALIF</option>
                                        <option value="ROCHMATAL LIL ALAMIN">ROCHMATAL LIL ALAMIN</option>
                                        <option value="ROFFI SUNARYA">ROFFI SUNARYA</option>
                                        <option value=" SARIPUDIN">  SARIPUDIN</option>
                                  </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Note" name="note">
                                </td>
                                <td>
                                    <input type="text" class="form-control" placeholder="Keuangan" name="gaji">
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-flat btn-primary" name="btnIn" {{$info['btnIn']}} >Absen Masuk </button>
                                </td>
                                <td>
                                    <button type="submit" class="btn btn-flat btn-primary" name="btnOut" {{$info['btnOut']}} >Absen Pulang</button>
                                </td>
                            </tr>
                            </table>
                        </form>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Riwayat Absensi</div>

                <div class="panel-body">
                    <form action="{{ URL::to('/home') }}" method="get">
                        <input type="text" class="form-control form-control-sm form-control border-light text-center" name="search" placeholder="Search Nama" style="border-radius: 20px" value="{{ request()->search ? request()->search : '' }}">
                    </form><br>

                     <table class="table table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th> No </th>
                                <th> Nama </th>
                                <th> Tanggal</th>
                                <th> Jam Masuk</th>
                                <th> Jam Pulang</th>
                                <th> Note</th>
                                <th> Keuangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;?>
                            @forelse($data_absen as $absen)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{$absen->nama}}</td>
                                <td>{{date('d F Y', strtotime($absen->date))}}</td>
                                <td>{{$absen->time_in}}</td>
                                <td>{{$absen->time_out}}</td>
                                <td>{{$absen->note}}</td>
                                <td>Rp. {{ number_format($absen->gaji, 0, ',','.') }}</td>
                            </tr>
                             @empty
                             <tr>
                                 <td colspan="4">
                                    <b><i>Tidak ada data yang tersedia</i></b>
                                 </td>
                             </tr>
                            @endforelse
                           <tr>
                             <td colspan="6" rowspan="" headers=""><i><b>Total Keuangan</i></b></td>
                             <td colspan="" rowspan="" headers="">
                              <i><b> Rp. {{ number_format($hitung_gaji, 0, ',','.') }} </i></b>
                             </td>  
                          </tr>
                           
                        </tbody>
                    </table>
                    <div align="center">
                      {!!$data_absen->links()!!}  
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
