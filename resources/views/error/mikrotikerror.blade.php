@extends('layouts.login')

@section('title', '403')

@section('content')
<div
    class="overflow-hidden position-relative radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
            <div class="col-md-8 col-lg-6 col-xxl-6">
                <div class="mb-0 card">
                    <div class="card-body">
                        <div class="py-2 text-center text-nowrap logo-img d-block w-100">
                            <h1>403</h1>
                        </div>
                        <p class="text-center">Gagal saat menghubungkan ke mikrotik</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
