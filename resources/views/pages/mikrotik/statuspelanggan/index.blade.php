@extends('layouts.mikrotik')

@section('title', 'Status Pelanggan')

@section('content')
    <div class="row">
        <div class="col-lg-12 d-flex align-items-stretch">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title">Status Pelanggan</h5>
                </div>
                <div class="p-4 card-body">
                    <ul class="gap-3 mb-3 nav">
                        <li class="nav-item">
                            <a class="btn btn-primary" href="{{ route('secret') }}">Secret</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="btn btn-primary" href="{{ route('active') }}">Active</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="btn btn-primary" href="{{ route('nonactive') }}">Non Active</a>
                        </li>
                    </ul>
                    <div class="table-responsive">
                        <table class="table border table-striped" id="dataTable"
                            style=" width: 100%;
                            min-width: 0;
                            max-width: 200px;
                            white-space: nowrap;">
                            <thead class="mb-0 align-middle text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold" style="width: 20px">No</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">Nama</h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">
                                            {{ isset($data[0]['last-logged-out']) ? 'Terakhir Keluar' : 'Service' }}
                                        </h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">
                                            {{ isset($data[0]['disabled']) ? 'Status' : 'Waktu Aktif' }}
                                        </h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">
                                            {{ isset($data[0]['password']) ? 'Password' : 'Caller ID' }}
                                        </h6>
                                    </th>
                                    <th class="border-bottom-0">
                                        <h6 class="mb-0 fw-semibold">
                                            {{ isset($data[0]['profile']) ? 'Profile' : 'Address' }}
                                        </h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $items)
                                    <tr>
                                        <td class="align-middle border-bottom-0">
                                            <h6 class="mb-0 fw-normal">{{ $key + 1 }}</h6>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">{{ $items['name'] ?? '-' }}</p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">
                                                {{ $items['last-logged-out'] ?? $items['service'] }}
                                            </p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">
                                                @if (isset($items) && array_key_exists('disabled', $items) && $items['disabled'] == 'true')
                                                    <span class="badge badge-danger">{{ $items['disabled'] }}</span>
                                                @elseif(isset($items) && array_key_exists('disabled', $items) && $items['disabled'] == 'false')
                                                    <span class="badge badge-success">{{ $items['disabled'] }}</span>
                                                @else
                                                    {{ $items['uptime'] }}
                                                @endif
                                            </p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <p class="mb-0 fw-normal">
                                                {{ $items['password'] ?? $items['caller-id'] }}
                                            </p>
                                        </td>
                                        <td class="align-middle border-bottom-0">
                                            <P class="mb-0 fw-normal">{{ $items['profile'] ?? $items['address'] }}</P>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
