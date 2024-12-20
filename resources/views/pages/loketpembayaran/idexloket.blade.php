@extends('layouts.backend')

@section('title', 'Data Loket')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="justify-center row align-items-center">
                <div class="col-12 col-md-5">
                    <h4>Haloo, {{ Auth::user()->name }}</h4>
                </div>
                <div class="col-12 col-md-7">
                    @foreach ($lokets as $index => $loket)
                        <form action="{{ route('search.invoice') }}" method="GET">
                            @csrf
                            <input type="hidden" name="kd_loket" value="{{ Crypt::encryptString($loket->kd_loket) }}">
                            <div class="input-group">
                                <select name="tgl_invoice" class="form-select">
                                    <option value="" selected>Semua Invoice</option>
                                    @foreach ($invoiceDates as $tgl_invoice)
                                        <option value="{{ $tgl_invoice }}"
                                            {{ request('tgl_invoice') == $tgl_invoice ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::parse($tgl_invoice)->format('d M Y') }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-success">Lihat Invoice</button>
                            </div>
                            @error('kd_cabang')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
