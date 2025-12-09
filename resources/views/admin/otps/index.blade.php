@extends('layouts.admin')

@section('title', 'OTP Records')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">OTP Records</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>OTP Code</th>
                                <th>Created At</th>
                                <th>Expires At</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($otps as $otp)
                            <tr>
                                <td>{{ $otp->id }}</td>
                                <td>
                                    @if($otp->user)
                                        <strong>{{ $otp->user->name ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $otp->user->email }}</small>
                                    @else
                                        <span class="text-danger">User deleted</span>
                                    @endif
                                </td>
                                <td><code>{{ $otp->otp_code }}</code></td>
                                <td>{{ $otp->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $otp->expires_at->format('Y-m-d H:i:s') }}</td>
                                <td>
                                    @if($otp->is_used)
                                        <span class="badge bg-secondary">Used</span>
                                    @elseif($otp->expires_at < now())
                                        <span class="badge bg-danger">Expired</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No OTP records found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer clearfix">
                    {{ $otps->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table td, .table th {
        vertical-align: middle;
    }
    code {
        background-color: #f8f9fa;
        padding: 2px 4px;
        border-radius: 3px;
        font-size: 0.9em;
    }
</style>
@endsection